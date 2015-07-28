<?php

class iHomefinderOrganizerEditSavedSearchVirtualPageImpl extends iHomefinderAbstractPropertyOrganizerVirtualPage {
	
	public function getTitle() {
		return "Saved Search List";
	}
	
	public function getPermalink() {
		return "property-organizer-edit-saved-search-submit";
	}
	
	public function getContent() {
		$searchProfileName = iHomefinderUtility::getInstance()->getQueryVar("searchProfileName");
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "property-organizer-edit-saved-search-submit")
			->addParameter("name", $searchProfileName)
		;
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}
	
	public function getBody() {
		$body = $this->remoteResponse->getBody();
		if(!iHomefinderLayoutManager::getInstance()->isResponsive()) {
			if(iHomefinderStateManager::getInstance()->isLoggedIn()) {
				$redirectUrl=iHomefinderUrlFactory::getInstance()->getOrganizerViewSavedSearchListUrl(true);
				//redirect to the list of saved searches to avoid double posting the request
				$body = "<meta http-equiv=\"refresh\" content=\"0;url=" . $redirectUrl . "\">";
			} else {
				$redirectUrl=iHomefinderUrlFactory::getInstance()->getOrganizerEmailUpdatesConfirmationUrl(true);
				//redirect to the list of saved searches to avoid double posting the request
				$body = "<meta http-equiv=\"refresh\" content=\"0;url=" . $redirectUrl . "\">";
			}
		}
		return $body;
	}
	
}