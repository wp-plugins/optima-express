<?php

class iHomefinderAgentDetailVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path = "agent-detail";
	private $title = "";
	private $defaultTitle = "";
	
	public function getTitle() {
		$customTitle = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_AGENT_DETAIL);
		if($customTitle != null && "" != $customTitle) {
			$this->title=$customTitle;
		}
		else{
			$this->title = $this->defaultTitle;
		}

		return $this->title;
	}

	public function getPageTemplate() {
		$pageTemplate = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_AGENT_DETAIL);
		return $pageTemplate;			
	}
	
	public function getPath() {
		$customPath = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_AGENT_DETAIL);	
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
			->addParameter("requestType", "agent-detail")
			->addParameter("phpStyle", true)
			->addParameter("includeSearchSummary", false)
		;
		$agentId = iHomefinderUtility::getInstance()->getQueryVar("agentID");
		if($agentId != null && is_numeric($agentId)) {
			$this->remoteRequest->addParameter("agentID", $agentId);
		}
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
		$body = $this->remoteRequest->getContent($this->remoteResponse);
		
		if(property_exists($this->remoteResponse, "title")) {
			//success, display the view
			$this->defaultTitle = $this->remoteResponse->title;
		}
		return $body;
	}
}