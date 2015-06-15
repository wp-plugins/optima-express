<?php

class iHomefinderOrganizerViewSavedSearchListVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path="property-organizer-view-saved-search-list";
	
	public function getTitle() {
		return "Saved Search List";
	}			
		
	public function getPageTemplate() {
		
	}
	
	public function getPath() {
		return $this->path;
	}
			
	public function getContent() {
		$this->remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "property-organizer-view-saved-search-list")
			->addParameter("phpStyle", true)
		;
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
		$body = $this->remoteRequest->getContent($this->remoteResponse);
		return $body;
	}
}