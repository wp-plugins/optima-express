<?php

class iHomefinderOfficeDetailVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path = "office-detail";
	private $title = "";
	private $defaultTitle = "";
	
	public function getTitle() {
		$customTitle = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_OFFICE_DETAIL);
		if($customTitle != null && "" != $customTitle) {
			$this->title=$customTitle;
		} else {
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
		$this->remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "office-detail")
			->addParameter("phpStyle", true)
		;
		$officeId = iHomefinderUtility::getInstance()->getQueryVar("officeID");
		if(is_numeric($officeId)) {
			$this->remoteRequest->addParameter("officeID", $officeId);
		}
		$this->remoteRequest->addParameters($_REQUEST);
		$this->remoteRequest->setCacheExpiration(60*60);
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
		$body = $this->remoteRequest->getContent($this->remoteResponse);
		if(property_exists($this->remoteResponse, "title")) {
			//success, display the view
			$this->defaultTitle = $this->remoteResponse->title;
		}
		return $body;
	}
	
}