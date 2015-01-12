<?php
if( !class_exists('IHomefinderOrganizerResendConfirmationVirtualPageImpl')) {
	
	class IHomefinderOrganizerResendConfirmationVirtualPageImpl implements IHomefinderVirtualPage {
	
		private $path ="property-organizer-resend-confirmation-email";
		
		public function __construct(){
			
		}
		
		public function getTitle(){
			return "Resend Confirmation Email";
		}	
		
		public function getPageTemplate(){
			
		}
		
		public function getPath(){
			return $this->path;	
		}	
				
		public function getContent( $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderOrganizerResendConfirmationFilterImpl');
			
			$email=IHomefinderUtility::getInstance()->getQueryVar('email');
			$password=IHomefinderUtility::getInstance()->getQueryVar('password');
			$name=IHomefinderUtility::getInstance()->getQueryVar('name');
			$phone=IHomefinderUtility::getInstance()->getQueryVar('phone');
			$agentId=IHomefinderUtility::getInstance()->getQueryVar('agentId');
			$afterLoginUrl=IHomefinderUtility::getInstance()->getRequestVar('afterLoginUrl');
			
			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=property-organizer-resend-confirm-email' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "email", $email);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "afterLoginUrl", $afterLoginUrl);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "password", $password);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "name", $name);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "phone", $phone);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "agentId", agentId);
			
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );
	
			$content=$idxContent;
			
			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End IHomefinderOrganizerResendConfirmationFilterImpl');
			
			return $content ;
		}
	}//end class
}
?>