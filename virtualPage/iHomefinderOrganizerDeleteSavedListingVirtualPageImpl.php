<?php

class iHomefinderOrganizerDeleteSavedListingVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path = "property-organizer-delete-saved-listing-submit";
	
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
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderOrganizerDeleteSavedListingVirtualPageImpl');

		$savedListingId=iHomefinderUtility::getInstance()->getQueryVar('savedListingID');		

		$requestData = 'method=handleRequest&viewType=json&requestType=property-organizer-delete-saved-listing-submit';
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "savedListingId", $savedListingId);
		//iHomefinderRequestor will append the subscriber id to this request.	
		
		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
					
		iHomefinderLogger::getInstance()->debug($requestData);
		iHomefinderLogger::getInstance()->debug('End iHomefinderOrganizerDeleteSavedListingVirtualPageImpl');
		
		return $body;
	}
}