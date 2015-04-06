<?php

class iHomefinderOrganizerLoginFormVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path="property-organizer-login";
	private $title="Organizer Login";
	
	public function __construct() {
		
	}
	
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
		iHomefinderLogger::getInstance()->debug('Begin PropertyOrganizerLoginFormVirtualPage');
		
		$subscriberId=iHomefinderUtility::getInstance()->getQueryVar('subscriberID');
		if($subscriberId != null && trim($subscriberId) != "") {
			$subscriberInfo=iHomefinderSubscriber::getInstance($subscriberId,'', '');
			//var_dump($subscriberInfo);
			iHomefinderStateManager::getInstance()->saveSubscriberLogin($subscriberInfo);			
		}

		$message=iHomefinderUtility::getInstance()->getQueryVar('message');
		$afterLoginUrl=iHomefinderUtility::getInstance()->getRequestVar('afterLoginUrl');		
		$requestData = 'method=handleRequest&viewType=json&requestType=property-organizer-login-form';
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "message", $message);
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "afterLoginUrl", $afterLoginUrl);
		
		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
		
		iHomefinderLogger::getInstance()->debug($requestData);
		iHomefinderLogger::getInstance()->debug('End PropertyOrganizerLoginFormVirtualPage');

		return $body;
	}		
}