<?php
if( !class_exists('iHomefinderOrganizerActivateSubscriberFilterImpl')) {
	
	class IHomefinderOrganizerActivateSubscriberFilterImpl implements IHomefinderFilter {
	
		public function __construct(){
			
		}
		public function getTitle(){
			return "Subscriber Activation";
		}		
		public function filter( $content, $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin iHomefinderOrganizerActivateSubscriberFilterImpl');
			
			$email=IHomefinderUtility::getInstance()->getQueryVar('email');
			
			$ihfUrl = IHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=property-organizer-activate-subscriber' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "email", $email);
			
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );
	
			$content=$idxContent;
			
			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End iHomefinderOrganizerActivateSubscriberFilterImpl');
			
			return $content ;
		}
	}//end class
}
?>