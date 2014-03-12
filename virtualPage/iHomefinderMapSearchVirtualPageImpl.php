<?php
if( !class_exists('IHomefinderMapSearchVirtualPageImpl')) {
	
	class IHomefinderMapSearchVirtualPageImpl implements IHomefinderVirtualPage {
		
		private $path="homes-for-sale-map-search";
		private $title="Map Search";
	
		public function __construct(){
			
		}
		public function getTitle(){
			$customTitle = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_MAP_SEARCH);
			if( $customTitle != null && "" != $customTitle ){
				$this->title=$customTitle ;
			}
			return $this->title;
		}
	
		public function getPageTemplate(){
			$pageTemplate = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_MAP_SEARCH);
			return $pageTemplate;			
		}
		
		public function getPath(){
			$customPath = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_MAP_SEARCH );	
			if( $customPath != null && "" != $customPath ){
				$this->path = $customPath ;
			}
			return $this->path;
		}
		
				
		public function getContent( $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderMapSearchVirtualPageImpl->getContent');
			//save url to get back from listing details page
			IHomefinderStateManager::getInstance()->saveLastSearch() ;
			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl()
			    . '?method=handleRequest&viewType=json'
			    . '&requestType=map-search-widget'
			    . '&authenticationToken=' 
				. $authenticationToken
	            . '&height=500';
			if(!IHomefinderLayoutManager::getInstance()->supportsMapSearchWithMultipleWidths()){
				$ihfUrl = $ihfUrl.'&width=595';
			}
			// adds restart= true if available to start over map session
			$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST);
			//echo($ihfUrl);
			//die();
            $contentInfo = iHomefinderRequestor::remoteRequest($ihfUrl);
            $content = IHomefinderRequestor::getContent( $contentInfo );
			
			IHomefinderLogger::getInstance()->debug('End IHomefinderMapSearchVirtualPageImpl->getContent');
			IHomefinderLogger::getInstance()->debug('<br/><br/>' . $ihfUrl);
			return $content ;
		}
	}
}
?>