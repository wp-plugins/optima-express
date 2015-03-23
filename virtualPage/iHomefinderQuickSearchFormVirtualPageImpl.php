<?php

class iHomefinderQuickSearchFormVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path="";
	
	public function __construct() {
		
	}
	public function getTitle() {
		return "";
	}			
	
	public function getPageTemplate() {
		
	}
	
	public function getPath() {
		return $this->path;
	}
			
	public function getContent() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderQuickSearchFormFilterImpl.filter');
		$requestData = 'method=handleRequest'
			. '&viewType=json'
			. '&requestType=listing-quick-search-form'
			. '&phpStyle=true'
			. '&includeJQuery=false'
			. '&includeJQueryUI=false';
		
		iHomefinderLogger::getInstance()->debug('requestData: ' . $requestData);	

		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
		
		iHomefinderLogger::getInstance()->debug('End iHomefinderQuickSearchFormFilterImpl.filter');
		iHomefinderLogger::getInstance()->debug($requestData);
		return $body;
	}
}