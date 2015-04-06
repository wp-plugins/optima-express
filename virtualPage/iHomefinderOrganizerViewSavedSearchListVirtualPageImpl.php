<?php

class iHomefinderOrganizerViewSavedSearchListVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path="property-organizer-view-saved-search-list";
	
	public function __construct() {
		
	}
	public function getTitle() {
		return "Saved Search List";
	}			
		
	public function getPageTemplate() {
		
	}
	
	public function getPath() {
		return $this->path;
	}
			
	public function getContent() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderOrganizerViewSavedSearchListFilterImpl');
		
		$requestData = 'method=handleRequest&viewType=json&requestType=property-organizer-view-saved-search-list';
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "phpStyle", "true");
		
		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
		
		iHomefinderLogger::getInstance()->debug($requestData);
		iHomefinderLogger::getInstance()->debug('End iHomefinderOrganizerViewSavedSearchListFilterImpl');
		
		return $body;
	}
}