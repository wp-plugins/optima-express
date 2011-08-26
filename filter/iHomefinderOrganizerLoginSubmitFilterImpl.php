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

			$email=IHomefinderUtility::getInstance()->getQueryVar('email');
			$password=IHomefinderUtility::getInstance()->getQueryVar('password');
			$fullname=IHomefinderUtility::getInstance()->getQueryVar('fullname');
			$phone=IHomefinderUtility::getInstance()->getQueryVar('phone');
			$agentId=IHomefinderUtility::getInstance()->getQueryVar('agentId');
			$actionType=IHomefinderUtility::getInstance()->getQueryVar('actionType');

			$subscriberId=IHomefinderUtility::getInstance()->getQueryVar('subscriberID');

			$ihfUrl = IHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=property-organizer-login-submit' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);

			if( $subscriberId == null || trim($subscriberId) == ""){
				$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "email", $email);
				$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "password", $password);
				$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "fullname", $fullname);
				$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "phone", $phone);
				$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "agentId", $agentId);
				$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "actionType", $actionType);
			} else {
				$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "subscriberId", $subscriberId);
			}

			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );

			$content=$idxContent;
			
			if( isset( $contentInfo->subscriberInfo )){
				$subscriberData=$contentInfo->subscriberInfo ;
				//var_dump($subscriberData);
				$subscriberInfo=IHomefinderSubscriber::getInstance($subscriberData->subscriberId,$subscriberData->name, $subscriberData->email );
				//var_dump($subscriberInfo);
				IHomefinderStateManager::getInstance()->saveSubscriberLogin($subscriberInfo);
			}
			$isLoggedIn = IHomefinderStateManager::getInstance()->isLoggedIn();
			if( $isLoggedIn ){
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