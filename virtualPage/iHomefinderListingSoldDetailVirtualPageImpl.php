<?php

class iHomefinderListingSoldDetailVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $defaultTitle = "";
	private $title = "";
	private $pageTitle = null;
	private $path = "homes-for-sale-sold-details";
	
	public function getTitle() {
		$customTitle = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_SOLD_DETAIL);				
		if($customTitle != null && "" != $customTitle) {
			$this->title=$customTitle;
		} else {
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
		$listingNumber = iHomefinderUtility::getInstance()->getQueryVar("ln");
		$boardId = iHomefinderUtility::getInstance()->getQueryVar("bid");
		$this->remoteRequest
			->addParameter("ln", $listingNumber)
			->addParameter("bid", $boardId)
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "listing-sold-detail")
		;
		$this->remoteRequest->addParameters($_REQUEST);
		$previousAndNextInformation = iHomefinderUtility::getInstance()->getPreviousAndNextInformation($boardId, $listingNumber);
		$this->remoteRequest->addParameters($previousAndNextInformation);
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
		$body = $this->remoteRequest->getContent($this->remoteResponse);
		if($this->remoteResponse != null && property_exists($this->remoteResponse, "title")) {
			$this->defaultTitle = $this->remoteResponse->title;
		}
		$previousSearchLink = $this->getPreviousSearchLink();
		$body = $previousSearchLink . "<br /><br />" . $body;
		return $body;
	}
	
	private function getPreviousSearchLink() {
		$previousSearchUrl=iHomefinderStateManager::getInstance()->getLastSearch();
		//If previous search does not exist, then use an empty search form
		if($previousSearchUrl == null || trim($previousSearchUrl) == "") {
			$previousSearchUrl= iHomefinderUrlFactory::getInstance()->getListingsSearchFormUrl(true);
			$previousSearchUrl="<a href=\"" . $previousSearchUrl . "\">&lt;&nbsp;New Search</a>";
		} else {
			$previousSearchUrl="<a href=\"" . $previousSearchUrl . "\">&lt;&nbsp;Return To Results</a>";
		}
		return $previousSearchUrl;
	}
	
}