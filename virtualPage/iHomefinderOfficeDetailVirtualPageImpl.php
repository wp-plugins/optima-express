<?php

class iHomefinderOfficeDetailVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path = "office-detail";
	private $title = "";
	private $defaultTitle = "";

	public function __construct() {
	}
	
	public function getTitle() {
		$customTitle = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_OFFICE_DETAIL);
		if($customTitle != null && "" != $customTitle) {
			$this->title=$customTitle;
		}
		else{
			$this->title = $this->defaultTitle;
		}

		return $this->title;
	}

	public function getPageTemplate() {
		$pageTemplate = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_OFFICE_DETAIL);
		return $pageTemplate;			
	}
	
	public function getPath() {
		$customPath = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_OFFICE_DETAIL);	
		if($customPath != null && "" != $customPath) {
			$this->path = $customPath;
		}
		return $this->path;
	}
	
			
	public function getContent() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderOfficeDetailPageImpl');

		$officeID=iHomefinderUtility::getInstance()->getQueryVar('officeID');
		//used to remember search results
		$requestData = 'method=handleRequest'
			. '&viewType=json'
			. '&requestType=office-detail'
			. '&phpStyle=true';

		if(is_numeric($officeID)) {
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "officeID", $officeID);		
		}
			
		$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);
		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
		
		if(property_exists($this->remoteResponse, "title")) {
			//success, display the view
			$this->defaultTitle = $this->remoteResponse->title;
		}					
		
		iHomefinderLogger::getInstance()->debug('End iHomefinderOfficeDetailPageImpl');
		iHomefinderLogger::getInstance()->debug($requestData);
		return $body;
	}
}