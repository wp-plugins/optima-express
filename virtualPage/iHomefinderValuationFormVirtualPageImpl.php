<?php

class iHomefinderValuationFormVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path="valuation-form";
	private $title="Valuation Request Form";

	public function __construct() {
	}
	
	public function getTitle() {
		$customTitle = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_VALUATION_FORM);
		if($customTitle != null && "" != $customTitle) {
			$this->title=$customTitle;
		}			
		return $this->title;
	}

	public function getPageTemplate() {
		$pageTemplate = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_VALUATION_FORM);
		return $pageTemplate;			
	}
	
	public function getPath() {
		$customPath = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_VALUATION_FORM);	
		if($customPath != null && "" != $customPath) {
			$this->path = $customPath;
		}
		return $this->path;
	}
	
			
	public function getContent() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderContactFormVirtualPageImpl');
		$requestData = 'method=handleRequest'
			. '&viewType=json'
			. '&requestType=valuation-form'
			. '&phpStyle=true';


		$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);
		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
		
		iHomefinderLogger::getInstance()->debug('End iHomefinderContactFormVirtualPageImpl');
		iHomefinderLogger::getInstance()->debug($requestData);
		return $body;
	}
}