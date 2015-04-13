<?php

class iHomefinderOrganizerViewSavedListingListVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path="property-organizer-saved-listings";	
	
	public function getTitle() {
		return "Saved Listing List";
	}	


	public function getPageTemplate() {
		
	}
	
	public function getPath() {
		return $this->path;
	}
			
	public function getContent() {
		iHomefinderStateManager::getInstance()->saveLastSearch();
		$this->remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "property-organizer-view-saved-listing-list")
			->addParameter("includeSearchSummary", true)
			->addParameter("phpStyle", true)
		;
		$this->remoteRequest->addParameters($_REQUEST);
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
		$body = $this->remoteRequest->getContent($this->remoteResponse);
		return $body;
	}
}