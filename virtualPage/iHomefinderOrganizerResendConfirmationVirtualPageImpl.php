<?php

class iHomefinderOrganizerResendConfirmationVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path = "property-organizer-resend-confirmation-email";
	
	public function getTitle() {
		return "Resend Confirmation Email";
	}	
	
	public function getPageTemplate() {
		
	}
	
	public function getPath() {
		return $this->path;	
	}	
			
	public function getContent() {
		$email = iHomefinderUtility::getInstance()->getQueryVar("email");
		$password = iHomefinderUtility::getInstance()->getQueryVar("password");
		$name = iHomefinderUtility::getInstance()->getQueryVar("name");
		$phone = iHomefinderUtility::getInstance()->getQueryVar("phone");
		$agentId = iHomefinderUtility::getInstance()->getQueryVar("agentId");
		$afterLoginUrl = iHomefinderUtility::getInstance()->getRequestVar("afterLoginUrl");
		$this->remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "property-organizer-resend-confirm-email")
			->addParameter("email", $email)
			->addParameter("password", $password)
			->addParameter("name", $name)
			->addParameter("phone", $phone)
			->addParameter("agentId", $agentId)
			->addParameter("afterLoginUrl", $afterLoginUrl)
		;
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
		$body = $this->remoteRequest->getContent($this->remoteResponse);
		return $body;
	}
}