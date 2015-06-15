<?php

class iHomefinderOrganizerSendSubscriberPasswordVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function getTitle() {
		return "Email Password";
	}
	
	public function getPermalink() {
		return "property-organizer-send-login";
	}
	
	public function getContent() {
		$email = iHomefinderUtility::getInstance()->getQueryVar("email");
		$this->remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "property-organizer-password-email")
			->addParameter("email", $email)
		;
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}
	
}