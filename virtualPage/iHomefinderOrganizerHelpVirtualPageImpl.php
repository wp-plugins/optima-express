<?php

class iHomefinderOrganizerHelpVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function getTitle() {
		return "Organizer Help";
	}
	
	public function getPermalink() {
		return "property-organizer-help";
	}
	
	public function getContent() {
		$this->remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "property-organizer-help")
			->addParameter("phpStyle", true)
		;
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}
	
}