<?php

/**
 * 
 * This virtual page is used in a shortcode and does not have a title, template or path.
 * 
 * @author ihomefinder
 *
 */
class iHomefinderAgentOrOfficeListingsVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function __construct() {			
	}
	
	public function getTitle() {
		return "";
	}
	
	public function getPageTemplate() {
		return "";			
	}
	
	/**
	 * @see wp-content/plugins/OptimaExpress/virtualPage/iHomefinderVirtualPage::getPath()
	 */
	public function getPath() {
		return "";
	}
			
	public function getContent() {
		iHomefinderStateManager::getInstance()->saveLastSearch();
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderAgentOrOfficeListingsVirtualPageImpl');
		
		$agentId  = iHomefinderUtility::getInstance()->getRequestVar('agentId');
		$officeId = iHomefinderUtility::getInstance()->getRequestVar('officeId');	

		$requestData = 'method=handleRequest'
			. '&viewType=json'
			. '&requestType=agent-or-office-listings';
			
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "agentId", $agentId);	
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "officeId", $officeId);
					
		$this->remoteResponse = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$body = iHomefinderRequestor::getInstance()->getContent($this->remoteResponse);
		
		iHomefinderLogger::getInstance()->debug('End iHomefinderAgentOrOfficeListingsVirtualPageImpl');
		iHomefinderLogger::getInstance()->debug($requestData);
		return $body;
	}
}