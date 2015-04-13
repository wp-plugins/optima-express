<?php

class iHomefinderOrganizerLoginSubmitVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path="property-organizer-login-submit";
	
	public function getTitle() {
		return "Organizer Login";
	}
				
	public function getPageTemplate() {
		
	}
	
	public function getPath() {
		return $this->path;	
	}
	
	public function getContent() {
		//if rememberMe parameter is set create a cookie "rmuser" with leadcaptureid
		if(isset($_REQUEST["rememberMe"]) && trim($_REQUEST["rememberMe"]) == "1") {
			iHomefinderStateManager::getInstance()->createRememberMeCookie();
		}
		$this->remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "property-organizer-login-submit")
		;
		$subscriberId=iHomefinderUtility::getInstance()->getQueryVar("subscriberID");
		if($subscriberId == null || trim($subscriberId) == "") {
			//If no subscriber id, then get the authentication info from the request and pass it along
			$this->remoteRequest->addParameters($_REQUEST);
		} else {
			$this->remoteRequest->addParameter("subscriberId", $subscriberId);
		}
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
		$body = $this->remoteRequest->getContent($this->remoteResponse);
		return $body;
	}
	
}