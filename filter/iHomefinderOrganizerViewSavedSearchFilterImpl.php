<?php
if( !class_exists('iHomefinderOrganizerViewSavedSearchFilterImpl')) {
	
	class IHomefinderOrganizerViewSavedSearchFilterImpl implements IHomefinderFilter {
	
		public function __construct(){
			
		}
		public function getTitle(){
			return "Saved Search";
		}		
		public function filter( $content, $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin iHomefinderOrganizerViewSavedSearchFilterImpl');
								
			$searchProfileId=IHomefinderUtility::getInstance()->getQueryVar('searchProfileID');
			$startRowNumber=IHomefinderUtility::getInstance()->getQueryVar('startRowNumber');
			
			$ihfUrl = IHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=property-organizer-view-saved-search' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "searchProfileId", $searchProfileId);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "startRowNumber", $startRowNumber);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);						

			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$content = IHomefinderRequestor::getContent( $contentInfo );
			
			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End iHomefinderOrganizerViewSavedSearchFilterImpl');
			
			return $content ;
		}
	}//end class
}
?>