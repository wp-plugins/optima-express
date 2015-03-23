<?php

class iHomefinderSoldFeaturedListingVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path="sold-featured-listing";
	private $title="Sold Properties";

	public function __construct() {
	}
	
	public function getTitle() {
		$customTitle = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_SOLD_FEATURED);
		if($customTitle != null && "" != $customTitle) {
			$this->title=$customTitle;
		}			
		return $this->title;
	}

	public function getPageTemplate() {
		$pageTemplate = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_SOLD_FEATURED);
		return $pageTemplate;			
	}
	
	public function getPath() {
		$customPath = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SOLD_FEATURED);	
		if($customPath != null && "" != $customPath) {
			$this->path = $customPath;
		}
		return $this->path;
	}
	
			
	public function getContent() {
		iHomefinderStateManager::getInstance()->saveLastSearch();
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderSoldFeaturedPageImpl');
		$requestData = 'method=handleRequest'
			. '&viewType=json'
			. '&requestType=sold-featured-listing'
			. '&phpStyle=true'
			. '&includeSearchSummary=true';
		
		$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);
		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
		
		iHomefinderLogger::getInstance()->debug('End iHomefinderSoldFeaturedPageImpl');
		iHomefinderLogger::getInstance()->debug($requestData);
		return $body;
	}
}