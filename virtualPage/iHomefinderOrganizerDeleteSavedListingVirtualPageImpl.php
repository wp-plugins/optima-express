<?php

class iHomefinderOrganizerDeleteSavedListingVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path = "property-organizer-delete-saved-listing-submit";

	public function getTitle() {
		return "Saved Listing List";
	}
		
	public function getPageTemplate() {
		
	}

	public function getPath() {
		return $this->path;	
	}
	
	public function getContent() {
		$savedListingId = iHomefinderUtility::getInstance()->getQueryVar("savedListingId");		
		$this->remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "property-organizer-delete-saved-listing-submit")
			->addParameter("savedListingId", $savedListingId)
		;
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
		$body = $this->remoteRequest->getContent($this->remoteResponse);
		return $body;
	}
	
}