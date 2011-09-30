<?php
if( !class_exists('IHomefinderOrganizerLoginSubmitFilterImpl')) {

	class IHomefinderOrganizerLoginSubmitFilterImpl implements IHomefinderFilter {

		public function __construct(){

		}
		public function getTitle(){
			return "Organizer Login";
		}

		public function filter( $content, $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin PropertyOrganizerLoginSubmitFilter');
			
			$subscriberId=IHomefinderUtility::getInstance()->getQueryVar('subscriberID');

			$ihfUrl = IHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=property-organizer-login-submit' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);

			if( $subscriberId == null || trim($subscriberId) == ""){
				//If no subscriber id, then get the authentication info from the request and pass it along
				$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST) ;
			} else {
				$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "subscriberId", $subscriberId);
			}

			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$content = IHomefinderRequestor::getContent( $contentInfo );
			
			$isLoggedIn = IHomefinderStateManager::getInstance()->isLoggedIn();
			if( $isLoggedIn && $content == ""){
				$redirectUrl=IHomefinderUrlFactory::getInstance()->getOrganizerViewSavedListingListUrl();
				$content = '<meta http-equiv="refresh" content="0;url=' . $redirectUrl . '">';
			}

			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End PropertyOrganizerLoginSubmitFilter');

			return $content ;
		}
	}//end class
}
?>