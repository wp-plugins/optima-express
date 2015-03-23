<?php

class iHomefinderOrganizerEmailUpdatesConfirmationVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path = "email-updates-confirmation";
	
	public function __construct() {
		
	}
	public function getTitle() {
		return "Email Updates Confirmation";
	}	

	public function getPageTemplate() {
		
	}

	public function getPath() {
		return $this->path;
	}
	
	public function getContent() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderOrganizerEmailUpdatesConfirmationVirtualPageImpl');
		$message=iHomefinderUtility::getInstance()->getQueryVar('message');		
		$requestData = 'method=handleRequest&viewType=json&requestType=property-organizer-email-updates-confirmation';
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "message", $message);
		
		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
		
		iHomefinderLogger::getInstance()->debug($requestData);
		iHomefinderLogger::getInstance()->debug('End iHomefinderOrganizerEmailUpdatesConfirmationVirtualPageImpl');
		
		return $body;
	}		
}
