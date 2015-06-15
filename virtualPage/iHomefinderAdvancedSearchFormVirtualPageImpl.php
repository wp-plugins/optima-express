<?php

class iHomefinderAdvancedSearchFormVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function getTitle() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_ADVANCED_SEARCH, "Advanced Property Search");
	}

	public function getPageTemplate() {
		return get_option(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_ADVANCED_SEARCH, null);		
	}
	
	public function getPermalink() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_ADVANCED_SEARCH, "homes-for-sale-search-advanced");
	}
	
	public function getContent() {
		$boardId = iHomefinderUtility::getInstance()->getQueryVar("boardId");
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "listing-advanced-search-form")
			->addParameter("includeAreaSelectorAreas", false)
			->addParameter("phpStyle", true)
		;
		if(is_numeric($boardId)) {
			$this->remoteRequest->addParameter("boardId", $boardId);
		}
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}
	
}