<?php

class iHomefinderAgentOrOfficeListingsVirtualPageImpl extends iHomefinderAbstractVirtualPage {
			
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
	}
	
}