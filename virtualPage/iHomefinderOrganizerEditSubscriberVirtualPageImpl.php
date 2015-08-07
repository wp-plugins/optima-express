<?php

class iHomefinderOrganizerEditSubscriberVirtualPageImpl extends iHomefinderAbstractPropertyOrganizerVirtualPage {
	
	public function getTitle() {
		return "Organizer Profile";
	}
	
	public function getPermalink() {
		return "property-organizer-edit-subscriber";
	}
	
	public function getContent() {
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "property-organizer-edit-subscriber")
			->addParameter("phpStyle", true)
		;
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}
	
}