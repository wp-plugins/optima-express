<?php

class iHomefinderHotsheetListVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path = "homes-for-sale-toppicks";
	
	public function __construct() {
		
	}
	public function getTitle() {
		return "Saved Search Pages";
	}
			
	public function getPageTemplate() {
		
	}
	
	public function getPath() {
		return  $this->path;
	}
			
	public function getContent() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderHotsheetListVirtualPageImpl');
		$requestData = 'method=handleRequest'
			. '&viewType=json'
			. '&requestType=hotsheet-list';
										
		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
		
		iHomefinderLogger::getInstance()->debug('End iHomefinderHotsheetListVirtualPageImpl');
		iHomefinderLogger::getInstance()->debug($requestData);
		return $body;
	}
}