<?php

class iHomefinderMapSearchVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function getTitle() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_MAP_SEARCH, "Map Search");
	}

	public function getPageTemplate() {
		return get_option(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_MAP_SEARCH, null);
	}
	
	public function getPermalink() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_MAP_SEARCH, "homes-for-sale-map-search");
	}
			
	public function getContent() {
		iHomefinderStateManager::getInstance()->saveLastSearch();
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "map-search-widget")
			->addParameter("height", 500)
		;
		if(!iHomefinderLayoutManager::getInstance()->supportsMapSearchWithMultipleWidths()) {
			$this->remoteRequest->addParameter("width", 595);
		}
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}
	
}