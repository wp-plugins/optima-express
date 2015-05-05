<?php

class iHomefinderOrganizerEditSavedSearchFormVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path = "email-alerts";
	private $title = "Email Alerts";
	
	public function getTitle() {
		$customTitle = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_EMAIL_UPDATES);
		if($customTitle != null && "" != $customTitle) {
			$this->title=$customTitle;
		}
		
		return $this->title;
	}

	public function getPageTemplate() {
		$pageTemplate = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_EMAIL_UPDATES);
		return $pageTemplate;			
	}
	
	public function getPath() {
		$customPath = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_EMAIL_UPDATES);	
		if($customPath != null && "" != $customPath) {
			$this->path = $customPath;
		}
		return $this->path;
	}
	
		
	public function getContent() {
		$boardId = iHomefinderUtility::getInstance()->getQueryVar("boardId");
		$this->remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "property-organizer-edit-saved-search-form")
			->addParameter("phpStyle", true)
			->addParameter("boardId", $boardId)
		;
		$this->remoteRequest->addParameters($_REQUEST);
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
		$body = $this->remoteRequest->getContent($this->remoteResponse);
		return $body;
	}
			
}