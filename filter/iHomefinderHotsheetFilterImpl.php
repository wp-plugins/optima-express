<?php
if( !class_exists('IHomefinderHotsheetFilterImpl')) {
	
	class IHomefinderHotsheetFilterImpl implements IHomefinderFilter {
	
		public function __construct(){
			
		}
		public function getTitle(){
			return "Top Picks";
		}
		public function filter( $content, $authenticationToken ){
			IHomefinderStateManager::getInstance()->saveLastSearch() ;
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderHotsheetFilterImpl.filter');
			
			$includeMap = IHomefinderUtility::getInstance()->getRequestVar('includeMap');
			$gallery = IHomefinderUtility::getInstance()->getRequestVar('gallery');	
			$hotSheetId=IHomefinderUtility::getInstance()->getQueryVar('hotSheetId');		
			if( !isset($hotSheetId) ){
				//IHomefinderShortCodeDispatcher sets vars in $_REQUEST
				$hotSheetId=IHomefinderUtility::getInstance()->getRequestVar('hotSheetId');
			}
			$startRowNumber=IHomefinderUtility::getInstance()->getQueryVar('startRowNumber');
			$sortBy=IHomefinderUtility::getInstance()->getQueryVar('sortBy');
						
			IHomefinderLogger::getInstance()->debug('hotSheetId: ' . $hotSheetId );
			$ihfUrl = IHomefinderConstants::EXTERNAL_URL 
				. '?method=handleRequest'
				. '&viewType=json'
				. '&requestType=hotsheet-results'
				. '&authenticationToken=' . $authenticationToken;
				
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "includeMap", $includeMap);	
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "gallery", $gallery);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "hotSheetId", $hotSheetId);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "startRowNumber", $startRowNumber);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "sortBy", $sortBy);
			//used to remember search results
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "includeSearchSummary", "true");	
			
							
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );
			
			$content=$idxContent;
			
			IHomefinderLogger::getInstance()->debug('End IHomefinderHotsheetFilterImpl.filter');
			IHomefinderLogger::getInstance()->debug('<br/><br/>' . $ihfUrl);
			return $content ;
		}
	}
}
?>