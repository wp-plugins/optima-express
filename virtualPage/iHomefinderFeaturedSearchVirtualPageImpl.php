<?php

class iHomefinderFeaturedSearchVirtualPageImpl extends iHomefinderAbstractVirtualPage{
	
	private $path = "homes-for-sale-featured";
	private $title = "Featured Properties";
		
	public function getTitle() {
		$customTitle = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_FEATURED);
		if($customTitle != null && "" != $customTitle) {
			$this->title=$customTitle;
		}
		
		return $this->title;
	}

	public function getPageTemplate() {
		$pageTemplate = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_FEATURED);
		return $pageTemplate;			
	}
	
	public function getPath() {
		$customPath = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_FEATURED);	
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
			->addParameter("requestType", "featured-search")
			->addParameter("includeSearchSummary", true)
		;
		$this->remoteRequest->addParameters($_REQUEST);
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();			
		$body = $this->remoteRequest->getContent($this->remoteResponse);
		return $body;
	}
}