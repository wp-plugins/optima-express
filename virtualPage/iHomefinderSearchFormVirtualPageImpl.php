<?php

class iHomefinderSearchFormVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path="homes-for-sale-search";
	private $title="Property Search";

	public function __construct() {
		
	}
	public function getTitle() {
		$customTitle = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_SEARCH);
		if($customTitle != null && "" != $customTitle) {
			$this->title=$customTitle;
		}
		
		return $this->title;
	}

	public function getPageTemplate() {
		$pageTemplate = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_SEARCH);
		return $pageTemplate;			
	}
	
	public function getPath() {
		$customPath = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SEARCH);	
		if($customPath != null && "" != $customPath) {
			$this->path = $customPath;
		}
		return $this->path;
	}
	
			
	public function getContent() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderFilter.filterSearchForm');
		$requestData = 'method=handleRequest'
			. '&viewType=json'
			. '&requestType=listing-search-form'
			. '&includeAreaSelectorAreas=false'
			. '&phpStyle=true';


		$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);
		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
		
		iHomefinderLogger::getInstance()->debug('End iHomefinderFilter.filterSearchForm');
		iHomefinderLogger::getInstance()->debug($requestData);
		return $body;
	}
}