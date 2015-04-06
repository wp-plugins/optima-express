<?php

class iHomefinderAgentDetailVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path = "agent-detail";
	private $title = "";
	private $defaultTitle = "";

	public function __construct() {
	}
	
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
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderAgentDetailPageImpl');
		iHomefinderStateManager::getInstance()->saveLastSearch();
		
		if(iHomefinderUtility::getInstance()->getQueryVar('agentID')) {
			$_REQUEST['agentID']=iHomefinderUtility::getInstance()->getQueryVar('agentID');
		}
		
		$agentID=iHomefinderUtility::getInstance()->getRequestVar('agentID');
		//used to remember search results
		$requestData = 'method=handleRequest'
			. '&viewType=json'
			. '&requestType=agent-detail'
			. '&phpStyle=true'
			. '&includeSearchSummary=true';

		if(is_numeric($agentID)) {
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "agentID", $agentID);		
		}

		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
		
		if(property_exists($this->remoteResponse, "title")) {
			//success, display the view
			$this->defaultTitle = $this->remoteResponse->title;
		}			
		
		iHomefinderLogger::getInstance()->debug('End iHomefinderAgentDetailPageImpl');
		iHomefinderLogger::getInstance()->debug($requestData);
		return $body;
	}
}