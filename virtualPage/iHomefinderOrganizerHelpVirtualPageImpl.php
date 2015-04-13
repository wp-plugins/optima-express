<?php

class iHomefinderOrganizerHelpVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path = "property-organizer-help";
	
	public function getTitle() {
		return "Organizer Help";
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
			->addParameter("requestType", "property-organizer-help")
			->addParameter("phpStyle", true)
		;
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
		$body = $this->remoteRequest->getContent($this->remoteResponse);
		return $body;
	}
	
}