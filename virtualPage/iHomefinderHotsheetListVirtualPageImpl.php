<?php

class iHomefinderHotsheetListVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path = "homes-for-sale-toppicks";
	
	public function getTitle() {
		return "Saved Search Pages";
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
			->addParameter("requestType", "hotsheet-list")
		;
		$this->remoteRequest->setCacheExpiration(60*60);
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
		$body = $this->remoteRequest->getContent($this->remoteResponse);
		return $body;
	}
}