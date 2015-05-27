<?php

class iHomefinderOrganizerEmailUpdatesConfirmationVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path = "email-updates-confirmation";
	
	public function getTitle() {
		return "Email Updates Confirmation";
	}	
	
	public function getPageTemplate() {
		
	}
	
	public function getPath() {
		return $this->path;
	}
	
	public function getContent() {
		$message = iHomefinderUtility::getInstance()->getQueryVar("message");		
		$this->remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "property-organizer-email-updates-confirmation")
			->addParameter("message", $message)
		;
		$this->remoteRequest->addParameters($_REQUEST);
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
		$body = $this->remoteRequest->getContent($this->remoteResponse);
		return $body;
	}
		
}
