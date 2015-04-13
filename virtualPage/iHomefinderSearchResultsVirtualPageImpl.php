<?php

class iHomefinderSearchResultsVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	//default path used for URL Rewriting
	private $path="homes-for-sale-results";
	
	public function getTitle() {
		return "Property Search Results";
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
			->addParameter("requestType", "listing-search-results")
			->addParameter("includeSearchSummary", true)
		;
		$this->remoteRequest->addParameters($_REQUEST);
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
		$body = $this->remoteRequest->getContent($this->remoteResponse);	
		return $body;
	}		
}