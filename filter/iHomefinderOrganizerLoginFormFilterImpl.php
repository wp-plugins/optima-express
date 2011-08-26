<?php
if( !class_exists('IHomefinderOrganizerLoginFormFilterImpl')) {
	
	class IHomefinderOrganizerLoginFormFilterImpl implements IHomefinderFilter {
	
		public function __construct(){
			
		}
		public function getTitle(){
			return "Organizer Login";
		}		
		public function filter( $content, $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin PropertyOrganizerLoginFormFilter');
			
			$subscriberId=IHomefinderUtility::getInstance()->getQueryVar('subscriberID');
			if($subscriberId != null && trim($subscriberId) != ""){
				$subscriberInfo=IHomefinderSubscriber::getInstance($subscriberId,'', '' );
				//var_dump($subscriberInfo);
				IHomefinderStateManager::getInstance()->saveSubscriberLogin($subscriberInfo);			
			}
			$isLoggedIn = IHomefinderStateManager::getInstance()->isLoggedIn();
			if( $isLoggedIn ){
				$redirectUrl=IHomefinderUrlFactory::getInstance()->getOrganizerViewSavedListingListUrl();
				$content = '<meta http-equiv="refresh" content="0;url=' . $redirectUrl . '">';
			}
			else {	
				$message=IHomefinderUtility::getInstance()->getQueryVar('message');		
				$ihfUrl = IHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=property-organizer-login-form' ;
				$ihfUrl = IHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
				$ihfUrl = IHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "message", $message);
						
				$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
				$idxContent = IHomefinderRequestor::getContent( $contentInfo );
		
				$content=$idxContent;
				
				IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
				IHomefinderLogger::getInstance()->debug('End PropertyOrganizerLoginFormFilter');
			}
				
			return $content ;
		}		
	}
}
?>