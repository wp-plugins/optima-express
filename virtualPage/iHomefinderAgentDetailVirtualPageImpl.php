<?php
if( !class_exists('IHomefinderAgentDetailVirtualPageImpl')) {
	
	class IHomefinderAgentDetailVirtualPageImpl implements IHomefinderVirtualPage {
	
		private $path="agent-detail";
		private $title="";
		private $defaultTitle="Agent Bio";
	
		public function __construct(){
		}
		
		public function getTitle(){
			$customTitle = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_AGENT_DETAIL);
			if( $customTitle != null && "" != $customTitle ){
				$this->title=$customTitle ;
			}
			else{
				$this->title = $this->defaultTitle ;
			}

			return $this->title ;
		}
	
		public function getPageTemplate(){
			$pageTemplate = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_AGENT_DETAIL);
			//$pageTemplage = '';
			return $pageTemplate;			
		}
		
		public function getPath(){
			$customPath = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_AGENT_DETAIL );	
			if( $customPath != null && "" != $customPath ){
				$this->path = $customPath ;
			}
			return $this->path;
		}
		
				
		public function getContent( $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderAgentDetailPageImpl');
			IHomefinderStateManager::getInstance()->saveLastSearch() ;
			
			$agentID=IHomefinderUtility::getInstance()->getQueryVar('agentID');
			//used to remember search results
			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl()
				. '?method=handleRequest'
				. '&viewType=json'
				. '&requestType=agent-detail'
				. '&authenticationToken=' . $authenticationToken
				. '&phpStyle=true'
				. '&includeSearchSummary=true';

			if( is_numeric($agentID)){
				$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "agentID", $agentID);		
			}

			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );
			
			$content=$idxContent;
			
			if( property_exists( $contentInfo, "title")){
				//success, display the view
				$this->defaultTitle = $contentInfo->title ;
			}			
			
			IHomefinderLogger::getInstance()->debug('End IHomefinderAgentDetailPageImpl');
			IHomefinderLogger::getInstance()->debug('<br/><br/>' . $ihfUrl);
			return $content ;
		}
	}
		
}
?>