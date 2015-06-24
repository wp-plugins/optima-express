<?php

class iHomefinderOrganizerActivateSubscriberVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function getTitle() {
		return "Subscriber Activation";
	}
	
	public function getPermalink() {
		return "property-organizer-activate";	
	}
	
	public function getContent() {
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "property-organizer-activate-subscriber")
		;
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}
	
}