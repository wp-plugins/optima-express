<?php

class iHomefinderListingSoldDetailVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $defaultTitle = "";
	private $title = "";
	private $pageTitle = null;
	private $path = "homes-for-sale-sold-details";
	public function __construct() {

	}

	public function getTitle() {
		$customTitle = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_SOLD_DETAIL);				
		if($customTitle != null && "" != $customTitle) {
			$this->title=$customTitle;
		}
		else{
			$this->title = $this->defaultTitle;
		}

		return $this->title;
	}
	
	public function getPath() {
		$customPath = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SOLD_DETAIL);	
		if($customPath != null && "" != $customPath) {
			$this->path = $customPath;
		}
		return $this->path;
	}
	
	function getPageTemplate() {
		$pageTemplate = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_SOLD_DETAIL);
		return $pageTemplate;
	}

	public function getContent() {
		global $post;
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderListingSoldDetailVirtualPageImpl');

		$listingNumber=iHomefinderUtility::getInstance()->getQueryVar('ln');
		$boardId=iHomefinderUtility::getInstance()->getQueryVar('bid');
		$requestData = 'ln=' . $listingNumber
			. '&bid=' . $boardId
			. '&method=handleRequest'
			. '&viewType=json'
			. '&requestType=listing-sold-detail';

		$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);
		
		$requestData = iHomefinderUtility::getInstance()->setPreviousAndNextInformation($requestData,$boardId, $listingNumber);

		iHomefinderLogger::getInstance()->debug('before logged in check');
		if(iHomefinderStateManager::getInstance()->isLoggedIn()) {
			iHomefinderLogger::getInstance()->debug('is logged in');
			$subscriberInfo=iHomefinderStateManager::getInstance()->getCurrentSubscriber();
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "subscriberId", $subscriberInfo->getId());
		}
		
		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
		iHomefinderLogger::getInstance()->debug('End iHomefinderListingSoldDetailVirtualPageImpl');
		iHomefinderLogger::getInstance()->debug($requestData);
		
		if(property_exists($this->remoteResponse, "title")) {
			//success, display the view
			$this->defaultTitle = $this->remoteResponse->title;
		}

		$previousSearchLink = $this->getPreviousSearchLink();
		$body = $previousSearchLink . '<br/><br/>' . $body;

		return $body;
	}
	


	/**
	 *
	 * @param unknown_type $content
	 */
	private function getPreviousSearchLink() {

		$previousSearchUrl=iHomefinderStateManager::getInstance()->getLastSearch();

		//If previous search does not exist, then use an empty search form
		if($previousSearchUrl == null || trim($previousSearchUrl) == '') {
			$previousSearchUrl= iHomefinderUrlFactory::getInstance()->getListingsSearchFormUrl(true);
			$previousSearchUrl="<a href='" . $previousSearchUrl . "'>&lt;&nbsp;New Search</a>";
		}
		else{
			$previousSearchUrl="<a href='" . $previousSearchUrl . "'>&lt;&nbsp;Return To Results</a>";
		}

		return $previousSearchUrl;
	}
}