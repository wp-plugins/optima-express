<?php

class iHomefinderOrganizerResendConfirmationVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path = "property-organizer-resend-confirmation-email";
	
	public function __construct() {
		
	}
	
	public function getTitle() {
		return "Resend Confirmation Email";
	}	
	
	public function getPageTemplate() {
		
	}
	
	public function getPath() {
		return $this->path;	
	}	
			
	public function getContent() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderOrganizerResendConfirmationFilterImpl');
		
		$email=iHomefinderUtility::getInstance()->getQueryVar('email');
		$password=iHomefinderUtility::getInstance()->getQueryVar('password');
		$name=iHomefinderUtility::getInstance()->getQueryVar('name');
		$phone=iHomefinderUtility::getInstance()->getQueryVar('phone');
		$agentId=iHomefinderUtility::getInstance()->getQueryVar('agentId');
		$afterLoginUrl=iHomefinderUtility::getInstance()->getRequestVar('afterLoginUrl');
		
		$requestData = 'method=handleRequest&viewType=json&requestType=property-organizer-resend-confirm-email';
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "email", $email);
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "afterLoginUrl", $afterLoginUrl);
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "password", $password);
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "name", $name);
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "phone", $phone);
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "agentId", agentId);
		
		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
		
		iHomefinderLogger::getInstance()->debug($requestData);
		iHomefinderLogger::getInstance()->debug('End iHomefinderOrganizerResendConfirmationFilterImpl');
		
		return $body;
	}
}