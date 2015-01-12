<?php
if( !class_exists('IHomefinderSearchResultsVirtualPageImpl')) {
	
	class IHomefinderSearchResultsVirtualPageImpl implements IHomefinderVirtualPage {
		
		//default path used for URL Rewriting
		private $path="homes-for-sale-results";
	
		public function __construct(){
			
		}
		
		public function getTitle(){
			return "Property Search Results";
		}	
			
		function getPageTemplate(){
			
		}
		
		public function getPath(){
			return $this->path ;
		}
				
		public function getContent( $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderFilter.filterSearchResults');
			IHomefinderStateManager::getInstance()->saveLastSearch() ;
			
			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=listing-search-results' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
			//used to remember search results
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "includeSearchSummary", "true");	
			$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST);
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );
	
			$content=$idxContent  ;
			
			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End IHomefinderFilter.filterSearchResults');
						
			return $content ;
		}		
	}
}
?>