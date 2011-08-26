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

		public static function getInstance(){
			if( !isset(self::$instance)){
				self::$instance = new IHomefinderAjaxHandler();
			}
			return self::$instance;
		}

		public function requestMoreInfo(){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderFilter.requestMoreInfo');
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();

			$action = IHomefinderUtility::getInstance()->getRequestVar('action');
			$listingNumber = IHomefinderUtility::getInstance()->getRequestVar('listingNumber');
			$boardID = IHomefinderUtility::getInstance()->getRequestVar('boardID');
			$interestLevel = IHomefinderUtility::getInstance()->getRequestVar('interestLevel');
			$name = IHomefinderUtility::getInstance()->getRequestVar('name');
			$phone = IHomefinderUtility::getInstance()->getRequestVar('phone');
			$email = IHomefinderUtility::getInstance()->getRequestVar('email');
			$password = IHomefinderUtility::getInstance()->getRequestVar('password');
			$message = IHomefinderUtility::getInstance()->getRequestVar('message');

			$ihfUrl = iHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=request-more-info' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "action", $action);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "listingNumber", $listingNumber);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "boardID", $boardID);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "interestLevel", $interestLevel);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "name", $name);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "phone", $phone);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "email", $email);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "password", $password);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "message", $message);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);

			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$content = IHomefinderRequestor::getContent( $contentInfo );
			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End IHomefinderFilter.requestMoreInfo');

			echo $content ;
			die();
		}

		public function scheduleShowing(){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderFilter.scheduleShowing');
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();

			$action = IHomefinderUtility::getInstance()->getRequestVar('action');
			$listingNumber = IHomefinderUtility::getInstance()->getRequestVar('listingNumber');
			$boardID = IHomefinderUtility::getInstance()->getRequestVar('boardID');

			$name = IHomefinderUtility::getInstance()->getRequestVar('name');
			$phone = IHomefinderUtility::getInstance()->getRequestVar('phone');
			$phoneAlt = IHomefinderUtility::getInstance()->getRequestVar('phone_alt');
			$email = IHomefinderUtility::getInstance()->getRequestVar('email');
			$password = IHomefinderUtility::getInstance()->getRequestVar('password');
			$comments = IHomefinderUtility::getInstance()->getRequestVar('comments');
			$prefDate = IHomefinderUtility::getInstance()->getRequestVar('prefDate');
			$prefTime = IHomefinderUtility::getInstance()->getRequestVar('prefTime');
			$altDate = IHomefinderUtility::getInstance()->getRequestVar('altDate');
			$altTime = IHomefinderUtility::getInstance()->getRequestVar('altTime');

			$ihfUrl = iHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=schedule-showing' ;

			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "listingNumber", $listingNumber);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "boardID", $boardID);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "name", $name);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "phone", $phone);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "phone_alt", $phoneAlt);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "email", $email);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "password", $password);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "comments", $comments);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "prefDate", $prefDate);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "prefTime", $prefTime);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "altDate", $altDate);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "altTime", $altTime);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);

			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$content = IHomefinderRequestor::getContent( $contentInfo );

			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End IHomefinderFilter.scheduleShowing');

			echo $content ;
			die();
		}

		public function photoTour(){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderFilter.photoTour');
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();

			$action = IHomefinderUtility::getInstance()->getRequestVar('action');
			$listingNumber = IHomefinderUtility::getInstance()->getRequestVar('listingNumber');
			$boardID = IHomefinderUtility::getInstance()->getRequestVar('boardID');

			$ihfUrl = iHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=photo-tour' ;

			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "listingNumber", $listingNumber);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "boardID", $boardID);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);

			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$content = IHomefinderRequestor::getContent( $contentInfo );

			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End IHomefinderFilter.photoTour');

			echo $content ;
			die();
		}

		public function saveProperty(){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderFilter.saveProperty');
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
			$subscriberId = IHomefinderUtility::getInstance()->getRequestVar('subscriberId');


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

			IHomefinderLogger::getInstance()->debugDumpVar($ihfUrl);
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			IHomefinderLogger::getInstance()->debugDumpVar($contentInfo);
			//var_dump($contentInfo);
			IHomefinderLogger::getInstance()->debug( 'before NOT isError' ) ;
			if( !IHomefinderRequestor::isError($contentInfo)){
				IHomefinderLogger::getInstance()->debug( 'begin serialize' ) ;
				$subscriberId = $contentInfo->subscriberInfo->id ;
				$subscriberName = $contentInfo->subscriberInfo->name ;
				$subscriberEmail = $contentInfo->subscriberInfo->email ;
				$subscriber = IHomefinderSubscriber::getInstance($subscriberId, $subscriberName, $subscriberEmail);
				IHomefinderLogger::getInstance()->debugDumpVar($subscriber);
				IHomefinderStateManager::getInstance()->saveSubscriberLogin($subscriber);

				IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
				IHomefinderLogger::getInstance()->debug('End IHomefinderFilter.saveProperty');
			}



			$content = IHomefinderRequestor::getContent($contentInfo);
			echo $content ;
			die();
		}

		public function saveSearch(){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderFilter.saveSearch');
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();

			$action = IHomefinderUtility::getInstance()->getRequestVar('action');
			$boardID = IHomefinderUtility::getInstance()->getRequestVar('boardID');
			$interestLevel = IHomefinderUtility::getInstance()->getRequestVar('interestLevel');
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

			$ihfUrl = iHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=save-search' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "action", $action);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "actionType", $actionType);
			//$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "boardID", $boardID);
			//$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "interestLevel", $interestLevel);
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


			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);

			$content = IHomefinderRequestor::getContent( $contentInfo );

			IHomefinderLogger::getInstance()->debugDumpVar($contentInfo );

			if( !IHomefinderRequestor::isError($contentInfo)){
				IHomefinderLogger::getInstance()->debug( 'begin serialize' ) ;
				$subscriberId = $contentInfo->subscriberInfo->subscriberId ;
				$subscriberName = $contentInfo->subscriberInfo->name ;
				$subscriberEmail = $contentInfo->subscriberInfo->email ;
				$subscriber = IHomefinderSubscriber::getInstance($subscriberId, $subscriberName, $subscriberEmail);
				IHomefinderStateManager::getInstance()->saveSubscriberLogin($subscriber);
			}

			$subscriberData=$contentInfo->subscriberInfo ;
			//var_dump($subscriberData);
			$subscriberInfo=IHomefinderSubscriber::getInstance($subscriberData->subscriberId,$subscriberData->name, $subscriberData->email );
			//var_dump($subscriberInfo);
			IHomefinderStateManager::getInstance()->saveSubscriberLogin($subscriberInfo);

			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End IHomefinderFilter.saveSearch');

			echo $content ;
			die();
		}
	}//end class
}//end ifclass_exists
?>