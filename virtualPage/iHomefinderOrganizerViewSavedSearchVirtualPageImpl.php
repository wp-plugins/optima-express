<?php

class iHomefinderOrganizerViewSavedSearchVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path="property-organizer-view-saved-search";
	public function __construct() {
			
	}
	public function getTitle() {
		return "Saved Search";
	}

	public function getPageTemplate() {
		
	}
	
	public function getPath() {
		return $this->path;
	}

	
	public function getContent() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderOrganizerViewSavedSearchFilterImpl');

		$searchProfileId=iHomefinderUtility::getInstance()->getQueryVar('searchProfileID');
		$startRowNumber=iHomefinderUtility::getInstance()->getQueryVar('startRowNumber');
		$sortBy=iHomefinderUtility::getInstance()->getQueryVar('sortBy');
		
		iHomefinderStateManager::getInstance()->saveLastSearch();
			
		$requestData = 'method=handleRequest&viewType=json&requestType=property-organizer-view-saved-search';
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "searchProfileId", $searchProfileId);
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "startRowNumber", $startRowNumber);
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "sortBy", $sortBy);

		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
		
		iHomefinderLogger::getInstance()->debug($requestData);
		iHomefinderLogger::getInstance()->debug('End iHomefinderOrganizerViewSavedSearchFilterImpl');
			
		return $body;
	}
}