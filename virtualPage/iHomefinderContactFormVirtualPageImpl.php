<?php

class iHomefinderContactFormVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path = "contact-form";
	private $title = "Contact";

	public function __construct() {
		
	}
	public function getTitle() {
		$customTitle = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_CONTACT_FORM);
		if($customTitle != null && "" != $customTitle) {
			$this->title=$customTitle;
		}
		
		return $this->title;
	}

	public function getPageTemplate() {
		$pageTemplate = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_CONTACT_FORM);
		return $pageTemplate;			
	}
	
	public function getPath() {
		$customPath = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_CONTACT_FORM);	
		if($customPath != null && "" != $customPath) {
			$this->path = $customPath;
		}
		return $this->path;
	}
	
			
	public function getContent() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderContactFormVirtualPageImpl');
		$requestData = 'method=handleRequest'
			. '&viewType=json'
			. '&smallView=false'
			. '&requestType=FeatureContactForm'
			. '&phpStyle=true';


		$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);
		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
		
		iHomefinderLogger::getInstance()->debug('End iHomefinderContactFormVirtualPageImpl');
		iHomefinderLogger::getInstance()->debug($requestData);
		return $body;
	}
}