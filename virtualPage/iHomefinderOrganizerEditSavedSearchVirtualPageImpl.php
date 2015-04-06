<?php

class iHomefinderOrganizerEditSavedSearchVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path = "property-organizer-edit-saved-search-submit";
	
	public function __construct() {
		
	}
	public function getTitle() {
		return "Saved Search List";
	}		
			
	public function getPageTemplate() {
		
	}
	
	public function getPath() {
		return $this->path;
	}
	
	public function getContent() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderOrganizerEditSavedSearchVirtualPageImpl');
					
		$searchProfileName=iHomefinderUtility::getInstance()->getQueryVar('searchProfileName');
		
		$requestData = 'method=handleRequest&viewType=json&requestType=property-organizer-edit-saved-search-submit';
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "name", $searchProfileName);
		$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);
		//$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "phpStyle", "true");
		
		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
		
		$subscriberSessionOnJavaServers = iHomefinderLayoutManager::getInstance()->isSubscriberSessionOnJavaServers();
		if (!$subscriberSessionOnJavaServers) {
			if(iHomefinderStateManager::getInstance()->isLoggedIn()) {
				$redirectUrl=iHomefinderUrlFactory::getInstance()->getOrganizerViewSavedSearchListUrl(true);
				//redirect to the list of saved searches to avoid double posting the request
				$body = '<meta http-equiv="refresh" content="0;url=' . $redirectUrl . '">';
			} else {
				$redirectUrl=iHomefinderUrlFactory::getInstance()->getOrganizerEmailUpdatesConfirmationUrl(true);
				//redirect to the list of saved searches to avoid double posting the request
				$body = '<meta http-equiv="refresh" content="0;url=' . $redirectUrl . '">';
			}
		}
		
		iHomefinderLogger::getInstance()->debug($requestData);
		iHomefinderLogger::getInstance()->debug('End iHomefinderOrganizerEditSavedSearchVirtualPageImpl');
		
		return $body;
	}
}