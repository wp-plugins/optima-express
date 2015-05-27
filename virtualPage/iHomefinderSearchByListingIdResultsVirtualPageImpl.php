<?php

class iHomefinderSearchByListingIdResultsVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	//default path used for URL Rewriting
	private $path="id-listing-results";
	
	public function getTitle() {
		return "Search By Listing ID Results";
	}
	
	function getPageTemplate() {
		
	}
	
	public function getPath() {
		return $this->path;
	}
		
	public function getContent() {
		iHomefinderStateManager::getInstance()->saveLastSearch();
		$this->remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "results-by-listing-id")
		;
		$this->remoteRequest->addParameters($_REQUEST);
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
		$body = $this->remoteRequest->getContent($this->remoteResponse);
		return $body;
	}
		
}