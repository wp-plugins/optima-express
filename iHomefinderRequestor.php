<?php

class iHomefinderRequestor {
	
	private $parameters = array();
	private $cacheExpiration = 0;
	
	public function __construct() {
		
	}
	
	public function remoteGetRequest() {
		
		//only add user specific info if the request is not cacheable
		if(!$this->isCacheable()) {
			
			//add subscriber id to the reqest parameters
			$subscriber = iHomefinderStateManager::getInstance()->getCurrentSubscriber();
			if($subscriber !== null) {
				$subscriberId = $subscriber->getId();
				$this->addParameter("subscriberId", $subscriberId);
			}
			
			//add lead capture id to the reqest parameters
			$leadCaptureId = iHomefinderStateManager::getInstance()->getLeadCaptureId();
			$this->addParameter("leadCaptureId", $leadCaptureId);
			
			//add user agent to the reqest parameters
			if(array_key_exists("HTTP_USER_AGENT", $_SERVER)) {
				$userAgent = $_SERVER["HTTP_USER_AGENT"];
				$this->addParameter("uagent", $userAgent);
			}
			
			//if remember me cookie add it to the reqest parameters
			if(array_key_exists("ihf_rmuser", $_COOKIE)) {
				$this->addParameter("rmuser", true);
			}
			
		}
		
		//add authentication token to the reqest parameters
		$authenticationToken = iHomefinderAdmin::getInstance()->getAuthenticationToken();
		$this->addParameter("authenticationToken", $authenticationToken);
		
		//add the plugin version to the reqest parameters
		$this->addParameter("version", iHomefinderConstants::VERSION);
		
		$this->addParameter("loadJQuery", false);
		$this->addParameter("leadCaptureSupport", true);
		
		$externalUrl = iHomefinderLayoutManager::getInstance()->getExternalUrl();
		
		//add jsession id to the end of the url
		$sessionId = iHomefinderStateManager::getInstance()->getIhfSessionId();
		$requestUrl = $externalUrl . ";jsessionid=" . $sessionId;
		$requestUrl = iHomefinderUtility::getInstance()->buildUrl($requestUrl, $this->getParameters());
		
		$ihfid = iHomefinderUrlFactory::getInstance()->getBaseUrl() . ";" . "WordpressPlugin";
		$ihfUserInfo = "WordPress/" . get_bloginfo("version") . "; " . iHomefinderUrlFactory::getInstance()->getBaseUrl();
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
		);
		
		if(isset($userAgent)) {
			$requestArgs["user-agent"] = $userAgent;
		}
		
		$response = null;
		if($this->isCacheable()) {
			$response = iHomefinderCacheUtility::getInstance()->getItem($this->getParameters());
		}
		
