<?php
if( !class_exists('IHomefinderStateManager')) {

	/**
	 * Uses a cookie to uniquely identify users.  The
	 * cookie id is used to store transient data about
	 * the user's state, such as subscriber info and
	 * last search url.
	 *
	 * @author ihomefinder
	 */
	class IHomefinderStateManager {

		private static $instance ;
		private $uniqueId = null;
		private $identifierCookieName = "ihf_identifier";
			
		//url used for last search
		//stored in a cookie
		private $lastSearchName = "ihf_last_search";
		
		//subscriber information
		//stored as a transient
		private $subcriberInfoName = "ihf_subscriber_info";

		//lead capture id
		//stored as an option
		private $leadCaptureIdName = "ihf_lead_capture_id";
		private $leadCaptureId = null ;
		
		//ihf session id
		private $ihfSessionIdName = "ihf_session_id";
		private $ihfSessionId = null;

		//summary of search results
		//stored in the session
		private $searchSummaryName = "ihf_search_summary";
		private $transientTimeout=86400;

		private $searchContext = false;
		
		private $webCrawler=false;
		
		
		//Save the current listing information
		//May be used in a widget like More Info Widget
		private $listingInfo=null;
		
		//We have this variable here in case a client cannot
		//use sessions.  If we set this to false, we can save
		//session information as a transient, but this causes
		//the database to grow quickly.
		private $sessionsEnabled=true;

		private function __construct(){

		}

		public static function getInstance(){
			if( !isset(self::$instance)){
				self::$instance = new IHomefinderStateManager();
			}
			return self::$instance;
		}

		public function initialize(){
			
			session_start();
			
			if( array_key_exists($this->identifierCookieName, $_COOKIE )){
				$this->uniqueId = $_COOKIE[$this->identifierCookieName];
			}

			//IHomefinderLogger::getInstance()->debug("uniqueId: " . $this->uniqueId);
			$isWebCrawler=IHomefinderUtility::getInstance()->isWebCrawler();
			if( empty($this->uniqueId) && !$this->isWebCrawler() ){
				$this->uniqueId= uniqid();
				$expireTime=time()+60*60*24*365*5 ; /* expire in 5 years */
				setcookie($this->identifierCookieName, $this->uniqueId, $expireTime, "/");
			}
						
			/**
			 * We want to store the lead capture information in the cookie, to keep the 
			 * size of wp_options down to a reasonable size.  When we first get the
			 * lead capture id, we cannot set the cookie, b/c headers have already 
			 * been sent.  As a result, we temporarily store the leadCaptureId as
			 * a transient.  If we cannot get the lead capture id from a cookie, then
			 * look for the transient.  If the transient is found, then store the
			 * value as a cookie and delete the transient.
			 */
			if( array_key_exists($this->getLeadCaptureKey(), $_COOKIE )){
				$this->leadCaptureId = $_COOKIE[$this->getLeadCaptureKey()];
			}
			else{
				$this->leadCaptureId = $this->getLeadCaptureId();
				if( $this->leadCaptureId != null ){
					$expireTime=time()+60*60*24*365*5 ; /* expire in 5 years */
					setcookie($this->getLeadCaptureKey(), $this->leadCaptureId, $expireTime, "/");
				}
			}		
		}
		
		private function isSessionsEnabled(){
			return isset( $_SESSION ) && $this->sessionsEnabled  ;			
		}
		
		private function isWebCrawler(){
			return $this->webCrawler ;
		}
		
		private function getStateValue( $cacheKey ){
			$value='';
			if( $this->isSessionsEnabled() ){
				if( array_key_exists($cacheKey, $_SESSION )){
					$value=$_SESSION[$cacheKey];
				}	
			}else{
				$value=get_transient($cacheKey);
			}
			return $value ;
		}
		
		private function saveStateValue( $cacheKey, $value ){
			if( $this->isSessionsEnabled() ){
				$_SESSION[$cacheKey]=$value;
			}else{
				set_transient($cacheKey, $value ,$this->transientTimeout );
			}
		}		

		/**
		 * The uniqueKey is used to help identify any transients / options for the
		 * given user.  This helps to remember previous searches, subscribier info,
		 * and lead capture info.
		 */
		private function getUniqueKey(){
			return $this->uniqueId ;
		}

		/**
		 * transient key used to remember the last search
		 */
		private function getLastSearchKey(){
			$cacheKey = $this->lastSearchName . "_" . $this->getUniqueKey() ;
			return $cacheKey ;
		}

		/**
		 * transient key used to remember a subscriber.
		 */
		private function getSubscriberInfoKey(){
			$cacheKey = $this->subcriberInfoName . "_". $this->getUniqueKey() ;
			return $cacheKey ;
		}

		private function getSearchSummaryKey(){
			$cacheKey = $this->searchSummaryName . "_". $this->getUniqueKey() ;
			return $cacheKey ;
		}


		/**
		 * If we are in the search context (like a search form or advanced search form,
		 * the we do not want to display certain widgets - for example the search widget
		 */
		public function isSearchContext(){
			return $this->searchContext ;
		}

		public function setSearchContext( $value ) {
			$this->searchContext=$value ;
		}

		/**
		 * The lead capture id is used to synchronize lead capture status with
		 * iHomefinder server side lead status.
		 */
		private function getLeadCaptureKey(){
			$cacheKey = $this->leadCaptureIdName . "_" . $this->getUniqueKey() ;
			return $cacheKey ;
		}

			/**
		 * The lead capture id is used to synchronize lead capture status with
		 * iHomefinder server side lead status.
		 */
		private function getIhfSessionKey(){
			$cacheKey = $this->ihfSessionIdName . "_" . $this->getUniqueKey() ;
			return $cacheKey ;
		}		
		/**
		 * If leadCaptureId is set, then we have retrieved it from a cookie
		 * value in the initialize method.  If it is not set, try to get it
		 * from a transient variable.  We temporarily store the leadCaptureId
		 * in a session variable in our first request, because we can no
		 * longer set the value a a cookie.
		 */
		public function getLeadCaptureId(){
			if( $this->leadCaptureId == null ){
				if( !$this->isWebCrawler() ){
					$cacheKey=$this->getLeadCaptureKey();
					if( $this->isSessionsEnabled() ){
						$this->leadCaptureId=$_SESSION[$cacheKey];	
					}else{
						$this->leadCaptureId=get_transient($cacheKey);
					}
					
				}
			}
			return $this->leadCaptureId ;
		}

		/**
		 * Store as a session variable or transient.
		 * 
		 * @param unknown_type $leadCaptureId
		 */
		public function saveLeadCaptureId( $leadCaptureId ){
			if( !$this->isWebCrawler()){
				$cacheKey=$this->getLeadCaptureKey();
				$this->saveStateValue($cacheKey, (string) $leadCaptureId );
			}
		}
		
		public function getIhfSessionId( ){
			if($this->ihfSessionId == null ){				
				if( !$this->isWebCrawler() ){
					$cacheKey=$this->getIhfSessionKey();
					$this->ihfSessionId=$this->getStateValue( $cacheKey );
				}
			}
			return $this->ihfSessionId ;
		}	
			
		/**
		 * Store as a session variable or transient.
		 * 
		 * @param unknown_type $ihfSessionId
		 */		
		public function saveIhfSessionId( $ihfSessionId ){
			if( !$this->isWebCrawler()){
				$cacheKey=$this->getIhfSessionKey();
				$this->saveStateValue($cacheKey, (string)  $ihfSessionId );
			}
		}
		
		public function getCurrentUrl(){
			$currentUrl="";
			if( !$this->isWebCrawler()){
				$host=$_SERVER['HTTP_HOST'];
				$requestUri=$_SERVER['REQUEST_URI'];	
				$currentUrl = "http://" . $host . $requestUri  ;	
			}
			return $currentUrl ;	
		}

		/**
		 * Save the search query for the listing search results page.
		 * This function fires for each request, so we need to make
		 * sure the requested URL is the search URL
		 */
		public function saveLastSearch(){
			if( !$this->isWebCrawler()){
				$lastSearch=$this->getCurrentUrl() ;
				$lastSearch = str_replace("newSearch=true&", "", $lastSearch);
				$cacheKey=$this->getLastSearchKey();
				$this->saveStateValue($cacheKey, $lastSearch);
			}
			return ;
		}
		

		public function getLastSearch(){
			$lastSearch="";
			if( !$this->isWebCrawler()){
				$cacheKey=$this->getLastSearchKey() ;
				$lastSearch = $this->getStateValue($cacheKey);
			}
			return $lastSearch;
		}

		public function getLastSearchQueryString(){
			$queryString="";
			if( !$this->isWebCrawler()){
				$lastSearch = $this->getLastSearch() ;
				$searchArray = explode('?', $lastSearch) ;
				if( isset($searchArray) && is_array($searchArray) && count( $searchArray ) > 1){
					$queryString=$searchArray[1] ;
				}
			}
			return $queryString ;
		}

		public function getLastSearchQueryArray(){
			$lastSearchQueryString=$this->getLastSearchQueryString() ;
			if( $lastSearchQueryString != null && trim($lastSearchQueryString) != ''){
				$lastSearchNameValueArray=explode("&", $lastSearchQueryString);	
			}
						
			$lastSearchArray=array();
			if( isset($lastSearchNameValueArray ) && count( $lastSearchNameValueArray ) > 0 ){
				foreach ($lastSearchNameValueArray as $value) {
					$nameValue=explode("=", $value);
					if( count( $nameValue) == 2){
						$lastSearchArray[$nameValue[0]]= $nameValue[1];
					}
				}
			}
			return $lastSearchArray ;
		}

		public function deleteSubscriberLogin( ){
			$cacheKey=$this->getSubscriberInfoKey();
			if( $this->isSessionsEnabled() ){
				if( array_key_exists($cacheKey, $_SESSION )){
					$_SESSION[$cacheKey]=null;	
				}
			}else{
				delete_transient($cacheKey);
			}
			
		}

		public function saveSubscriberLogin( $subscriberInfo ){
			if( !$this->isWebCrawler() ){
				IHomefinderLogger::getInstance()->debugDumpVar($subscriberInfo);
				
				$cacheKey=$this->getSubscriberInfoKey();
				$this->saveStateValue($cacheKey, $subscriberInfo);
			}
		}

		public function getCurrentSubscriber(){
			$cacheKey=$this->getSubscriberInfoKey();
			if( !$this->isWebCrawler()){
				$subscriberInfo=$this->getStateValue($cacheKey);

				if( !is_null($subscriberInfo) && $subscriberInfo != false ){
					if($subscriberInfo->getId() == null || "" == trim($subscriberInfo->getId())){
						$subscriberInfo=null;
					}
				}
			}

			return $subscriberInfo ;
		}

		public function isLoggedIn(){
			$result=false;
			if( $this->getCurrentSubscriber() != null ){
				$result = true ;
			}
			return $result ;
		}

		public function getSearchSummary(){
			$result=array();
			$cacheKey=$this->getSearchSummaryKey() ;
			$result=$this->getStateValue($cacheKey);
			return $result ;
		}

		public function saveSearchSummary( $searchSummary){
			
			if( !$this->isWebCrawler() ){
				$searchSummaryArray=(array) $searchSummary ;				
				$cacheKey=$this->getSearchSummaryKey() ;
				$this->saveStateValue($cacheKey, $searchSummaryArray);
			}
		}
		
		public function setCurrentListingInfo($listingInfo ){
			$this->listingInfo=$listingInfo ;
		}

		public function getCurrentListingInfo(){
			return $this->listingInfo ;
		}
		
		public function hasListingInfo(){
			if( $this->listingInfo != null ){
				return true;
			}
			return false ;
		}
		
	}//end class
}// end if class_exists

?>