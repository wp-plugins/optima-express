<?php

class iHomefinderSearchByAddressResultsVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	//default path used for URL Rewriting
	private $path="address-listing-results";
	
	public function getTitle() {
		return "Search By Address Results";
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
			->addParameter("requestType", "results-by-address")
			->addParameter("includeSearchSummary", true)
		;
		$this->remoteRequest->addParameters($_REQUEST);
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
		$body = $this->remoteRequest->getContent($this->remoteResponse);
		return $body;
	}
		
}