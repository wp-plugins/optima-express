<?php

class iHomefinderHotsheetVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path = "homes-for-sale-toppicks";
	private $title = "";
	//The default title might get updated in function getContent
	private $defaultTitle = "";
	
	public function __construct() {
		
	}

	public function getTitle() {
		$customTitle = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_HOTSHEET);
		if($customTitle != null && "" != $customTitle) {
			$this->title=$customTitle;
		}
		else{
			$this->title = $this->defaultTitle;
		}

		return $this->title;
	}

	public function getPageTemplate() {
		$pageTemplate = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_HOTSHEET);
		return $pageTemplate;			
	}
	
	public function getPath() {
		$customPath = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_HOTSHEET);	
		if($customPath != null && "" != $customPath) {
			$this->path = $customPath;
		}
		return $this->path;
	}
			
	public function getContent() {
		iHomefinderStateManager::getInstance()->saveLastSearch();
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderHotsheetVirtualPageImpl');
		
		$requestData = 'method=handleRequest'
			. '&viewType=json'
			. '&requestType=hotsheet-results';
			
		$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);
		
		$hotSheetId=iHomefinderUtility::getInstance()->getRequestVar('hotSheetId');
		if(!isset($hotSheetId)) {
			//iHomefinderShortCodeDispatcher sets vars in $_REQUEST
			//URL rewriting can set vars in the URL
			$hotSheetId=iHomefinderUtility::getInstance()->getQueryVar('hotSheetId');
			if(isset($hotSheetId)) {
				$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "hotSheetId", $hotSheetId);
			}
		}
		
		if($this->getTitle() == "") {
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "includeDisplayName", "false");
		}
		//used to remember search results
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "includeSearchSummary", "true");	
		
		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
		
		if(isset($this->remoteResponse) && isset($this->remoteResponse->title)) {
			//success, display the view
			$this->defaultTitle = $this->remoteResponse->title;
		}
		
		iHomefinderLogger::getInstance()->debug('End iHomefinderHotsheetVirtualPageImpl');
		iHomefinderLogger::getInstance()->debug($requestData);
		return $body;
	}
}