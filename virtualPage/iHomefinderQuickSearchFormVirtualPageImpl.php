<?php

class iHomefinderQuickSearchFormVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path="";
	
	public function getTitle() {
		return "";
	}			
	
	public function getPageTemplate() {
		
	}
	
	public function getPath() {
		return $this->path;
	}
			
	public function getContent() {
		$this->remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "listing-quick-search-form")
			->addParameter("phpStyle", true)
			->addParameter("includeJQuery", false)
			->addParameter("includeJQueryUI", false)
		;
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
		$body = $this->remoteRequest->getContent($this->remoteResponse);
		return $body;
	}
}