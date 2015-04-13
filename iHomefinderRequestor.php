<?php

class iHomefinderRequestor {
	
	private $parameters = array();
	
	public function __construct() {
		
	}
	
	public function remoteGetRequest($cacheExpiration = 0) {
		$url = iHomefinderLayoutManager::getInstance()->getExternalUrl();
		
		//append the jsessionid to the end of the url
		if(!strpos(strtolower($url), ";jsessionid=")) {
			$ihfSessionId = iHomefinderStateManager::getInstance()->getIhfSessionId();
			$url = $url . ";jsessionid=" . $ihfSessionId;
		}
		
		$authenticationToken = iHomefinderAdmin::getInstance()->getAuthenticationToken();
		$this->addParameter("authenticationToken", $authenticationToken);
		
		//We don"t try to get subscriber information for ajax requests
		//because of cookie related complications.
		if(!strpos(strtolower($url), "subscriberid=")) {
			$subscriber = iHomefinderStateManager::getInstance()->getCurrentSubscriber();
			if(!is_null($subscriber) && "" != $subscriber) {
				$subscriberId = $subscriber->getId();
				$this->addParameter("subscriberId", $subscriberId);
			}
		}
					
		//If the url does not have the lead capture id then try to add it
		$urlHasLeadCapture = strrpos($url, "leadCaptureId=");
		if($urlHasLeadCapture == false) {
			$leadCaptureId = iHomefinderStateManager::getInstance()->getLeadCaptureId();
			if(!is_null($leadCaptureId) && "" != $leadCaptureId) {
				$this->addParameter("leadCaptureId", $leadCaptureId);
			}
		}
			
		$this->addParameter("version", iHomefinderConstants::VERSION);
		$userAgent = $_SERVER["HTTP_USER_AGENT"];
		if($userAgent != null) {
			$userAgent = urlencode($userAgent);
			$this->addParameter("uagent", $userAgent);	
		}
		
		$this->addParameter("loadJQuery", false);
		$this->addParameter("leadCaptureSupport", true);
		//if rememberme cookie is set then append variable to url
		if(isSet($_COOKIE["ihf_rmuser"])) {
			$this->addParameter("rmuser", true);
		}
		
		$url = iHomefinderUtility::getInstance()->buildUrl($url, $this->parameters);
		
		$ihfid = site_url() . ";" . "WordpressPlugin";
		$wp_version = get_bloginfo("version");
		$ihfUserInfo = "WordPress/" . $wp_version . "; " . get_bloginfo("url");
		//modified user-agent in the request header to pass original user-agent
		//This information is used by spring-mobile library to determine 
		//if request came from mobile devices
		//This can also be acheived by using is_mobile wordpress function
		//user-agent information that wordpress provides is now added to 
		//ihfuserinfo variable
		
		$requestArgs = array(
			"timeout" => "200",
			"ihfid" => $ihfid,
			"ihfUserInfo" => $ihfUserInfo,
			"user-agent" => $userAgent
		);
		
		iHomefinderLogger::getInstance()->debug("ihfUrl: " . $url);
		iHomefinderLogger::getInstance()->debug("before request");
		$response = wp_remote_get($url, $requestArgs);
		iHomefinderLogger::getInstance()->debug("after request");
		iHomefinderLogger::getInstance()->debugDumpVar($response);
		
		if(is_wp_error($response)) {
			$contentInfo = null;
		} else {
			$responseBody = wp_remote_retrieve_body($response);
			$contentType = wp_remote_retrieve_header($response, "content-type");
			if($contentType != null && $contentType == "text/xml;charset=UTF-8") {
				$contentInfo = simplexml_load_string($responseBody, null, LIBXML_NOCDATA);
			} else {
				$contentInfo = json_decode($responseBody);
			}
			if($response["response"]["code"] >= 400) {
				//This is specifically for listings that are not found. We set status from java code to "404 not found"
				if($response["response"]["code"] == 404) {
					global $wp_query;
					$wp_query->set_404();
					status_header(404);
					nocache_headers();
				}
			}
		}
		
		//Save the leadCaptureId, if we get it back.
		if(isset($contentInfo->leadCaptureId) && !empty($contentInfo->leadCaptureId)) {
			iHomefinderStateManager::getInstance()->saveLeadCaptureId($contentInfo->leadCaptureId);
		}
		
		if(isset($contentInfo->ihfSessionId)) {
			iHomefinderStateManager::getInstance()->saveIhfSessionId($contentInfo->ihfSessionId);
		}
		
		if(isset($contentInfo->searchContext)) {
			iHomefinderStateManager::getInstance()->setSearchContext($contentInfo->searchContext);
		}

		if(isset($contentInfo->listingInfo)) {
			$listingInfo = $contentInfo->listingInfo;
			$listingNumber = "";
			$listingAddress = "";
			$boardId = "";
			$clientPropertyId = "";
			$sold = "false";
			
			$hasListingInfo = false;
			if(isset($listingInfo->listingNumber) && isset($listingInfo->boardId)) {
				$listingNumber = $listingInfo->listingNumber;
				$boardId = $listingInfo->boardId;
				$hasListingInfo = true;
				if(isset($listingInfo->clientPropertyId)) {
					$clientPropertyId = $listingInfo->clientPropertyId;
				}
				if(isset($listingInfo->listingAddress)) {
					$listingAddress = $listingInfo->listingAddress;
				}
				if(isset($listingInfo->sold)) {
					$sold = $listingInfo->sold;
				}
				$listingInfo = new iHomefinderListingInfo($listingNumber, $boardId, $listingAddress, $clientPropertyId, $sold);
				iHomefinderStateManager::getInstance()->setCurrentListingInfo($listingInfo);					
			}
		}
			
		if(!$this->isError($contentInfo) && isset($contentInfo->subscriberInfo)) {
			$subscriberData = $contentInfo->subscriberInfo;
			$subscriberInfo = iHomefinderSubscriber::getInstance($subscriberData->subscriberId,$subscriberData->name, $subscriberData->email);
			iHomefinderStateManager::getInstance()->saveSubscriberLogin($subscriberInfo);
		}
		
		if(!$this->isError($contentInfo) && isset($contentInfo->searchSummary)) {
			$searchSummary = $contentInfo->searchSummary;
			iHomefinderStateManager::getInstance()->saveSearchSummary($searchSummary);
		}
			
		return $contentInfo;
	}
	
