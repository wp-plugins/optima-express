<?php

/**
 * 
 * This virtual page is used in a shortcode and does not have a title, template or path.
 * 
 * @author ihomefinder
 *
 */
class iHomefinderAgentOrOfficeListingsVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function getTitle() {
		return "";
	}
	
	public function getPageTemplate() {
		return "";			
	}
	
	public function getPath() {
		return "";
	}
			
	public function getContent() {
		iHomefinderStateManager::getInstance()->saveLastSearch();
		$agentId = iHomefinderUtility::getInstance()->getRequestVar("agentId");
		$officeId = iHomefinderUtility::getInstance()->getRequestVar("officeId");
		$this->remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "agent-or-office-listings")
			->addParameter("agentId", $agentId)
			->addParameter("officeId", $officeId)
		;		
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
		$body = $this->remoteRequest->getContent($this->remoteResponse);
		return $body;
	}
}