<?php

class iHomefinderOrganizerLoginSubmitVirtualPageImpl extends iHomefinderAbstractPropertyOrganizerVirtualPage {
	
	public function getTitle() {
		return "Organizer Login";
	}
	
	public function getPermalink() {
		return "property-organizer-login-submit";
	}
	public function getMetaTags() {
		$default = "<meta name=\"description\" content=\"\" />\n";
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_META_TAGS_ORGANIZER_LOGIN, $default);
	}
	public function getContent() {
		$rememberMe = iHomefinderUtility::getInstance()->getRequestVar("rememberMe");
		if($rememberMe === "1") {
			iHomefinderStateManager::getInstance()->setRememberMe(true);
		}
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "property-organizer-login-submit")
		;
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}
	
}