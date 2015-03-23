<?php

class iHomefinderMapSearchVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	
	private $path = "homes-for-sale-map-search";
	private $title = "Map Search";

	public function __construct() {
		
	}
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
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderMapSearchVirtualPageImpl->getContent');
		//save url to get back from listing details page
		iHomefinderStateManager::getInstance()->saveLastSearch();
		$requestData = 'method=handleRequest&viewType=json'
			. '&requestType=map-search-widget'
			. '&height=500';
		if(!iHomefinderLayoutManager::getInstance()->supportsMapSearchWithMultipleWidths()) {
			$requestData = $requestData.'&width=595';
		}
		// adds restart= true if available to start over map session
		$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);
		
		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
		
		iHomefinderLogger::getInstance()->debug('End iHomefinderMapSearchVirtualPageImpl->getContent');
		iHomefinderLogger::getInstance()->debug($requestData);
		return $body;
	}
}