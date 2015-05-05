<?php

class iHomefinderOrganizerViewSavedSearchVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path="property-organizer-view-saved-search";
	
	public function getTitle() {
		return "Saved Search";
	}
	
	public function getPageTemplate() {
		
	}
	
	public function getPath() {
		return $this->path;
	}
	
	public function getContent() {
		iHomefinderStateManager::getInstance()->saveLastSearch();
		$searchProfileId = iHomefinderUtility::getInstance()->getQueryVar("searchProfileID");
		$this->remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "property-organizer-view-saved-search")
			->addParameter("searchProfileId", $searchProfileId)
			->addParameters($_REQUEST)
		;
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
		$body = $this->remoteRequest->getContent($this->remoteResponse);
		return $body;
	}
	
}