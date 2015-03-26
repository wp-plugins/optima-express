<?php

class iHomefinderSupplementalListingVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path="supplemental-listing";
	private $title="Supplemental Listings";

	public function __construct() {
	}
	
	public function getTitle() {
		$customTitle = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_SUPPLEMENTAL_LISTING);
		if($customTitle != null && "" != $customTitle) {
			$this->title=$customTitle;
		}			
		return $this->title;
	}

	public function getPageTemplate() {
		$pageTemplate = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_SUPPLEMENTAL_LISTING);
		return $pageTemplate;			
	}
	
	public function getPath() {
		$customPath = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SUPPLEMENTAL_LISTING);	
		if($customPath != null && "" != $customPath) {
			$this->path = $customPath;
		}
		return $this->path;
	}
	
			
	public function getContent() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderSupplementalListingPageImpl');
		iHomefinderStateManager::getInstance()->saveLastSearch();
		//used to remember search results
		$requestData = 'method=handleRequest'
			. '&viewType=json'
			. '&requestType=supplemental-listing'
			. '&phpStyle=true'
			. '&includeSearchSummary=true';


		$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);
		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
		
		iHomefinderLogger::getInstance()->debug('End iHomefinderSupplementalListingPageImpl');
		iHomefinderLogger::getInstance()->debug($requestData);
		return $body;
	}
}