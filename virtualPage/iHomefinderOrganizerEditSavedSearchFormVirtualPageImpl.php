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
		$boardId = iHomefinderUtility::getInstance()->getQueryVar("boardId");
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "property-organizer-edit-saved-search-form")
			->addParameter("phpStyle", true)
			->addParameter("boardId", $boardId)
		;
		$lastSearchQuery = iHomefinderStateManager::getInstance()->getLastSearchQuery();
		if(count($lastSearchQuery) > 0) {
			$cityID = trim(iHomefinderUtility::getInstance()->getVarFromArray("cityID", $lastSearchQuery));
			$zip = trim(iHomefinderUtility::getInstance()->getVarFromArray("zip", $lastSearchQuery));
			$bedrooms = trim(iHomefinderUtility::getInstance()->getVarFromArray("bedrooms", $lastSearchQuery));
			$bathCount = trim(iHomefinderUtility::getInstance()->getVarFromArray("bathCount", $lastSearchQuery));
			$minListPrice = trim(iHomefinderUtility::getInstance()->getVarFromArray("minListPrice", $lastSearchQuery));
			$maxListPrice = trim(iHomefinderUtility::getInstance()->getVarFromArray("maxListPrice", $lastSearchQuery));
			$squareFeet = trim(iHomefinderUtility::getInstance()->getVarFromArray("squareFeet", $lastSearchQuery));
			$this->remoteRequest
				->addParameter("cityID", $cityID)
				->addParameter("zip", $zip)
				->addParameter("bedrooms", $bedrooms)
				->addParameter("bathcount", $bathCount)
				->addParameter("minListPrice", $minListPrice)
				->addParameter("maxListPrice", $maxListPrice)
				->addParameter("squareFeet", $squareFeet)
			;
		}
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}
			
}