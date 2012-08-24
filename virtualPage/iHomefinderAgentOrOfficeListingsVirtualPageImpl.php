<?php
if( !class_exists('IHomefinderAgentOrOfficeListingsVirtualPageImpl')) {
	
	/**
	 * 
	 * This virtual page is used in a shortcode and does not have a title, template or path.
	 * 
	 * @author ihomefinder
	 *
	 */
	class IHomefinderAgentOrOfficeListingsVirtualPageImpl implements IHomefinderVirtualPage {
		
		public function __construct(){			
		}

		
		/**
		 * @see wp-content/plugins/OptimaExpress/virtualPage/IHomefinderVirtualPage::getTitle()
		 */
		public function getTitle(){
			return "" ;
		}
	

		/**
		 * @see wp-content/plugins/OptimaExpress/virtualPage/IHomefinderVirtualPage::getPageTemplate()
		 */
		public function getPageTemplate(){
			return "";			
		}
		
		/**
		 * @see wp-content/plugins/OptimaExpress/virtualPage/IHomefinderVirtualPage::getPath()
		 */
		public function getPath(){
			return "";
		}
				
		public function getContent( $authenticationToken ){
			IHomefinderStateManager::getInstance()->saveLastSearch() ;
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderAgentOrOfficeListingsVirtualPageImpl');
			
			$agentId  = IHomefinderUtility::getInstance()->getRequestVar('agentId');
			$officeId = IHomefinderUtility::getInstance()->getRequestVar('officeId');	

			$ihfUrl = IHomefinderConstants::EXTERNAL_URL 
				. '?method=handleRequest'
				. '&viewType=json'
				. '&requestType=agent-or-office-listings'
				. '&authenticationToken=' . $authenticationToken;
				
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "agentId", $agentId);	
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "officeId", $officeId);
						
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );
						
			$content=$idxContent;
			
			IHomefinderLogger::getInstance()->debug('End IHomefinderAgentOrOfficeListingsVirtualPageImpl');
			IHomefinderLogger::getInstance()->debug('<br/><br/>' . $ihfUrl);
			return $content ;
		}
	}
}
?>