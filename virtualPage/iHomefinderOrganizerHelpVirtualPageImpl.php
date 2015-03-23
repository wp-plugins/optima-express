<?php

class iHomefinderOrganizerHelpVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path = "property-organizer-help";
	
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
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderOrganizerHelpFilterImpl');
		
		$requestData = 'method=handleRequest&viewType=json&requestType=property-organizer-help';
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "phpStyle", "true");
		
		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
		
		iHomefinderLogger::getInstance()->debug($requestData);
		iHomefinderLogger::getInstance()->debug('End iHomefinderOrganizerHelpFilterImpl');
		
		return $body;
	}
}