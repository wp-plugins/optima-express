<?php

class iHomefinderOrganizerLoginFormVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function getTitle() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_ORGANIZER_LOGIN, "Organizer Login");
	}
	
	public function getPageTemplate() {
		return get_option(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_ORGANIZER_LOGIN, null);
	}
	
	public function getPermalink() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_ORGANIZER_LOGIN, "property-organizer-login");
	}
	
	public function getContent() {
		$subscriberId = iHomefinderUtility::getInstance()->getQueryVar("subscriberID");
		if(!empty($subscriberId)) {
			$subscriber = new iHomefinderSubscriber($subscriberId, null, null);
			iHomefinderStateManager::getInstance()->saveSubscriberLogin($subscriber);			
		}
		$message = iHomefinderUtility::getInstance()->getQueryVar("message");
		$afterLoginUrl=iHomefinderUtility::getInstance()->getRequestVar("afterLoginUrl");		
		$this->remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "property-organizer-login-form")
			->addParameter("message", $message)
			->addParameter("afterLoginUrl", $afterLoginUrl)
		;
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}
	
}