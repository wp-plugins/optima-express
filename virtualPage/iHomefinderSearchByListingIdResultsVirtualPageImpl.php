<?php
if( !class_exists('IHomefinderSearchByListingIdResultsVirtualPageImpl')) {
	
	class IHomefinderSearchByListingIdResultsVirtualPageImpl implements IHomefinderVirtualPage {
		
		//default path used for URL Rewriting
		private $path="id-listing-results";
	
		public function __construct(){
			
		}
		
		public function getTitle(){
			return "Search By Listing ID Results";
		}	
			
		function getPageTemplate(){
			
		}
		
		public function getPath(){
			return $this->path ;
		}
				
		public function getContent( $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderFilter.filterSearchByListingIdResults');
			IHomefinderStateManager::getInstance()->saveLastSearch() ;
			
			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=results-by-listing-id' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
			//used to remember search results
			//$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "includeSearchSummary", "true");	
			$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST) ;
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );
	
			$content=$idxContent  ;
			
			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End IHomefinderFilter.filterSearchByListingIdResults');
						
			return $content ;
		}		
	}
}
?>