<?php

class iHomefinderOrganizerDeleteSavedSearchVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path = "property-organizer-delete-saved-search-submit";
	
	public function __construct() {

	}

	public function getTitle() {
		return "Delete Saved Search";
	}

	public function getPageTemplate() {

	}

	public function getPath() {
		return $this->path;
	}

	public function getContent() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderOrganizerDeleteSavedSearchVirtualPageImpl');
		
		$searchProfileId=iHomefinderUtility::getInstance()->getQueryVar('searchProfileID');
		if(empty($searchProfileId)) {
			$searchProfileId=iHomefinderUtility::getInstance()->getQueryVar('searchProfileId');
		}

		$requestData = 'method=handleRequest&viewType=json&requestType=property-organizer-delete-saved-search-submit';
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "searchProfileId", $searchProfileId);

		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		//$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
		iHomefinderLogger::getInstance()->debug($requestData);
		iHomefinderLogger::getInstance()->debug('End iHomefinderOrganizerDeleteSavedSearchVirtualPageImpl');

		$redirectUrl=iHomefinderUrlFactory::getInstance()->getOrganizerViewSavedSearchListUrl(true);
		//redirect to the list of saved searches to avoid double posting the request
		$body = '<meta http-equiv="refresh" content="0;url=' . $redirectUrl . '">';

		return $body;
	}
}