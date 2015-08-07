<?php

class iHomefinderOrganizerLoginFormVirtualPageImpl extends iHomefinderAbstractPropertyOrganizerVirtualPage {
	
	public function getTitle() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_ORGANIZER_LOGIN, "Organizer Login");
	}
	
	public function getPermalink() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_ORGANIZER_LOGIN, "property-organizer-login");
	}

	public function getMetaTags() {
		$default = "<meta name=\"description\" content=\"\" />\n";
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_META_TAGS_ORGANIZER_LOGIN, $default);
	}	
	
	public function getContent() {
		$subscriberId = iHomefinderUtility::getInstance()->getQueryVar("subscriberID");
		if(!empty($subscriberId)) {
			iHomefinderStateManager::getInstance()->setSubscriberId($subscriberId);			
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