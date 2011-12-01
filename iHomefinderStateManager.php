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
		private $lastSearchName = "ihf_last_search";
		//subscriber information
		private $subcriberInfoName = "ihf_subscriber_info";
		//lead capture id
		private $leadCaptureIdName = "ihf_lead_capture_id";
		//summary of search results
		private $searchSummaryName = "ihf_search_summary";
		private $cache_timeout=	86400 ;	//Number of seconds for transient to timeout 60*60*24= 86400 = 1 day

		private $searchContext = false;

		private function __construct(){

		}

		public static function getInstance(){
			if( !isset(self::$instance)){
				self::$instance = new IHomefinderStateManager();
			}
			return self::$instance;
		}

		public function initialize(){
			if( array_key_exists($this->identifierCookieName, $_COOKIE )){
				$this->uniqueId = $_COOKIE[$this->identifierCookieName];
			}

			//IHomefinderLogger::getInstance()->debug("uniqueId: " . $this->uniqueId);
			if( empty($this->uniqueId) ){
				$this->uniqueId= uniqid();
				$expireTime=time()+60*60*24*365*5 ; /* expire in 5 years */
				setcookie($this->identifierCookieName, $this->uniqueId, $expireTime, "/");
			}
		}


		/**
		 * The uniqueKey is used to help identify any transients or options for the
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

		public function getLeadCaptureId(){
			$leadCaptureId=get_option( $this->getLeadCaptureKey() );
			return $leadCaptureId ;
		}

		public function saveLeadCaptureId( $leadCaptureId ){
			$optionKey=$this->getLeadCaptureKey();
			update_option($optionKey, $leadCaptureId );
		}

		/**
		 * Save the search query for the listing search results page.
		 * This function fires for each request, so we need to make
		 * sure the requested URL is the search URL
		 */
		public function saveLastSearch(){

			$host=$_SERVER['HTTP_HOST'];
			$requestUri=$_SERVER['REQUEST_URI'];

			$cacheKey=$this->getLastSearchKey() ;
			//IHomefinderLogger::getInstance()->debug("saveLastSearch cacheKey: " . $cacheKey);
			$lastSearch = "http://" . $host . $requestUri  ;

			$lastSearch = str_replace("newSearch=true&", "", $lastSearch);
			//setcookie($this->lastSearchCookie, $searchUrl, time()+3600);  /* expire in 1 hour */
			//IHomefinderLogger::getInstance()->debug("setLastSearchUrl: " .$lastSearchUrl);
			set_transient($cacheKey, $lastSearch, $this->cache_timeout);
		}

		public function getLastSearch(){
			$cacheKey=$this->getLastSearchKey() ;
			$lastSearch=get_transient($cacheKey);
			return $lastSearch;
		}
                        
		public function getLastSearchQueryString(){
			$queryString="";
			$lastSearch = $this->getLastSearch() ;
			$searchArray = explode('?', $lastSearch) ;
			if( isset($searchArray) && is_array($searchArray) && count( $searchArray ) > 1){
				$queryString=$searchArray[1] ;
			}
			return $queryString ;
		}

		public function getLastSearchQueryArray(){
			$lastSearchQueryString=$this->getLastSearchQueryString() ;
			if( $lastSearchQueryString != null && trim($lastSearchQueryString) != '')
			$lastSearchNameValueArray=explode("&", $lastSearchQueryString);
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
			//echo 'serialized: ' . $serializedSubscriber . '<br/>';
			$cacheKey=$this->getSubscriberInfoKey();
			//echo 'cacheKey: ' . $cacheKey . '<br/>';
			delete_transient($cacheKey);
		}

		public function saveSubscriberLogin( $subscriberInfo ){
			$serializedSubscriber = $subscriberInfo->serializedValue();
			//echo 'serialized: ' . $serializedSubscriber . '<br/>';
			$cacheKey=$this->getSubscriberInfoKey();
			//echo 'cacheKey: ' . $cacheKey . '<br/>';
			IHomefinderLogger::getInstance()->debugDumpVar($serializedSubscriber);
			set_transient($cacheKey, $serializedSubscriber, $this->cache_timeout, "/");
		}

		public function getCurrentSubscriber(){
			$cacheKey=$this->getSubscriberInfoKey();
			$subscriberInfo=null;
			$serializedSubscriber = get_transient($cacheKey);
			if($serializedSubscriber != null ){
				$subscriberInfo = IHomefinderSubscriber::getDeserialized($serializedSubscriber);
				if($subscriberInfo->getId() == null || "" == trim($subscriberInfo->getId())){
					$subscriberInfo=null;
				}
			}

			//echo 'subscriberInfo' . $subscriberInfo->serializedValue() .'<br/>'';
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
			$cacheKey=$this->getSearchSummaryKey() ;
			$result = get_transient($cacheKey );
			return $result ;
		}		
		
		public function saveSearchSummary( $searchSummary){
			$searchSummaryArray=(array) $searchSummary ;
			$cacheKey=$this->getSearchSummaryKey() ;
			set_transient($cacheKey, $searchSummaryArray, $this->cache_timeout);
		}

	}//end class
}// end if class_exists

?>