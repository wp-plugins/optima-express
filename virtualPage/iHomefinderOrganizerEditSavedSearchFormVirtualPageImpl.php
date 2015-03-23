<?php

class iHomefinderOrganizerEditSavedSearchFormVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path = "email-alerts";
	private $title = "Email Alerts";
	
	public function __construct() {
		
	}
	
	public function getTitle() {
		$customTitle = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_EMAIL_UPDATES);
		if($customTitle != null && "" != $customTitle) {
			$this->title=$customTitle;
		}
		
		return $this->title;
	}

	public function getPageTemplate() {
		$pageTemplate = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_EMAIL_UPDATES);
		return $pageTemplate;			
	}
	
	public function getPath() {
		$customPath = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_EMAIL_UPDATES);	
		if($customPath != null && "" != $customPath) {
			$this->path = $customPath;
		}
		return $this->path;
	}
	
		
	public function getContent() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderOrganizerViewSavedSearchListVirtualPageImpl');
					
		$requestData = 'method=handleRequest&viewType=json&requestType=property-organizer-edit-saved-search-form';
		
		$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);
		$boardId=iHomefinderUtility::getInstance()->getQueryVar('boardId');
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "boardId", $boardId);
		
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "phpStyle", "true");
		
		$searchQueryArray=iHomefinderStateManager::getInstance()->getLastSearchQueryArray();
		
		if(count($searchQueryArray) > 0) {
			$cityID = trim(iHomefinderUtility::getInstance()->getVarFromArray("cityID", $searchQueryArray));
			$zip = trim(iHomefinderUtility::getInstance()->getVarFromArray("zip", $searchQueryArray));
			$bedrooms = trim(iHomefinderUtility::getInstance()->getVarFromArray("bedrooms", $searchQueryArray));
			$bathCount = trim(iHomefinderUtility::getInstance()->getVarFromArray("bathCount", $searchQueryArray));
			$minListPrice = trim(iHomefinderUtility::getInstance()->getVarFromArray("minListPrice", $searchQueryArray));
			$maxListPrice = trim(iHomefinderUtility::getInstance()->getVarFromArray("maxListPrice", $searchQueryArray));
			$squareFeet = trim($searchQueryArray["squareFeet"]);
			
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "cityID", $cityID);
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "zip", $zip);
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "bedrooms", $bedrooms);
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "bathcount", $bathCount);
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "minListPrice", $minListPrice);
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "maxListPrice", $maxListPrice);
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "squareFeet", $squareFeet);
		}
		
		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
		
		iHomefinderLogger::getInstance()->debug($requestData);
		iHomefinderLogger::getInstance()->debug('End iHomefinderOrganizerEditSavedSearchFormVirtualPageImpl');
		
		return $body;
	}		
}