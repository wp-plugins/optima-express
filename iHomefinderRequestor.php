<?php

class iHomefinderRequestor{
	
	private $cacheUtility;
	private static $instance;

	private function __construct() {
		$cacheUtility = new iHomefinderCacheUtility();
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new iHomefinderRequestor();
		}
		return self::$instance;
	}
	
	public function remoteGetRequest($data = null, $cacheExpiration = 0) {
		iHomefinderLogger::getInstance()->debug("Begin iHomefinderRequestor.remoteGetRequest: ");
		
		$ihfUrl = iHomefinderLayoutManager::getInstance()->getExternalUrl();
		
		//append the jsessionid to the end of the url
		if(!strpos(strtolower($ihfUrl), ";jsessionid=")) {
			$ihfSessionId=iHomefinderStateManager::getInstance()->getIhfSessionId();
			$ihfUrl = $ihfUrl . ";jsessionid=" . $ihfSessionId;
		}
		
		//append the query string
		$ihfUrl = $ihfUrl . "?" . $data;
		
		$authenticationToken=iHomefinderAdmin::getInstance()->getAuthenticationToken();
		$ihfUrl = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
		
		//We don't try to get subscriber information for ajax requests
		//because of cookie related complications.
		if(!strpos(strtolower($ihfUrl), "subscriberid=")) {
			$subscriber = iHomefinderStateManager::getInstance()->getCurrentSubscriber();
			if(!is_null($subscriber) && '' != $subscriber) {
				$subscriberId=$subscriber->getId();
				iHomefinderLogger::getInstance()->debug('subscriberId: ' . $subscriberId);
				$ihfUrl = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($ihfUrl, "subscriberId", $subscriberId);
			}
		}
					
		//If the url does not have the lead capture id then try to add it
		$ihfUrlHasLeadCapture=strrpos($ihfUrl, "leadCaptureId=");
		if($ihfUrlHasLeadCapture == false) {
			$leadCaptureId = iHomefinderStateManager::getInstance()->getLeadCaptureId();
			iHomefinderLogger::getInstance()->debug("leadCaptureId=" . $leadCaptureId);
			if(!is_null($leadCaptureId) && '' != $leadCaptureId) {
				$ihfUrl = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($ihfUrl, "leadCaptureId", $leadCaptureId);
			}
		}
			
		$ihfUrl = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($ihfUrl, "version", iHomefinderConstants::VERSION);
		$userAgent=$_SERVER['HTTP_USER_AGENT'];
		if($userAgent != null) {
			$userAgent=urlencode($userAgent);
			$ihfUrl = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($ihfUrl, "uagent", $userAgent);	
		}
		
		$ihfUrl = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($ihfUrl, "loadJQuery", "false");
		$ihfUrl = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($ihfUrl, "leadCaptureSupport", "true");
		//if rememberme cookie is set then append variable to url
		if(isSet($_COOKIE["ihf_rmuser"])) {
			$ihfUrl = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($ihfUrl, "rmuser", "true");
		}
		
		iHomefinderLogger::getInstance()->debug("ihfUrl: " . $ihfUrl);
		$ihfid=site_url() + ";" + "WordpressPlugin";
		$wp_version = get_bloginfo("version");
		$ihfUserInfo= 'WordPress/' . $wp_version . '; ' . get_bloginfo('url');
		//modified user-agent in the request header to pass original user-agent
		//This information is used by spring-mobile library to determine 
		//if request came from mobile devices
		//This can also be acheived by using is_mobile wordpress function
		//user-agent information that wordpress provides is now added to 
		//ihfuserinfo variable
	
		$requestArgs = array("timeout"=>"200", "ihfid"=> $ihfid,"ihfUserInfo"=> $ihfUserInfo,"user-agent"=> $userAgent);		
		iHomefinderLogger::getInstance()->debug("before request");
		$response = wp_remote_get($ihfUrl, $requestArgs);

		iHomefinderLogger::getInstance()->debug("after request");
		
		if(is_wp_error($response)) {
			$contentInfo=null;
		} else {
			$responseBody = wp_remote_retrieve_body($response);
			iHomefinderLogger::getInstance()->debug('responseBody: ' . $responseBody);
			try{
				$contentType=wp_remote_retrieve_header($response, "content-type");
				//$ihfSessionId=wp_remote_retrieve_header($response, "ihfSessionId");
				if($contentType != null && $contentType == "text/xml;charset=UTF-8") {
					$contentInfo=simplexml_load_string($responseBody, null, LIBXML_NOCDATA);
				}
				else{
					$contentInfo=json_decode($responseBody);
				}
			}catch (Exception $e) {
				var_dump($e);
			}
			if($response['response']['code'] >= 400) {
				//This is specifically for listings that are 
				//not found. We set status from java code to '404 not found'
				//$contentInfo->view = $responseBody;
				if($response['response']['code'] == 404) {
						global $wp_query;
						$wp_query->set_404();
						status_header(404);
						nocache_headers();
					}
				}
				
		}
		
		iHomefinderLogger::getInstance()->debug("after get body");
		
		//Save the leadCaptureId, if we get it back.
		if(isset($contentInfo->leadCaptureId) && !empty($contentInfo->leadCaptureId)) {
			iHomefinderLogger::getInstance()->debug("calling saveLeadCaptureId with leadCaptureId=" . $contentInfo->leadCaptureId);
			iHomefinderStateManager::getInstance()->saveLeadCaptureId($contentInfo->leadCaptureId);
		}
		
		if(isset($contentInfo->ihfSessionId)) {
			iHomefinderStateManager::getInstance()->saveIhfSessionId($contentInfo->ihfSessionId);
		}		
		
		if(isset($contentInfo->searchContext)) {
			iHomefinderStateManager::getInstance()->setSearchContext($contentInfo->searchContext);
		}	

		if(isset($contentInfo->listingInfo)) {
			$listingInfo=$contentInfo->listingInfo;
			$listingNumber="";
			$listingAddress="";
			$boardId="";
			$clientPropertyId="";
			$sold="false";
			
			$hasListingInfo=false;
			if(isset($listingInfo->listingNumber) && isset($listingInfo->boardId)) {
				$listingNumber=$listingInfo->listingNumber;
				$boardId=$listingInfo->boardId;
				$hasListingInfo=true;
				if(isset($listingInfo->clientPropertyId)) {
					$clientPropertyId=$listingInfo->clientPropertyId;
				}
				if(isset($listingInfo->listingAddress)) {
					$listingAddress=$listingInfo->listingAddress;
				}
				if(isset($listingInfo->sold)) {
					$sold=$listingInfo->sold;
				}
				$listingInfo = new iHomefinderListingInfo($listingNumber, $boardId, $listingAddress, $clientPropertyId, $sold);
				iHomefinderStateManager::getInstance()->setCurrentListingInfo($listingInfo);					
			}
		}
			
		if(!iHomefinderRequestor::getInstance()->isError($contentInfo) && isset($contentInfo->subscriberInfo)) {
			$subscriberData=$contentInfo->subscriberInfo;
			$subscriberInfo=iHomefinderSubscriber::getInstance($subscriberData->subscriberId,$subscriberData->name, $subscriberData->email);
			iHomefinderStateManager::getInstance()->saveSubscriberLogin($subscriberInfo);
		}
		
		if(!iHomefinderRequestor::getInstance()->isError($contentInfo) && isset($contentInfo->searchSummary)) {
			$searchSummary=$contentInfo->searchSummary;
			iHomefinderStateManager::getInstance()->saveSearchSummary($searchSummary);
		}
		
		iHomefinderLogger::getInstance()->debug("End iHomefinderRequestor.remoteGetRequest: ");
			
		return $contentInfo;
	}
	
	/**
	 * only used for registration
	 */
	public function remotePostRequest($data) {
		iHomefinderLogger::getInstance()->debug("Begin iHomefinderRequestor.remoteGetRequest: ");
		
		$ihfUrl = iHomefinderLayoutManager::getInstance()->getExternalUrl();
		iHomefinderLogger::getInstance()->debug("ihfUrl: " . $ihfUrl);
		$ihfid=site_url() + ";" + "WordpressPlugin";
		$wp_version = get_bloginfo("version");
		$ihfUserInfo= 'WordPress/' . $wp_version . '; ' . get_bloginfo('url');
		//modified user-agent in the request header to pass original user-agent
		//This information is used by spring-mobile library to determine
		//if request came from mobile devices
		//This can also be acheived by using is_mobile wordpress function
		//user-agent information that wordpress provides is now added to
		//ihfuserinfo variable
		
		$requestArgs = array('timeout'=>'200', 'body'=>$data, "ihfid"=> $ihfid,"ihfUserInfo"=> $ihfUserInfo,"user-agent"=> $userAgent);
		$response = wp_remote_post($ihfUrl, $requestArgs);
		
		iHomefinderLogger::getInstance()->debug("iHomefinderRequestor.remoteGetRequest post data ");
		iHomefinderLogger::getInstance()->debugDumpVar($data);
		iHomefinderLogger::getInstance()->debugDumpVar($response);

		if(is_wp_error($response)) {
			$contentInfo=null;
		} else {
			if($response['response']['code'] >= 400) {
				$responseBody = wp_remote_retrieve_body($response);
				$contentInfo = new stdClass();
				$contentInfo->view = $responseBody;
			} else {
				$responseBody = wp_remote_retrieve_body($response);
				
				$contentType=wp_remote_retrieve_header($response, "content-type");
				if($contentType != null && $contentType == "text/xml;charset=UTF-8") {
					$contentInfo=simplexml_load_string($responseBody, null, LIBXML_NOCDATA);	
				}
				else{
					$contentInfo=json_decode($responseBody);
				}
			}
							
			iHomefinderLogger::getInstance()->debugDumpVar($responseBody);
		}
			
		iHomefinderLogger::getInstance()->debug("iHomefinderRequestor.remoteGetRequest response ");
		iHomefinderLogger::getInstance()->debug("End iHomefinderRequestor.remoteGetRequest: ");
			
		return $contentInfo;
	}

	public function appendQueryVarIfNotEmpty($ihfUrl, $queryVarName, $queryVarValue) {
		if(isset($queryVarValue, $queryVarName)) {
			$queryVarValue=urlencode($queryVarValue);
			$trimmedValue=trim($queryVarValue);
			if('' != $trimmedValue) {
				$ihfUrl = $ihfUrl . "&" . $queryVarName . "=" . $trimmedValue;
			}

		}
		return $ihfUrl;
	}

	public function isError($contentInfo) {
		$result=false;
		if(is_null($contentInfo) || property_exists($contentInfo, "error")) {
			$result=true;
		}
		return $result;
	}

	/**
	 *
	 * Extract the content from the response.
	 * @param $contentInfo
	 */
	public function getContent($contentInfo) {
		$content='';
		
		if(is_null($contentInfo)) {
			//We could reach this code, if the iHomefinder services are down.
			$content = "<br/>Sorry we are experiencing system issues.  Please try again.<br/>";
		}
		else if (property_exists($contentInfo, "error")) {
			//Report the error from iHomefinder
			$content = "<br/>" . $contentInfo->error . "</br/>";
		}
		else if(property_exists($contentInfo, "view")) {
			//success, display the view
			$content = html_entity_decode($contentInfo->view, null, 'UTF-8');
		}
		
		return $content;
	}
	
		/**
	 *
	 * Extract JSON from the response for ajax requests.
	 * @param $contentInfo
	 */
	public function getJson($contentInfo) {
		$json='';
		
		if(property_exists($contentInfo, "json")) {
			//success, return the json
			$json = $contentInfo->json;
		}
		
		return $json;
	}		
	
	
	public function addVarsToUrl($url, $arrayOfVars) {
		foreach($arrayOfVars as $key=>$val) {
			$paramValue=null;
			if(is_array($val)) {
				foreach($val as $value) {
					if($paramValue != null) {
						$paramValue .=  ",";
					}
					$paramValue .=  $value;
				}
			} else {
				$paramValue=$val;
			}
			$url = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($url, $key, $paramValue);
		}
			
		return $url;
	}
	
}