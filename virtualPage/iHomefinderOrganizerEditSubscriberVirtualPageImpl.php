<?php

class iHomefinderOrganizerEditSubscriberVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path = "property-organizer-edit-subscriber";	
	
	public function getTitle() {
		return "Organizer Profile";
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
			->addParameter("requestType", "property-organizer-edit-subscriber")
			->addParameter("phpStyle", true)
		;
		$this->remoteRequest->addParameters($_REQUEST);
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
		$body = $this->remoteRequest->getContent($this->remoteResponse);
		return $body;
	}
}