<?php

class iHomefinderAdvancedSearchFormVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path = "homes-for-sale-search-advanced";
	private $title = "Advanced Property Search";
	
	public function __construct() {
		
	}
		public function getTitle() {
		$customTitle = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_ADV_SEARCH);
		if($customTitle != null && "" != $customTitle) {
			$this->title=$customTitle;
		}
		
		return $this->title;
	}

	public function getPageTemplate() {
		$pageTemplate = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_ADV_SEARCH);
		return $pageTemplate;			
	}
	
	public function getPath() {
		$customPath = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_ADV_SEARCH);	
		if($customPath != null && "" != $customPath) {
			$this->path = $customPath;
		}
		return $this->path;
	}
	
	public function getContent() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderAdvancedSearchFormVirtualPageImpl');
		$boardId=iHomefinderUtility::getInstance()->getQueryVar('bid');
		$requestData = 'method=handleRequest'
			. '&viewType=json'
			. '&requestType=listing-advanced-search-form'
			. '&includeAreaSelectorAreas=false'
			. '&phpStyle=true';
			
		$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);	
	
		if(is_numeric($boardId)) {
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "boardId", $boardId);		
		}

		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
		
		iHomefinderLogger::getInstance()->debug('End iHomefinderAdvancedSearchFormVirtualPageImpl');
		iHomefinderLogger::getInstance()->debug($requestData);
		return $body;
	}
}