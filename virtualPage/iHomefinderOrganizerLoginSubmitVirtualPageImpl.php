<?php

class iHomefinderOrganizerLoginSubmitVirtualPageImpl extends iHomefinderAbstractPropertyOrganizerVirtualPage {
	
	public function getTitle() {
		return "Organizer Login";
	}
	
	public function getPermalink() {
		return "property-organizer-login-submit";
	}
	
	public function getContent() {
		$rememberMe = iHomefinderUtility::getInstance()->getRequestVar("rememberMe");
		if($rememberMe === "1") {
			iHomefinderStateManager::getInstance()->setRememberMe(true);
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