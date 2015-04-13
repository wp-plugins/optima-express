<?php

class iHomefinderOrganizerSendSubscriberPasswordVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path="property-organizer-send-login";
	
	public function getTitle() {
		return "Email Password";
	}			
	
	public function getPageTemplate() {
		
	}
	
	public function getPath() {
		return $this->path;
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
		$body = $this->remoteRequest->getContent($this->remoteResponse);	
		return $body;
	}
}