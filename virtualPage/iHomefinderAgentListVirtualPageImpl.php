<?php

class iHomefinderAgentListVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	private $path = "agent-list";
	private $title = "Agent List";

	public function __construct() {
	}
	
	public function getTitle() {
		$customTitle = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_AGENT_LIST);
		if($customTitle != null && "" != $customTitle) {
			$this->title=$customTitle;
		}			
		return $this->title;
	}

	public function getPageTemplate() {
		$pageTemplate = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_AGENT_LIST);
		return $pageTemplate;			
	}
	
	public function getPath() {
		$customPath = get_option(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_AGENT_LIST);	
		if($customPath != null && "" != $customPath) {
			$this->path = $customPath;
		}
		return $this->path;
	}
	
			
	public function getContent() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderAgentListPageImpl');

		//used to remember search results
		$requestData = 'method=handleRequest'
			. '&viewType=json'
			. '&requestType=agent-list'
			. '&phpStyle=true'
			. '&includeSearchSummary=true';


		$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);
		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
		
		iHomefinderLogger::getInstance()->debug('End iHomefinderAgentListPageImpl');
		iHomefinderLogger::getInstance()->debug($requestData);
		return $body;
	}
}