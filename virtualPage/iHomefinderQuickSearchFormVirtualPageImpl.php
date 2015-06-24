<?php

class iHomefinderQuickSearchFormVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function getContent() {
		$this->remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "listing-quick-search-form")
			->addParameter("phpStyle", true)
			->addParameter("includeJQuery", false)
			->addParameter("includeJQueryUI", false)
		;
		$this->remoteRequest->setCacheExpiration(60*60*24);
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}
	
}