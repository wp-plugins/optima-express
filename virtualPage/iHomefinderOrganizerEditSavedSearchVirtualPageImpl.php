<?php

class iHomefinderOrganizerEditSavedSearchVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path = "property-organizer-edit-saved-search-submit";
	
	public function getTitle() {
		return "Saved Search List";
	}		
			
	public function getPageTemplate() {
		
	}
	
	public function getPath() {
		return $this->path;
	}
	
	public function getContent() {		
		$searchProfileName = iHomefinderUtility::getInstance()->getQueryVar("searchProfileName");
		$this->remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "property-organizer-edit-saved-search-submit")
			->addParameter("name", $searchProfileName)
		;
		$this->remoteRequest->addParameters($_REQUEST);
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
		$body = $this->remoteRequest->getContent($this->remoteResponse);
		$subscriberSessionOnJavaServers = iHomefinderLayoutManager::getInstance()->isSubscriberSessionOnJavaServers();
		if (!$subscriberSessionOnJavaServers) {
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