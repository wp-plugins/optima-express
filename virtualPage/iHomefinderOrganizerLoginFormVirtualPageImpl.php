<?php

class iHomefinderOrganizerLoginFormVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path="property-organizer-login";
	private $title="Organizer Login";
	
	public function getTitle() {
		$customTitle = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_ORG_LOGIN);
		if($customTitle != null && "" != $customTitle) {
			$this->title=$customTitle;
		}
		
		return $this->title;
	}
	
	public function getPageTemplate() {
		$pageTemplate = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_ORG_LOGIN);
		return $pageTemplate;			
	}
	
	public function getPath() {
		$customPath = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_ORG_LOGIN);	
		if($customPath != null && "" != $customPath) {
			$this->path = $customPath;
		}
		return $this->path;
	}
	
	public function getContent() {
		$subscriberId = iHomefinderUtility::getInstance()->getQueryVar("subscriberID");
		if($subscriberId != null && trim($subscriberId) != "") {
			$subscriberInfo = iHomefinderSubscriber::getInstance($subscriberId, "", "");
			iHomefinderStateManager::getInstance()->saveSubscriberLogin($subscriberInfo);			
		}
		$message=iHomefinderUtility::getInstance()->getQueryVar("message");
		$afterLoginUrl=iHomefinderUtility::getInstance()->getRequestVar("afterLoginUrl");		
		$this->remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "property-organizer-login-form")
			->addParameter("message", $message)
			->addParameter("afterLoginUrl", $afterLoginUrl)
		;
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
		$body = $this->remoteRequest->getContent($this->remoteResponse);
		return $body;
	}
	
}