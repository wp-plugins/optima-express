<?php
if( !class_exists('IHomefinderOrganizerEmailUpdatesConfirmationFilterImpl')) {
	
	class IHomefinderOrganizerEmailUpdatesConfirmationFilterImpl implements IHomefinderFilter {
	
		public function __construct(){
			
		}
		public function getTitle(){
			return "Email Updates Confirmation";
		}		
		public function filter( $content, $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderOrganizerEmailUpdatesConfirmationFilterImpl');
			$message=IHomefinderUtility::getInstance()->getQueryVar('message');		
			$ihfUrl = IHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=property-organizer-email-updates-confirmation' ;
			$ihfUrl = IHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
			$ihfUrl = IHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "message", $message);
						
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );

			$content=$idxContent;
				
			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End IHomefinderOrganizerEmailUpdatesConfirmationFilterImpl');
			
			return $content ;
		}		
	}
}
?>
