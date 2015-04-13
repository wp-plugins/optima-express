<?php

class iHomefinderBasicVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path = null;
	private $title = null;
	private $titleOption = null;
	private $templateOption = null;
	private $permalinkOption = null;
	
	//Value send to iHomefinder server to determine how to handle the request.
	private $requestType=null;
	

	public function __construct($requestType, $titleOption, $defaultTitle, $permalinkOption, $defaultPath, $templateOption) {
		$this->requestType=$requestType;
		
		$this->titleOption=$titleOption;
		$this->title=$defaultTitle;
		
		$this->permalinkOption=$permalinkOption;
		$this->path=$defaultPath;
		$this->templateOption=$templateOption;
	}
	
	public function getTitle() {
		$customTitle = get_option($this->titleOption);
		if($customTitle != null && "" != $customTitle) {
			$this->title=$customTitle;
		}			
		return $this->title;
	}

	public function getPageTemplate() {
		$pageTemplate = get_option($this->templateOption);
		return $pageTemplate;			
	}
	
	public function getPath() {
		$customPath = get_option($this->permalinkOption);	
		if($customPath != null && "" != $customPath) {
			$this->path = $customPath;
		}
		return $this->path;
	}
	
			
	public function getContent() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderBasicPageImpl');

		//used to remember search results
		$requestData = 'method=handleRequest'
			. '&viewType=json'
			. '&requestType=' . $this->requestType
			. '&phpStyle=true'
			. '&includeSearchSummary=true';


		$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);
		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
		
		iHomefinderLogger::getInstance()->debug('End iHomefinderBasicPageImpl');
		iHomefinderLogger::getInstance()->debug($requestData);
		return $body;
	}
}