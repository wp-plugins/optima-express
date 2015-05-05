<?php

class iHomefinderOrganizerActivateSubscriberVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path = "property-organizer-activate";
	
	public function getTitle() {
		return "Subscriber Activation";
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
			->addParameter("requestType", "property-organizer-activate-subscriber")
		;
		$this->remoteRequest->addParameters($_REQUEST);
		
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
		$body = $this->remoteRequest->getContent($this->remoteResponse);
		return $body;
	}
}