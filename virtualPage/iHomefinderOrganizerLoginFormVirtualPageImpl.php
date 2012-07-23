<?php
if( !class_exists('IHomefinderOrganizerLoginFormVirtualPageImpl')) {
	
	class IHomefinderOrganizerLoginFormVirtualPageImpl implements IHomefinderVirtualPage {
	
		private $path="property-organizer-login";
		private $title="Organizer Login";
		
		public function __construct(){
			
		}
		
		public function getTitle(){
			$customTitle = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_ORG_LOGIN);
			if( $customTitle != null && "" != $customTitle ){
				$this->title=$customTitle ;
			}
			
			return $this->title;
		}
	
		public function getPageTemplate(){
			$pageTemplate = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_ORG_LOGIN);
			//$pageTemplage = '';
			return $pageTemplate;			
		}
		
		public function getPath(){
			$customPath = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_ORG_LOGIN );	
			if( $customPath != null && "" != $customPath ){
				$this->path = $customPath ;
			}
			return $this->path;
		}
		
		public function getContent( $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin PropertyOrganizerLoginFormVirtualPage');
			
			$subscriberId=IHomefinderUtility::getInstance()->getQueryVar('subscriberID');
			if($subscriberId != null && trim($subscriberId) != ""){
				$subscriberInfo=IHomefinderSubscriber::getInstance($subscriberId,'', '' );
				//var_dump($subscriberInfo);
				IHomefinderStateManager::getInstance()->saveSubscriberLogin($subscriberInfo);			
			}

			$message=IHomefinderUtility::getInstance()->getQueryVar('message');
			$afterLoginUrl=IHomefinderUtility::getInstance()->getRequestVar('afterLoginUrl');		
			$ihfUrl = IHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=property-organizer-login-form' ;
			$ihfUrl = IHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
			$ihfUrl = IHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "message", $message);
			$ihfUrl = IHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "afterLoginUrl", $afterLoginUrl);
			
			$isLoggedIn = IHomefinderStateManager::getInstance()->isLoggedIn();
			if( $isLoggedIn ){
				$subscriberInfo=IHomefinderStateManager::getInstance()->getCurrentSubscriber() ;
				$subscriberId=$subscriberInfo->getId();
				$ihfUrl = IHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "subscriberId", $subscriberId);
			}
			
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );
	
			$content=$idxContent;
			
			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End PropertyOrganizerLoginFormVirtualPage');

				
			return $content ;
		}		
	}
}
?>