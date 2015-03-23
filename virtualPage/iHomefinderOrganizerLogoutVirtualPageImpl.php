<?php

class iHomefinderOrganizerLogoutVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path="property-organizer-logout";
	
	public function __construct() {
		
	}
	public function getTitle() {
		return "Organizer Logout";
	}
		
	public function getPageTemplate() {
		
	}
	
	public function getPath() {
		return $this->path;
	}
	public function getContent() {
		
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderOrganizerLogoutImpl');
		$logOutOfJavaServers = iHomefinderLayoutManager::getInstance()->isSubscriberSessionOnJavaServers();
		/**
		 * For responsive layout we need to kill the session for subscriber 
		 * on lava servers
		 * Where as for legacy layout we need to kill session stored locally on wordpress
		 * servers
		 */
		if($logOutOfJavaServers) {
			$requestData = 'method=handleRequest&viewType=json&requestType=property-organizer-logout';
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "phpStyle", "true");
			//delete rememberme cookie if user logs out
			iHomefinderStateManager::getInstance()->deleteRememberMeCookie();
			
			$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
			$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
			iHomefinderLogger::getInstance()->debug($requestData);
		} else {
			iHomefinderStateManager::getInstance()->deleteSubscriberLogin();
			iHomefinderLogger::getInstance()->debug('End iHomefinderOrganizerLogoutFilterImpl');		
			$redirectUrl=iHomefinderUrlFactory::getInstance()->getListingsSearchFormUrl(true); 
			//redirect to the search page
			$body = '<meta http-equiv="refresh" content="0;url=' . $redirectUrl . '">';
		}
		iHomefinderLogger::getInstance()->debug('End iHomefinderOrganizerLogoutFilterImpl');
		return $body;
	}

}