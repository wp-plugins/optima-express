<?php

class iHomefinderSearchByListingIdResultsVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	//default path used for URL Rewriting
	private $path="id-listing-results";

	public function __construct() {
		
	}
	
	public function getTitle() {
		return "Search By Listing ID Results";
	}	
		
	function getPageTemplate() {
		
	}
	
	public function getPath() {
		return $this->path;
	}
			
	public function getContent() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderFilter.filterSearchByListingIdResults');
		iHomefinderStateManager::getInstance()->saveLastSearch();
		
		$requestData = 'method=handleRequest&viewType=json&requestType=results-by-listing-id';
		//used to remember search results
		//$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "includeSearchSummary", "true");	
		$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);
		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
		
		iHomefinderLogger::getInstance()->debug($requestData);
		iHomefinderLogger::getInstance()->debug('End iHomefinderFilter.filterSearchByListingIdResults');
					
		return $body;
	}		
}