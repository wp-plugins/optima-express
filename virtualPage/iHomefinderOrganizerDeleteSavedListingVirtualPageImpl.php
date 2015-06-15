<?php

class iHomefinderOrganizerDeleteSavedListingVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function getTitle() {
		return "Saved Listing List";
	}

	public function getPermalink() {
		return "property-organizer-delete-saved-listing-submit";
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
	}
	
}