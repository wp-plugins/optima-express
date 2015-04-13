<?php

class iHomefinderOrganizerSendSubscriberPasswordVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path="property-organizer-send-login";
	
	public function __construct() {
		
	}
	
	public function getTitle() {
		return "Email Password";
	}			
	
	public function getPageTemplate() {
		
	}
	
	public function getPath() {
		return $this->path;
	}
	
	public function getContent() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderOrganizerSendSubscriberPasswordFilterImpl');
		
		$email=iHomefinderUtility::getInstance()->getQueryVar('email');
					
		$requestData = 'method=handleRequest&viewType=json&requestType=property-organizer-password-email';
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "email", $email);
		
		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);	
		
		iHomefinderLogger::getInstance()->debug($requestData);
		iHomefinderLogger::getInstance()->debug('End iHomefinderOrganizerSendSubscriberPasswordFilterImpl');
		
		return $body;
	}
}