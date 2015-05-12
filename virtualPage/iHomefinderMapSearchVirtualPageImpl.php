<?php

class iHomefinderMapSearchVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	
	private $path = "homes-for-sale-map-search";
	private $title = "Map Search";
	
	public function getTitle() {
		$customTitle = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_MAP_SEARCH);
		if($customTitle != null && "" != $customTitle) {
			$this->title=$customTitle;
		}
		return $this->title;
	}

	public function getPageTemplate() {
		$pageTemplate = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_MAP_SEARCH);
		return $pageTemplate;			
	}
	
	public function getPath() {
		$customPath = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_MAP_SEARCH);	
		if($customPath != null && "" != $customPath) {
			$this->path = $customPath;
		}
		return $this->path;
	}
	
			
	public function getContent() {
		iHomefinderStateManager::getInstance()->saveLastSearch();
		$this->remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "map-search-widget")
			->addParameter("height", 500)
		;
		if(!iHomefinderLayoutManager::getInstance()->supportsMapSearchWithMultipleWidths()) {
			$this->remoteRequest->addParameter("width", 595);
		}
		$this->remoteRequest->addParameters($_REQUEST);
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
		$body = $this->remoteRequest->getContent($this->remoteResponse);
		return $body;
	}
}