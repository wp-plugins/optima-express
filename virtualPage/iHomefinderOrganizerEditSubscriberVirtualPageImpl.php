<?php

class iHomefinderOrganizerEditSubscriberVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path = "property-organizer-edit-subscriber";	
	public function __construct() {
		
	}
	public function getTitle() {
		return "Organizer Help";
	}	

	public function getPageTemplate() {
		
	}
	
	public function getPath() {
		return $this->path;
	}
			
	public function getContent() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderOrganizerEditSubscriberVirtualPageImpl');
		
		$requestData = 'method=handleRequest&viewType=json&requestType=property-organizer-edit-subscriber';
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "phpStyle", "true");
		$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);
		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
		
		iHomefinderLogger::getInstance()->debug($requestData);
		iHomefinderLogger::getInstance()->debug('End iHomefinderOrganizerEditSubscriberVirtualPageImpl');
		
		return $body;
	}
}