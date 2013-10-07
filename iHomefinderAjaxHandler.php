<?php
if( !class_exists('IHomefinderAjaxHandler')) {
	/**
	 * This class is handle all iHomefinder Ajax Requests.
	 * It proxies the requests and returns the proper results.
	 *
	 * @author ihomefinder
	 */
	class IHomefinderAjaxHandler {

		private static $instance ;
		private $ihfAdmin ;

		private function __construct(){
			$this->ihfAdmin = IHomefinderAdmin::getInstance();
		}
		
		private function isSpam(){
			$isSpam=true;			
			$spamChecker = IHomefinderUtility::getInstance()->getRequestVar('JKGH00920');
			if( IHomefinderUtility::getInstance()->isStringEmpty($spamChecker)){
				//if spamChecker is NOT empty, then we know this is SPAM
				$isSpam=false ;
			}
			return $isSpam ;
		}

		public static function getInstance(){
			if( !isset(self::$instance)){
				self::$instance = new IHomefinderAjaxHandler();
			}
			return self::$instance;
		}

		public function requestMoreInfo(){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderAjaxHandler.requestMoreInfo');
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();
			
			$isSpam=$this->isSpam() ;			
			if( $isSpam == false ){
				$ihfUrl = iHomefinderConstants::EXTERNAL_URL ;
				$ihfUrl .= '?method=handleRequest&viewType=json&requestType=request-more-info';
				$ihfUrl .= '&authenticationToken=' . $authenticationToken ;	
				
				$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST );
				
				$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl,true);
				
				$content = IHomefinderRequestor::getContent( $contentInfo );
				IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
				IHomefinderLogger::getInstance()->debug('End IHomefinderAjaxHandler.requestMoreInfo');
	
				echo $content ;
			}
			die();
		}

		public function scheduleShowing(){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderAjaxHandler.scheduleShowing');
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();
			
			$isSpam=$this->isSpam() ;
			if( $isSpam == false  ){
				
				$ihfUrl = iHomefinderConstants::EXTERNAL_URL ;
				$ihfUrl .= '?method=handleRequest&viewType=json&requestType=schedule-showing';
				$ihfUrl .= '&authenticationToken=' . $authenticationToken ;	
				
				$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST );				
				$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl,true);				
				$content = IHomefinderRequestor::getContent( $contentInfo );
	
				IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
				IHomefinderLogger::getInstance()->debug('End IHomefinderAjaxHandler.scheduleShowing');
	
				echo $content ;
					
			}
			die();
		}

		public function photoTour(){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderAjaxHandler.photoTour');
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();

			$action = IHomefinderUtility::getInstance()->getRequestVar('action');
			$listingNumber = IHomefinderUtility::getInstance()->getRequestVar('listingNumber');
			$boardID = IHomefinderUtility::getInstance()->getRequestVar('boardID');

			$ihfUrl = iHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=photo-tour' ;

			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "listingNumber", $listingNumber);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "boardID", $boardID);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);

			//echo($ihfUrl);
			//die();
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl, true);
			$content = IHomefinderRequestor::getContent( $contentInfo );

			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End IHomefinderAjaxHandler.photoTour');

			echo $content ;
			die();
		}

		public function saveProperty(){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderAjaxHandler.saveProperty');
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();

			$action = IHomefinderUtility::getInstance()->getRequestVar('action');
			$listingNumber = IHomefinderUtility::getInstance()->getRequestVar('listingNumber');
			$boardID = IHomefinderUtility::getInstance()->getRequestVar('boardID');
			$interestLevel = IHomefinderUtility::getInstance()->getRequestVar('interestLevel');
			$name = IHomefinderUtility::getInstance()->getRequestVar('name');
			$phone = IHomefinderUtility::getInstance()->getRequestVar('phone');
			$email = IHomefinderUtility::getInstance()->getRequestVar('email');
			$password = IHomefinderUtility::getInstance()->getRequestVar('password');
			$actionType = IHomefinderUtility::getInstance()->getRequestVar('actionType');
			$subscriberId = IHomefinderUtility::getInstance()->getRequestVar('subscriberID');
			$captchaKey = IHomefinderUtility::getInstance()->getRequestVar('key');
			$captchaToken = IHomefinderUtility::getInstance()->getRequestVar('token');
			

			$ihfUrl = iHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=save-property' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "action", $action);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "actionType", $actionType);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "subscriberId", $subscriberId);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "listingNumber", $listingNumber);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "boardID", $boardID);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "interestLevel", $interestLevel);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "name", $name);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "phone", $phone);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "email", $email);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "password", $password);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "key", $captchaKey);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "token", $captchaToken);
			
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl, true);
			IHomefinderLogger::getInstance()->debugDumpVar($contentInfo);
			

			$content = IHomefinderRequestor::getContent($contentInfo);
			echo $content ;
			die();
		}

		public function saveSearch(){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderAjaxHandler.saveSearch');
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();

			$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST );	
			
			$action = IHomefinderUtility::getInstance()->getRequestVar('action');
			$captchaKey = IHomefinderUtility::getInstance()->getRequestVar('key');
			$captchaToken = IHomefinderUtility::getInstance()->getRequestVar('token');
			$name = IHomefinderUtility::getInstance()->getRequestVar('name');
			$phone = IHomefinderUtility::getInstance()->getRequestVar('phone');
			$email = IHomefinderUtility::getInstance()->getRequestVar('email');
			$password = IHomefinderUtility::getInstance()->getRequestVar('password');
			$actionType = ""  ;
			$method=IHomefinderUtility::getInstance()->getRequestVar('method');

			if( $method == "createNewAccountAndSaveSearch"){
				$actionType="newaccount";
			}

			$subscriberId=IHomefinderUtility::getInstance()->getRequestVar('subscriberID');
			if( empty( $subscriberId )){
				
			}

			$ihfUrl = iHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=save-search' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "action", $action);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "actionType", $actionType);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "key", $captchaKey);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "token", $captchaToken);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "subscriberName", $name);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "phone", $phone);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "email", $email);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "password", $password);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "modal", "true");
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "subscriberId", $subscriberId);
			
			//we need to initialize here for Ajax requests, when trying to save a search
			IHomefinderStateManager::getInstance()->initialize();
			$lastSearchQueryString = IHomefinderStateManager::getInstance()->getLastSearchQueryString();
			$lastSearchQueryString = str_replace('[]', '', $lastSearchQueryString );
			$lastSearchQueryString = str_replace('%5B%5D', '', $lastSearchQueryString );
			$ihfUrl .= '&' . $lastSearchQueryString ;
			
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl, true);

			$content = IHomefinderRequestor::getContent( $contentInfo );

			IHomefinderLogger::getInstance()->debugDumpVar($contentInfo );

			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End IHomefinderAjaxHandler.saveSearch');

			echo $content ;
			die();
		}
		
		public function advancedSearchMultiSelects(){
			IHomefinderLogger::getInstance()->debug('Begin advancedSearchMultiSelects');
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();
			$ihfUrl = iHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=advanced-search-multi-select-values' ;
			$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST) ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken );
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "phpStyle", "true" );
			
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl, true);
			$content = IHomefinderRequestor::getContent( $contentInfo );

			echo $content ;
			IHomefinderLogger::getInstance()->debug('End advancedSearchMultiSelects');
			die();
		}
		
		public function getAdvancedSearchFormFields(){
			IHomefinderLogger::getInstance()->debug('Begin getAdvancedSearchFormFields');
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken() ;
			
			$boardID = IHomefinderUtility::getInstance()->getRequestVar('boardID');
			
			$ihfUrl = iHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=advanced-search-fields' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken );
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "boardID", $boardID );
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "phpStyle", "true" );
			
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl, true);

			$content = IHomefinderRequestor::getContent( $contentInfo );

			echo $content ;
			IHomefinderLogger::getInstance()->debug('End getAdvancedSearchFormFields');
			die();
		}		
		
		public function leadCaptureLogin(){
			IHomefinderLogger::getInstance()->debug('Begin leadCaptureLogin');
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken() ;
			
			$ihfUrl = iHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=lead-capture-login' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken );
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "phpStyle", "true" );
			
			$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST );	
			
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl, true);
			$content = IHomefinderRequestor::getContent( $contentInfo );
			echo $content ;
			IHomefinderLogger::getInstance()->debug('End leadCaptureLogin');
			die();
		}				
	}//end class
}//end ifclass_exists
?>