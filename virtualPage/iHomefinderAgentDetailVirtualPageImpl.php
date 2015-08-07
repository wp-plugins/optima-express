<?php

class iHomefinderAgentDetailVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function getTitle() {
		$default = null;
		if(iHomefinderLayoutManager::getInstance()->supportsSeoVariables()) {
			$default = "{agentName}, {agentDesignation}";
		} elseif(is_object($this->remoteResponse) && $this->remoteResponse->hasTitle()) {
			$default = $this->remoteResponse->getTitle();
		}
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_AGENT_DETAIL, $default);
	}

	public function getPageTemplate() {
		return get_option(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_AGENT_DETAIL, null);
	}
	
	public function getPermalink() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_AGENT_DETAIL, "agent-detail");
	}
	
	public function getAvailableVariables() {
		$variableUtility = iHomefinderVariableUtility::getInstance();
		return array(
			$variableUtility->getAgentName(),
			$variableUtility->getAgentDesignation()
		);
	}

	public function getMetaTags() {
		$default = "<meta name=\"description\" content=\"\" />\n";
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_META_TAGS_AGENT_DETAIL, $default);
	}	
	
	public function getContent() {
		iHomefinderStateManager::getInstance()->setLastSearchUrl();
		$this->remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "agent-detail")
			->addParameter("phpStyle", true)
			->addParameter("includeSearchSummary", false)
		;
		$agentId = iHomefinderUtility::getInstance()->getQueryVar("agentId");
		//used for shortcode
		if(empty($agentId)) {
			$agentId = iHomefinderUtility::getInstance()->getRequestVar("agentId");
		}
		$this->remoteRequest->addParameter("agentID", $agentId);
		$this->remoteRequest->setCacheExpiration(60*60);
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}
	
}