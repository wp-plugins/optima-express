<?php

class iHomefinderOrganizerEditSavedSearchVirtualPageImpl extends iHomefinderAbstractPropertyOrganizerVirtualPage {
	
	public function getTitle() {
		return "Saved Search List";
	}
	
	public function getPermalink() {
		return "property-organizer-edit-saved-search-submit";
	}
	
	public function getContent() {
		//searchProfileName is used only in fixed width
		$searchProfileName = iHomefinderUtility::getInstance()->getQueryVar("searchProfileName");
		if(!empty($searchProfileName)) {
			$this->remoteRequest->addParameter("name", $searchProfileName);
		}
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "property-organizer-edit-saved-search-submit")
		;
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}
	
	public function getBody() {
		$body = $this->remoteResponse->getBody();
		if(!iHomefinderLayoutManager::getInstance()->isResponsive()) {
			if(iHomefinderStateManager::getInstance()->hasSubscriberId()) {
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