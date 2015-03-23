<?php

class iHomefinderOfficeListVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path = "office-list";
	private $title = "Office List";

	public function __construct() {
	}
	
	public function getTitle() {
		$customTitle = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_OFFICE_LIST);
		if($customTitle != null && "" != $customTitle) {
			$this->title=$customTitle;
		}			
		return $this->title;
	}

	public function getPageTemplate() {
		$pageTemplate = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_OFFICE_LIST);
		return $pageTemplate;			
	}
	
	public function getPath() {
		$customPath = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_OFFICE_LIST);	
		if($customPath != null && "" != $customPath) {
			$this->path = $customPath;
		}
		return $this->path;
	}
	
			
	public function getContent() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderOfficeListPageImpl');

		//used to remember search results
		$requestData = 'method=handleRequest'
			. '&viewType=json'
			. '&requestType=office-list'
			. '&phpStyle=true'
			. '&includeSearchSummary=true';
			
		$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);
		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
		
		iHomefinderLogger::getInstance()->debug('End iHomefinderOfficeListPageImpl');
		iHomefinderLogger::getInstance()->debug($requestData);
		return $body;
	}
}