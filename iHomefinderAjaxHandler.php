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
				$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() ;
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
    
    public function contactFormRequest(){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderAjaxHandler.contactFormRequest');
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();
			
			$isSpam=$this->isSpam() ;			
			if( $isSpam == false ){
				$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() ;
				$ihfUrl .= '?method=handleRequest&viewType=json&requestType=contact-form';
				$ihfUrl .= '&authenticationToken=' . $authenticationToken ;	
				
				$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST );
				
				$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl,true);
				
				$content = IHomefinderRequestor::getContent( $contentInfo );
				IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
				IHomefinderLogger::getInstance()->debug('End IHomefinderAjaxHandler.contactFormRequest');
	
				echo $content ;
			}
			die();
		}

		public function scheduleShowing() {
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderAjaxHandler.scheduleShowing');
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();
			
			$isSpam=$this->isSpam() ;
			if( $isSpam == false  ){
				
				$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() ;
				$ihfUrl .= '?method=handleRequest&viewType=json&requestType=schedule-showing';
				$ihfUrl .= '&authenticationToken=' . $authenticationToken ;	
				
				$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST );	
				//echo ($ihfUrl);
				//die();			
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

			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=photo-tour' ;

			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "listingNumber", $listingNumber);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "boardId", $boardID);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);

			
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

			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=save-property' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
			$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST );	
			
			//echo( $ihfUrl);die();
			
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl, true);
			IHomefinderLogger::getInstance()->debugDumpVar($contentInfo);
			
			$content = IHomefinderRequestor::getContent($contentInfo);
			echo $content ;
			die();
		}

		public function saveSearch(){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderAjaxHandler.saveSearch');
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();				
			
			$name = IHomefinderUtility::getInstance()->getRequestVar('name');
			$actionType = ""  ;
			$method=IHomefinderUtility::getInstance()->getRequestVar('method');

			if( $method == "createNewAccountAndSaveSearch"){
				$actionType="newaccount";
			}

			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=save-search' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "actionType", $actionType);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "subscriberName", $name);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "modal", "true");
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
			
			$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST );
			
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
			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=advanced-search-multi-select-values' ;
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
			
			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=advanced-search-fields' ;
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
			
			
			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=lead-capture-login' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken );
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "phpStyle", "true" );
			
			$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST );	
			
			if( $_REQUEST['leadCaptureId'] == null ){
				$leadCaptureId=IHomefinderStateManager::getInstance()->getLeadCaptureId() ;
				$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "leadCaptureId", $leadCaptureId );
			}
						
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl, true);
			$content = IHomefinderRequestor::getContent( $contentInfo );
			echo $content ;
			IHomefinderLogger::getInstance()->debug('End leadCaptureLogin');
			die();
		}
		public function addSavedListingComments(){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderAjaxHandler.addSavedListingComments');
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();
		
			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=saved-listing-comments' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
			$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST );
		
			//echo( $ihfUrl);die();
		
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl, true);
			//IHomefinderLogger::getInstance()->debugDumpVar($contentInfo);
		
			$content = IHomefinderRequestor::getContent($contentInfo);
			//We do not need to get any content back
			//echo $content ;
			IHomefinderLogger::getInstance()->debug('End addSavedListingComments');
			die();
		
		}
		
		public function addSavedListingRating(){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderAjaxHandler.addSavedListingRating');
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();
		
			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=saved-listing-rating' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
			$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST );
		
			///echo( $ihfUrl);die();
		
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl, true);
			IHomefinderLogger::getInstance()->debugDumpVar($contentInfo);
		
			$content = IHomefinderRequestor::getContent($contentInfo);
			//We do not need to get any content back
			//echo $content ;
			IHomefinderLogger::getInstance()->debug('End addSavedListingRating');
			die();
		
		
		}

		public function saveListingForSubscriberInSession(){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderAjaxHandler.saveListingForSubscriberInsession');
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();
		
			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=save-listing-subscriber-session' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
			$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST );
		
			//echo( $ihfUrl);die();
		
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl, true);
			IHomefinderLogger::getInstance()->debugDumpVar($contentInfo);
		
			$content = IHomefinderRequestor::getContent($contentInfo);
			echo $content ;
			die();
		
		}
		
		public function saveSearchForSubscriberInSession(){
		
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderAjaxHandler.saveSearchForSubscriberInSession');
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();
		
			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=save-search-subscriber-session' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
			$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST );
		
			//echo( $ihfUrl);die();
		
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl, true);
			IHomefinderLogger::getInstance()->debugDumpVar($contentInfo);
		
			$content = IHomefinderRequestor::getContent($contentInfo);
			echo $content ;
			die();
		}
		
		public function getAutocompleteMatches(){
		
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderAjaxHandler.getAutocompleteMatches');
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();
		
			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=area-autocomplete' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
			$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST );
		
			//echo( $ihfUrl);die();
		
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl, true);
			IHomefinderLogger::getInstance()->debugDumpVar($contentInfo);
		
			$json = IHomefinderRequestor::getJson($contentInfo);
			echo $json ;
			die();	
		}
	}//end class
}//end ifclass_exists
?>