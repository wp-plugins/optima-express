<?php

class iHomefinderOrganizerEditSavedSearchFormVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function getTitle() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_EMAIL_UPDATES, "Email Alerts");
	}

	public function getPageTemplate() {
		$pageTemplate = get_option(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_EMAIL_UPDATES, null);
		return $pageTemplate;			
	}
	
	public function getPermalink() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_EMAIL_UPDATES, "email-alerts");
	}
		
	public function getContent() {
		$utility = iHomefinderUtility::getInstance();
		$boardId = $utility->getQueryVar("boardId");
		$lastSearch = iHomefinderStateManager::getInstance()->getLastSearch();
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameters($lastSearch)
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "property-organizer-edit-saved-search-form")
			->addParameter("phpStyle", true)
			->addParameter("boardId", $boardId)
		;
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}
			
}