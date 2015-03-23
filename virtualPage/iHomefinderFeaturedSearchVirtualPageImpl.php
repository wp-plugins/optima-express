<?php

class iHomefinderFeaturedSearchVirtualPageImpl extends iHomefinderAbstractVirtualPage{
	
	private $path = "homes-for-sale-featured";
	private $title = "Featured Properties";
	
	public function __construct() {
		
	}
		
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
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderFeaturedSearchVirtualPageImpl');
		iHomefinderStateManager::getInstance()->saveLastSearch();
								
		if(!is_numeric($startRowNumber)) {
			$startRowNumber=1;
		}
		
		$requestData = 'method=handleRequest&viewType=json&requestType=featured-search';
		$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);
		
		//used to remember search results
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "includeSearchSummary", "true");				
		
		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);			
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);

		iHomefinderLogger::getInstance()->debug($requestData);
		iHomefinderLogger::getInstance()->debug('End iHomefinderFeaturedSearchVirtualPageImpl');
		return $body;
	}
}