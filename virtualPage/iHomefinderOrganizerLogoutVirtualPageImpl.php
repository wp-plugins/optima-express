<?php

class iHomefinderOrganizerLogoutVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path="property-organizer-logout";
	
	public function getTitle() {
		return "Organizer Login";
	}
		
	public function getPageTemplate() {
		
	}
	
	public function getPath() {
		return $this->path;
	}
	public function getContent() {
		/**
		 * For responsive layout we need to kill the session for subscriber on java servers
		 * Where as for legacy layout we need to kill session stored locally on wordpress servers
		 */
		$subscriberSessionOnJavaServers = iHomefinderLayoutManager::getInstance()->isSubscriberSessionOnJavaServers();
		if($subscriberSessionOnJavaServers) {
			iHomefinderStateManager::getInstance()->deleteRememberMeCookie();
			$this->remoteRequest
				->addParameter("method", "handleRequest")
				->addParameter("viewType", "json")
				->addParameter("requestType", "property-organizer-logout")
				->addParameter("phpStyle", true)
			;
			$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
			$body = $this->remoteRequest->getContent($this->remoteResponse);
		} else {
			iHomefinderStateManager::getInstance()->deleteSubscriberLogin();	
			$redirectUrl = iHomefinderUrlFactory::getInstance()->getListingsSearchFormUrl(true); 
			$body = "<meta http-equiv=\"refresh\" content=\"0;url=" . $redirectUrl . "\">";
		}
		return $body;
	}

}