<?php
if( !class_exists('iHomefinderOrganizerSendSubscriberPasswordFilterImpl')) {
	
	class iHomefinderOrganizerSendSubscriberPasswordFilterImpl implements IHomefinderFilter {
	
		public function __construct(){
			
		}
		public function getTitle(){
			return "Email Password";
		}			
		public function filter( $content, $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin iHomefinderOrganizerSendSubscriberPasswordFilterImpl');
			
			$email=IHomefinderUtility::getInstance()->getQueryVar('email');
						
			$ihfUrl = IHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=property-organizer-password-email' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "email", $email);
			
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );	
			$content=$idxContent;
			
			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End iHomefinderOrganizerSendSubscriberPasswordFilterImpl');
			
			return $content ;
		}
	}//end class
}
?>