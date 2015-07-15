<?php

class iHomefinderOrganizerLoginSubmitVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function getTitle() {
		return "Organizer Login";
	}
	
	public function getPermalink() {
		return "property-organizer-login-submit";
	}
	
	public function getContent() {
		//if rememberMe parameter is set create a cookie "rmuser" with leadcaptureid
		$rememberMe = iHomefinderUtility::getInstance()->getRequestVar("rememberMe");
		if($rememberMe === "1") {
			iHomefinderStateManager::getInstance()->createRememberMeCookie();
		}
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "property-organizer-login-submit")
		;
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}
	
}