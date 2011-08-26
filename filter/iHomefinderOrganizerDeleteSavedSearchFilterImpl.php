<?php
if( !class_exists('IHomefinderOrganizerDeleteSavedSearchFilterImpl')) {
	
	class IHomefinderOrganizerDeleteSavedSearchFilterImpl implements IHomefinderFilter {
	
		public function __construct(){
			
		}

		public function getTitle(){
			return "Delete Saved Search";
		}
		public function filter( $content, $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderOrganizerDeleteSavedSearchFilterImpl');
			
			$subscriberId=IHomefinderUtility::getInstance()->getQueryVar('subscriberID');
			$searchProfileID=IHomefinderUtility::getInstance()->getQueryVar('searchProfileID');						
			
			$ihfUrl = IHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=property-organizer-delete-saved-search-submit' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "subscriberID", $subscriberId);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "searchProfileID", $searchProfileID);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
						
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			//$content = IHomefinderRequestor::getContent( $contentInfo );
			
			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End IHomefinderOrganizerDeleteSavedSearchFilterImpl');
			
			$redirectUrl=IHomefinderUrlFactory::getInstance()->getOrganizerViewSavedSearchListUrl(true) ; 
			//redirect to the list of saved searches to avoid double posting the request
			$content = '<meta http-equiv="refresh" content="0;url=' . $redirectUrl . '">';
			
			return $content ;
		}
	}//end class
}
?>