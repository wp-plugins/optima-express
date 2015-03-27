<?php

class iHomefinderOrganizerLoginSubmitVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path="property-organizer-login-submit";
	
	public function __construct() {

	}
	public function getTitle() {
		return "Organizer Login";
	}
				
	public function getPageTemplate() {
		
	}
	
	public function getPath() {
		return $this->path;	
	}
	
	public function getContent() {
		iHomefinderLogger::getInstance()->debug('Begin PropertyOrganizerLoginSubmitVirtualPage');
		
		$subscriberId=iHomefinderUtility::getInstance()->getQueryVar('subscriberID');
		//if rememberMe parameter is set
		//create a cookie 'rmuser' with leadcaptureid
		if(isset($_REQUEST["rememberMe"]) && trim($_REQUEST["rememberMe"]) == '1') {
			iHomefinderStateManager::getInstance()->createRememberMeCookie();
		}
		

		$requestData = 'method=handleRequest&viewType=json&requestType=property-organizer-login-submit';

		if($subscriberId == null || trim($subscriberId) == "") {
			//If no subscriber id, then get the authentication info from the request and pass it along
			$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);
		} else {
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "subscriberId", $subscriberId);
		}

		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
		
		$isLoggedIn = iHomefinderStateManager::getInstance()->isLoggedIn();
		if($isLoggedIn && $body == "") {
			$redirectUrl=iHomefinderUrlFactory::getInstance()->getOrganizerViewSavedListingListUrl();
			//$body = '<meta http-equiv="refresh" content="0;url=' . $redirectUrl . '">';
		}

		iHomefinderLogger::getInstance()->debug($requestData);
		iHomefinderLogger::getInstance()->debug('End PropertyOrganizerLoginSubmitVirtualPage');

		return $body;
	}
}