<?php

class iHomefinderListingDetailVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $defaultTitle = "";
	private $title = "";
	private $pageTitle = null;
	private $path = "homes-for-sale-details";
	
	public function __construct() {

	}

	public function getTitle() {
		$customTitle = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_DETAIL);				
		if($customTitle != null && "" != $customTitle) {
			$this->title=$customTitle;
		}
		else{
			$this->title = $this->defaultTitle;
		}

		return $this->title;
	}
	
	public function getPath() {
		$customPath = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_DETAIL);	
		if($customPath != null && "" != $customPath) {
			$this->path = $customPath;
		}
		return $this->path;
	}
	
	function getPageTemplate() {
		$pageTemplate = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_DETAIL);
		return $pageTemplate;
	}

	public function getContent() {
		global $post;
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderListingDetailVirtualPageImpl');

		$listingNumber=iHomefinderUtility::getInstance()->getQueryVar('ln');
		$boardId=iHomefinderUtility::getInstance()->getQueryVar('bid');
		$requestData = 'ln=' . $listingNumber
			. '&bid=' . $boardId
			. '&method=handleRequest'
			. '&viewType=json'
			. '&requestType=listing-detail';

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
		
		iHomefinderLogger::getInstance()->debug('End iHomefinderListingDetailVirtualPageImpl');
		iHomefinderLogger::getInstance()->debug($requestData);
		
		if($this->remoteResponse != null && property_exists($this->remoteResponse, "title")) {
			//success, display the view
			$this->defaultTitle = $this->remoteResponse->title;
		}

		$previousSearchLink = $this->getPreviousSearchLink();
		
		if(strpos($body, "<!-- INSERT RETURN TO RESULTS LINK HERE -->") !== false) {
			$body = str_replace("<!-- INSERT RETURN TO RESULTS LINK HERE -->", $previousSearchLink, $body);
		}
		else{
			$body = $previousSearchLink . '<br/><br/>' . $body;
		}

		return $body;
	}
	


	/**
	 *
	 * @param unknown_type $content
	 */
	private function getPreviousSearchLink() {

		$previousSearchUrl=iHomefinderStateManager::getInstance()->getLastSearch();
		$findme = "map-search";
		$isMapSearch = strpos($previousSearchUrl, $findme);

		//If previous search does not exist, then use an empty search form
		if($previousSearchUrl == null || trim($previousSearchUrl) == '') {
			$previousSearchUrl= iHomefinderUrlFactory::getInstance()->getListingsSearchFormUrl(true);
			$previousSearchUrl="<a href='" . $previousSearchUrl . "'>&lt;&nbsp;New Search</a>";
		}
		else if($isMapSearch !== false) {
			$previousSearchUrl="<a href='" . $previousSearchUrl . "'>&lt;&nbsp;Return To Map Search</a>";
		}
		else{
			$previousSearchUrl="<a href='" . $previousSearchUrl . "'>&lt;&nbsp;Return To Results</a>";
		}

		return $previousSearchUrl;
	}
}