<?php

class iHomefinderOrganizerViewSavedListingListVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function getTitle() {
		return "Saved Listing List";
	}
	
	public function getPermalink() {
		return "property-organizer-saved-listings";
	}
	
	public function getContent() {
		iHomefinderStateManager::getInstance()->saveLastSearch();
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "property-organizer-view-saved-listing-list")
			->addParameter("includeSearchSummary", true)
			->addParameter("phpStyle", true)
		;
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}
	
}