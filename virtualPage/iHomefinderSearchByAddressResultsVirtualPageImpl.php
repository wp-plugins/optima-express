<?php
if( !class_exists('IHomefinderSearchByAddressResultsVirtualPageImpl')) {
	
	class IHomefinderSearchByAddressResultsVirtualPageImpl implements IHomefinderVirtualPage {
		
		//default path used for URL Rewriting
		private $path="address-listing-results";
	
		public function __construct(){
			
		}
		
		public function getTitle(){
			return "Search By Address Results";
		}	
			
		function getPageTemplate(){
			
		}
		
		public function getPath(){
			return $this->path ;
		}
				
		public function getContent( $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderFilter.filterSearchByAddressResults');
			IHomefinderStateManager::getInstance()->saveLastSearch() ;
			
			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=results-by-address' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
			//used to remember search results
			//$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "includeSearchSummary", "true");	
			$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST);
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );
	
			$content=$idxContent  ;
			
			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End IHomefinderFilter.filterSearchByAddressResults');
						
			return $content ;
		}		
	}
}
?>