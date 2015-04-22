<?php

class iHomefinderHotsheetVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path = "homes-for-sale-toppicks";
	private $title = "";
	//The default title might get updated in function getContent
	private $defaultTitle = "";

	public function getTitle() {
		$customTitle = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_HOTSHEET);
		if($customTitle != null && "" != $customTitle) {
			$this->title=$customTitle;
		} else {
			$this->title = $this->defaultTitle;
		}
		return $this->title;
	}

	public function getPageTemplate() {
		$pageTemplate = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_HOTSHEET);
		return $pageTemplate;			
	}
	
	public function getPath() {
		$customPath = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_HOTSHEET);	
		if($customPath != null && "" != $customPath) {
			$this->path = $customPath;
		}
		return $this->path;
	}
			
	public function getContent() {
		iHomefinderStateManager::getInstance()->saveLastSearch();
		$this->remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "hotsheet-results")
			->addParameter("includeSearchSummary", true)
		;
		$this->remoteRequest->addParameters($_REQUEST);
		$hotSheetId=iHomefinderUtility::getInstance()->getRequestVar("hotSheetId");
		if(!isset($hotSheetId)) {
			//iHomefinderShortCodeDispatcher sets vars in $_REQUEST
			//URL rewriting can set vars in the URL
			$hotSheetId=iHomefinderUtility::getInstance()->getQueryVar("hotSheetId");
			if(isset($hotSheetId)) {
				$this->remoteRequest->addParameter("hotSheetId", $hotSheetId);
			}
		}
		if($this->getTitle() == "") {
			$this->remoteRequest->addParameter("includeDisplayName", false);
		}
		$this->remoteRequest->setCacheExpiration(60*60);
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
		$body = $this->remoteRequest->getContent($this->remoteResponse);
		if(isset($this->remoteResponse) && isset($this->remoteResponse->title)) {
			//success, display the view
			$this->defaultTitle = $this->remoteResponse->title;
		}
		return $body;
	}
}