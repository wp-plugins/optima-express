<?php
if( !class_exists('IHomefinderOrganizerEditSavedSearchFilterImpl')) {
	
	class IHomefinderOrganizerEditSavedSearchFilterImpl implements IHomefinderFilter {
	
		public function __construct(){
			
		}
		public function getTitle(){
			return "Email Alert";
		}		
		public function filter( $content, $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderOrganizerEditSavedSearchFilterImpl');
						
			$searchProfileName=IHomefinderUtility::getInstance()->getQueryVar('searchProfileName');
			
			$ihfUrl = IHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=property-organizer-edit-saved-search-submit' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "name", $searchProfileName);
			$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST) ;
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$content = IHomefinderRequestor::getContent( $contentInfo );	

			if(IHomefinderStateManager::getInstance()->isLoggedIn()){	
				$redirectUrl=IHomefinderUrlFactory::getInstance()->getOrganizerViewSavedSearchListUrl(true) ; 
				//redirect to the list of saved searches to avoid double posting the request
				$content = '<meta http-equiv="refresh" content="0;url=' . $redirectUrl . '">';
			} else {
				$redirectUrl=IHomefinderUrlFactory::getInstance()->getOrganizerEmailUpdatesConfirmationUrl(true) ; 
				//redirect to the list of saved searches to avoid double posting the request
				$content = '<meta http-equiv="refresh" content="0;url=' . $redirectUrl . '">';
			}
				
			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End IHomefinderOrganizerEditSavedSearchFilterImpl');
			
			return $content ;
		}
	}//end class
}
?>