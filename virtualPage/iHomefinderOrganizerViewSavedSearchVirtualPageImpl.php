<?php

class iHomefinderOrganizerViewSavedSearchVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function getTitle() {
		return "Saved Search";
	}
	
	public function getPermalink() {
		return "property-organizer-view-saved-search";
	}
	
	public function getContent() {
		iHomefinderStateManager::getInstance()->saveLastSearch();
		$searchProfileId = iHomefinderUtility::getInstance()->getQueryVar("searchProfileId");
		$this->remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "property-organizer-view-saved-search")
			->addParameter("searchProfileId", $searchProfileId)
			->addParameters($_REQUEST)
		;
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}
	
}