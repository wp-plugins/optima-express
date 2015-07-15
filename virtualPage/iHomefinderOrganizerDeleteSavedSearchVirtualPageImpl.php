<?php

class iHomefinderOrganizerDeleteSavedSearchVirtualPageImpl extends iHomefinderAbstractPropertyOrganizerVirtualPage {
	
	public function getTitle() {
		return "Saved Search List";
	}
	
	public function getPermalink() {
		return "property-organizer-delete-saved-search-submit";
	}

	public function getContent() {
		$searchProfileId = iHomefinderUtility::getInstance()->getQueryVar("searchProfileId");
		if(empty($searchProfileId)) {
			$searchProfileId = iHomefinderUtility::getInstance()->getRequestVar("searchProfileId");
		}
		$this->remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "property-organizer-delete-saved-search-submit")
			->addParameter("searchProfileId", $searchProfileId)
		;
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}
	
}