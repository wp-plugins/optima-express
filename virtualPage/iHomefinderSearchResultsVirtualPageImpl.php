<?php

class iHomefinderSearchResultsVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function getTitle() {
		return "Property Search Results";
	}
	
	public function getPermalink() {
		return "homes-for-sale-results";
	}
			
	public function getContent() {
		$stateManager = iHomefinderStateManager::getInstance();
		$stateManager->setLastSearchUrl();
		//use a different requestType depending on the search
		$requestType = null;
		if($stateManager->isListingIdResults()) {
			$requestType = "results-by-listing-id";
		} elseif($stateManager->isListingAddressResults()) {
			$requestType = "results-by-address";
		} else {
			$requestType = "listing-search-results";
		}
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", $requestType)
			->addParameter("includeSearchSummary", true)
		;
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}
	
}