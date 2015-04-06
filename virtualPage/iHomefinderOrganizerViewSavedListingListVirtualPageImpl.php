<?php

class iHomefinderOrganizerViewSavedListingListVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path="property-organizer-saved-listings";	
	
	public function __construct() {
		
	}
	public function getTitle() {
		return "Saved Listing List";
	}	


	public function getPageTemplate() {
		
	}
	
	public function getPath() {
		return $this->path;
	}
			
	public function getContent() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderOrganizerViewSavedListingListFilterImpl');
		iHomefinderStateManager::getInstance()->saveLastSearch();
		
		$requestData = 'method=handleRequest&viewType=json&requestType=property-organizer-view-saved-listing-list';
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "includeSearchSummary", "true");
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "phpStyle", "true");
		$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);
		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
		
		iHomefinderLogger::getInstance()->debug($requestData);
		iHomefinderLogger::getInstance()->debug('End iHomefinderOrganizerViewSavedListingListFilterImpl');
		
		return $body;
	}
}