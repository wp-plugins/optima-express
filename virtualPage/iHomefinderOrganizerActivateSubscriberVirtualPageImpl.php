<?php

class iHomefinderOrganizerActivateSubscriberVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path = "property-organizer-activate";
	
	public function __construct() {
		
	}
	public function getTitle() {
		return "Subscriber Activation";
	}		
			
	public function getPageTemplate() {
		
	}
	
	public function getPath() {
		return $this->path;			
	}
	
	public function getContent() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderOrganizerActivateSubscriberVirtualPageImpl');
		
		$email=iHomefinderUtility::getInstance()->getQueryVar('email');
		
		$requestData = 'method=handleRequest&viewType=json&requestType=property-organizer-activate-subscriber';
		$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);
		
		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
		
		iHomefinderLogger::getInstance()->debug($requestData);
		iHomefinderLogger::getInstance()->debug('End iHomefinderOrganizerActivateSubscriberVirtualPageImpl');
		
		return $body;
	}
}