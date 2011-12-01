<?php
if( !class_exists('IHomefinderSearchResultsFilterImpl')) {
	
	class IHomefinderSearchResultsFilterImpl implements IHomefinderFilter {
	
		public function __construct(){
			
		}
		
		public function getTitle(){
			return "Property Search Results";
		}	
		public function filter( $content, $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderFilter.filterSearchResults');
			IHomefinderStateManager::getInstance()->saveLastSearch() ;
			
			$ihfUrl = IHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=listing-search-results' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
			//used to remember search results
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "includeSearchSummary", "true");	
			$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST) ;
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );
	
			$content=$idxContent;
			
			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End IHomefinderFilter.filterSearchResults');
						
			return $content ;
		}		
	}
}
?>