		if($response === null) {
			iHomefinderLogger::getInstance()->debug("ihfUrl: " . $requestUrl);
			iHomefinderLogger::getInstance()->debugDumpVar($requestArgs);
			iHomefinderLogger::getInstance()->debug("before request");
			$response = wp_remote_get($requestUrl, $requestArgs);
			iHomefinderLogger::getInstance()->debug("after request");
			iHomefinderLogger::getInstance()->debugDumpVar($response);
			if(!is_wp_error($response) && $this->isCacheable() && $response["response"]["code"] < 400) {
				iHomefinderCacheUtility::getInstance()->updateItem($this->getParameters(), $response, $this->getCacheExpiration());
			}
		}
		
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
		
		
		if(!$this->isError($contentInfo) && !$this->isCacheable()) {
			
			//Save the leadCaptureId, if we get it back.
			if(property_exists($contentInfo, "leadCaptureId") && !empty($contentInfo->leadCaptureId)) {
				iHomefinderStateManager::getInstance()->saveLeadCaptureId($contentInfo->leadCaptureId);
			}
			
			if(property_exists($contentInfo, "ihfSessionId")) {
				iHomefinderStateManager::getInstance()->saveIhfSessionId($contentInfo->ihfSessionId);
			}
			
			if(property_exists($contentInfo, "searchContext")) {
				iHomefinderStateManager::getInstance()->setSearchContext($contentInfo->searchContext);
			}
			
			if(property_exists($contentInfo, "listingInfo")) {
				$listingInfo = $contentInfo->listingInfo;
				$listingNumber = "";
				$listingAddress = "";
				$boardId = "";
				$clientPropertyId = "";
				$sold = "false";
					
				$hasListingInfo = false;
				if(property_exists($listingInfo, "listingNumber") && property_exists($listingInfo, "boardId")) {
					$listingNumber = $listingInfo->listingNumber;
					$boardId = $listingInfo->boardId;
					$hasListingInfo = true;
					if(property_exists($listingInfo, "clientPropertyId")) {
						$clientPropertyId = $listingInfo->clientPropertyId;
					}
					if(property_exists($listingInfo, "listingAddress")) {
						$listingAddress = $listingInfo->listingAddress;
					}
					if(property_exists($listingInfo, "sold")) {
						$sold = $listingInfo->sold;
					}
					$listingInfo = new iHomefinderListingInfo($listingNumber, $boardId, $listingAddress, $clientPropertyId, $sold);
					iHomefinderStateManager::getInstance()->setCurrentListingInfo($listingInfo);
				}
			}
				
			if(property_exists($contentInfo, "subscriberInfo")) {
				$subscriberInfo = $contentInfo->subscriberInfo;
				$subscriber = new iHomefinderSubscriber($subscriberInfo->subscriberId, $subscriberInfo->name, $subscriberInfo->email);
				iHomefinderStateManager::getInstance()->saveSubscriberLogin($subscriber);
			}
			
			if(property_exists($contentInfo, "searchSummary")) {
				$searchSummary = $contentInfo->searchSummary;
				iHomefinderStateManager::getInstance()->saveSearchSummary($searchSummary);
			}
			
		}
			
		return $contentInfo;
	}
	
	/**
	 * only used for registration
	 */
	public function remotePostRequest() {
		
		$requestUrl = iHomefinderLayoutManager::getInstance()->getExternalUrl();
		$ihfid = iHomefinderUrlFactory::getInstance()->getBaseUrl() . ";" . "WordpressPlugin";
		$ihfUserInfo = "WordPress/" . get_bloginfo("version") . "; " . iHomefinderUrlFactory::getInstance()->getBaseUrl();
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
		
		$requestArgs = array(
			"timeout" => "200",
			"body" => $this->getParameters(),
			"ihfid" => $ihfid,
			"ihfUserInfo" => $ihfUserInfo,
			"user-agent" => $userAgent
		);
		
		iHomefinderLogger::getInstance()->debug("ihfUrl: " . $requestUrl);
		iHomefinderLogger::getInstance()->debugDumpVar($requestArgs);
		iHomefinderLogger::getInstance()->debug("before request");
		$response = wp_remote_post($requestUrl, $requestArgs);
		iHomefinderLogger::getInstance()->debug("after request");
		iHomefinderLogger::getInstance()->debugDumpVar($response);
		
		if(is_wp_error($response)) {
			$contentInfo = null;
		} else {
			$responseBody = wp_remote_retrieve_body($response);
			if($response["response"]["code"] >= 400) {
				$contentInfo = new stdClass();
				$contentInfo->view = $responseBody;
			} else {
				$contentType = wp_remote_retrieve_header($response, "content-type");
				if($contentType != null && $contentType == "text/xml;charset=UTF-8") {
					$contentInfo = simplexml_load_string($responseBody, null, LIBXML_NOCDATA);	
				} else {
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
			$content = "<br />Sorry we are experiencing system issues. Please try again.<br />";
		} elseif(property_exists($contentInfo, "error")) {
			//Report the error from iHomefinder
			$content = "<br />" . $contentInfo->error . "<br />";
		} elseif(property_exists($contentInfo, "view")) {
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
	
	public function getParameters() {
		return $this->parameters;
	}
	
	public function setCacheExpiration($cacheExpiration) {
		$this->cacheExpiration = $cacheExpiration;
	}
	
	public function getCacheExpiration() {
		return $this->cacheExpiration;
	}
	
	public function isCacheable() {
		$result = false;
		if(is_int($this->cacheExpiration) && $this->cacheExpiration > 0) {
			$result = true;
		}
		return $result;
	}
	
}