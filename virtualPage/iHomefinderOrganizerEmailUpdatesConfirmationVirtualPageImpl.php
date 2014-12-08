<?php
if( !class_exists('IHomefinderOrganizerEmailUpdatesConfirmationVirtualPageImpl')) {
	
	class IHomefinderOrganizerEmailUpdatesConfirmationVirtualPageImpl implements IHomefinderVirtualPage {
	
		private $path="email-updates-confirmation";
		public function __construct(){
			
		}
		public function getTitle(){
			return "Email Updates Confirmation";
		}	

		public function getPageTemplate(){
			
		}

		public function getPath(){
			return $this->path;
		}
		
		public function getContent( $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderOrganizerEmailUpdatesConfirmationVirtualPageImpl');
			$message=IHomefinderUtility::getInstance()->getQueryVar('message');		
			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=property-organizer-email-updates-confirmation' ;
			$ihfUrl = IHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
			$ihfUrl = IHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "message", $message);
						
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );

			$content=$idxContent;
				
			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End IHomefinderOrganizerEmailUpdatesConfirmationVirtualPageImpl');
			
			return $content ;
		}		
	}
}
?>
