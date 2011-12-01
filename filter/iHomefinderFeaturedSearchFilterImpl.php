<?php
if( !class_exists('IHomefinderFeaturedSearchFilterImpl')) {
	
	class IHomefinderFeaturedSearchFilterImpl implements IHomefinderFilter{
	
		public function __construct(){
			
		}
		public function getTitle(){
			return "Featured Properties";
		}
		public function filter( $content, $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderFilter.filterFeaturedSearch');
			IHomefinderStateManager::getInstance()->saveLastSearch() ;
						
			$startRowNumber=IHomefinderUtility::getInstance()->getQueryVar('startRowNumber');
			$sortBy=IHomefinderUtility::getInstance()->getQueryVar('sortBy');
			$includeMap = IHomefinderUtility::getInstance()->getRequestVar('includeMap');
			$gallery = IHomefinderUtility::getInstance()->getRequestVar('gallery');
			
			if( !is_numeric($startRowNumber)){
				$startRowNumber=1;
			}
			
			$ihfUrl = iHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=featured-search' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "startRowNumber", $startRowNumber);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "gallery", $gallery);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "includeMap", $includeMap);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);			
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "sortBy", $sortBy);
			//used to remember search results
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "includeSearchSummary", "true");				
			
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);			
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );
			$content=$idxContent;
			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End IHomefinderFilter.filterFeaturedSearch');
			return $content ;
		}
	}
}
?>