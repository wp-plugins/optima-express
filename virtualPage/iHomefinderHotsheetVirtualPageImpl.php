<?php
if( !class_exists('IHomefinderHotsheetVirtualPageImpl')) {
	
	class IHomefinderHotsheetVirtualPageImpl implements IHomefinderVirtualPage {
	
		private $path="homes-for-sale-toppicks";
		private $title="";
		//The default title might get updated in function getContent
		private $defaultTitle="";
		
		public function __construct(){
			
		}

		public function getTitle(){
			$customTitle = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_HOTSHEET);
			if( $customTitle != null && "" != $customTitle ){
				$this->title=$customTitle ;
			}
			else{
				$this->title = $this->defaultTitle ;
			}

			return $this->title ;
		}
	
		public function getPageTemplate(){
			$pageTemplate = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_HOTSHEET);
			//$pageTemplage = '';
			return $pageTemplate;			
		}
		
		public function getPath(){
			$customPath = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_HOTSHEET);	
			if( $customPath != null && "" != $customPath ){
				$this->path = $customPath ;
			}
			return $this->path;
		}
				
		public function getContent( $authenticationToken ){
			IHomefinderStateManager::getInstance()->saveLastSearch() ;
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderHotsheetVirtualPageImpl');
			
			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl()
				. '?method=handleRequest'
				. '&viewType=json'
				. '&requestType=hotsheet-results'
				. '&authenticationToken=' . $authenticationToken;
				
      		$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST);
      		
			$hotSheetId=IHomefinderUtility::getInstance()->getRequestVar('hotSheetId');
			if( !isset($hotSheetId) ){
				//IHomefinderShortCodeDispatcher sets vars in $_REQUEST
				//URL rewriting can set vars in the URL
				$hotSheetId=IHomefinderUtility::getInstance()->getQueryVar('hotSheetId');
				if( isset($hotSheetId)){
					$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "hotSheetId", $hotSheetId);
				}
			}
			
			if( $this->getTitle() == ""){
				$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "includeDisplayName", "false");
			}
			//used to remember search results
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "includeSearchSummary", "true");	
			
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );
			
			if( isset($contentInfo) && isset($contentInfo->title) ){
				//success, display the view
				$this->defaultTitle = $contentInfo->title ;
			}
						
			$content=$idxContent;
			
			IHomefinderLogger::getInstance()->debug('End IHomefinderHotsheetVirtualPageImpl');
			IHomefinderLogger::getInstance()->debug('<br/><br/>' . $ihfUrl);
			return $content ;
		}
	}
}
?>