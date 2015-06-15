<?php

class iHomefinderOfficeListVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function getTitle() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_OFFICE_LIST, "Office List");
	}

	public function getPageTemplate() {
		return get_option(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_OFFICE_LIST, null);		
	}
	
	public function getPermalink() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_OFFICE_LIST, "office-list");
	}
	
	public function getContent() {
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "office-list")
			->addParameter("phpStyle", true)
			->addParameter("includeSearchSummary", true)
		;
		$this->remoteRequest->setCacheExpiration(60*60);
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}
	
}