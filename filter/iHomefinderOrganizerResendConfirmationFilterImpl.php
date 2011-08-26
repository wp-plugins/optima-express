<?php
if( !class_exists('IHomefinderOrganizerResendConfirmationFilterImpl')) {
	
	class IHomefinderOrganizerResendConfirmationFilterImpl implements IHomefinderFilter {
	
		public function __construct(){
			
		}
		public function getTitle(){
			return "Resend Confirmation Email";
		}			
		public function filter( $content, $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderOrganizerResendConfirmationFilterImpl');
			
			$email=IHomefinderUtility::getInstance()->getQueryVar('email');
			$password=IHomefinderUtility::getInstance()->getQueryVar('password');
			$name=IHomefinderUtility::getInstance()->getQueryVar('name');
			$phone=IHomefinderUtility::getInstance()->getQueryVar('phone');
			$agentId=IHomefinderUtility::getInstance()->getQueryVar('agentId');
			
			$ihfUrl = IHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=property-organizer-resend-confirm-email' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "email", $email);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "password", $password);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "name", $name);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "phone", $phone);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "agentId", agentId);
			
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );
	
			$content=$idxContent;
			$subscriberData=$contentInfo->subscriberInfo ;
			//var_dump($subscriberData);
			$subscriberInfo=IHomefinderSubscriber::getInstance($subscriberData->subscriberId,$subscriberData->name, $subscriberData->email );
			//var_dump($subscriberInfo);
			IHomefinderStateManager::getInstance()->saveSubscriberLogin($subscriberInfo);
			
			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End IHomefinderOrganizerResendConfirmationFilterImpl');
			
			return $content ;
		}
	}//end class
}
?>