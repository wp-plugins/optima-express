<?php

class iHomefinderOrganizerDeleteSavedSearchVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path = "property-organizer-delete-saved-search-submit";

	public function getTitle() {
		return "Saved Search List";
	}

	public function getPageTemplate() {

	}

	public function getPath() {
		return $this->path;
	}

	public function getContent() {
		$searchProfileId = iHomefinderUtility::getInstance()->getQueryVar("searchProfileID");
		if(empty($searchProfileId)) {
			$searchProfileId = iHomefinderUtility::getInstance()->getQueryVar("searchProfileId");
		}
		$this->remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "property-organizer-delete-saved-search-submit")
			->addParameter("searchProfileId", $searchProfileId)
		;
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
		$body = $this->remoteRequest->getContent($this->remoteResponse);
		return $body;
	}
}