	/**
	 * only used for registration
	 */
	public function remotePostRequest() {
		
		$url = iHomefinderLayoutManager::getInstance()->getExternalUrl();
		$ihfid = site_url() . ";" . "WordpressPlugin";
		$wp_version = get_bloginfo("version");
		$ihfUserInfo = "WordPress/" . $wp_version . "; " . get_bloginfo("url");
		//modified user-agent in the request header to pass original user-agent
		//This information is used by spring-mobile library to determine
		//if request came from mobile devices
		//This can also be acheived by using is_mobile wordpress function
		//user-agent information that wordpress provides is now added to
		//ihfuserinfo variable
		
		$userAgent = $_SERVER["HTTP_USER_AGENT"];
		if($userAgent != null) {
			$userAgent = urlencode($userAgent);
		}
		
		$data = $this->parameters;
		
		$requestArgs = array(
			"timeout" => "200",
			"body" => $data,
			"ihfid" => $ihfid,
			"ihfUserInfo" => $ihfUserInfo,
			"user-agent" => $userAgent
		);
		
		iHomefinderLogger::getInstance()->debug("ihfUrl: " . $url);
		iHomefinderLogger::getInstance()->debugDumpVar($data);
		iHomefinderLogger::getInstance()->debug("before request");
		$response = wp_remote_post($url, $requestArgs);
		iHomefinderLogger::getInstance()->debug("after request");
		iHomefinderLogger::getInstance()->debugDumpVar($response);
		
		if(is_wp_error($response)) {
			$contentInfo = null;
		} else {
			if($response["response"]["code"] >= 400) {
				$responseBody = wp_remote_retrieve_body($response);
				$contentInfo = new stdClass();
				$contentInfo->view = $responseBody;
			} else {
				$responseBody = wp_remote_retrieve_body($response);
				
				$contentType = wp_remote_retrieve_header($response, "content-type");
				if($contentType != null && $contentType == "text/xml;charset=UTF-8") {
					$contentInfo = simplexml_load_string($responseBody, null, LIBXML_NOCDATA);	
				}
				else{
					$contentInfo = json_decode($responseBody);
				}
			}
		}
			
		return $contentInfo;
	}
	
	public function isError($contentInfo) {
		$result = false;
		if(is_null($contentInfo) || property_exists($contentInfo, "error")) {
			$result = true;
		}
		return $result;
	}

	/**
	 *
	 * Extract the content from the response.
	 * @param $contentInfo
	 */
	public function getContent($contentInfo) {
		$content = "";
		if(is_null($contentInfo)) {
			//We could reach this code, if the iHomefinder services are down.
			$content = "<br />Sorry we are experiencing system issues.  Please try again.<br />";
		} else if (property_exists($contentInfo, "error")) {
			//Report the error from iHomefinder
			$content = "<br />" . $contentInfo->error . "<br />";
		} else if(property_exists($contentInfo, "view")) {
			//success, display the view
			$content = html_entity_decode($contentInfo->view, null, "UTF-8");
		}
		return $content;
	}
	
	/**
	 *
	 * Extract JSON from the response for ajax requests.
	 * @param $contentInfo
	 */
	public function getJson($contentInfo) {
		$json = "";
		if(property_exists($contentInfo, "json")) {
			//success, return the json
			$json = $contentInfo->json;
		}
		return $json;
	}
	
	public function addParameter($name, $value) {
		$this->parameters[$name] = $value;
		return $this;
	}
	
	public function addParameters($parameters) {
		foreach($parameters as $name => $value) {
			$this->addParameter($name, $value);
		}
		return $this;
	}
	
}