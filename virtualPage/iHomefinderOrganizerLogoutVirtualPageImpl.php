<?php

class iHomefinderOrganizerLogoutVirtualPageImpl extends iHomefinderAbstractPropertyOrganizerVirtualPage {
	
	public function getTitle() {
		return "Organizer Login";
	}
	
	public function getPermalink() {
		return "property-organizer-logout";
	}
	
	public function getContent() {
		/**
		 * For responsive layout we need to kill the session for subscriber on java servers
		 * Where as for legacy layout we need to kill session stored locally on wordpress servers
		 */
		if(iHomefinderLayoutManager::getInstance()->isResponsive()) {
			iHomefinderStateManager::getInstance()->removeRememberMe();
			$this->remoteRequest
				->addParameter("method", "handleRequest")
				->addParameter("viewType", "json")
				->addParameter("requestType", "property-organizer-logout")
				->addParameter("phpStyle", true)
			;
			$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
		}
	}
	
	public function getBody() {
		if(iHomefinderLayoutManager::getInstance()->isResponsive()) {
			$body = $this->remoteResponse->getBody();
		} else {
			iHomefinderStateManager::getInstance()->removeSubscriberId();	
			$redirectUrl = iHomefinderUrlFactory::getInstance()->getOrganizerLoginUrl(true); 
			$body = "<meta http-equiv=\"refresh\" content=\"0;url=" . $redirectUrl . "\">";
		}
		return $body;
	}

}