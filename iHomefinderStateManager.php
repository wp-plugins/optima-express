<?php
if( !class_exists('IHomefinderStateManager')) {
	
	class IHomefinderStateManager {
	
		private static $instance ;
		private $uniqueId = null;
		private $identifierCookieName = "ihf_identifier";
		private $lastSearchCookieName = "ihf_last_search";
		private $subcriberInfoCookieName = "ihf_subscriber_info";
		private $cache_timeout=	86400 ;	//Number of seconds for transient to timeout 60*60*24= 86400 = 1 day
		
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
				setcookie($this->identifierCookieName, $this->uniqueId, time()+3600, "/");  /* expire in 1 hour */
			}			
		}
		
		private function getUniqueKey(){
			return $this->uniqueId ;
		}
		
		private function getLastSearchKey(){
			$cacheKey = $this->lastSearchCookieName . "_" . $this->getUniqueKey() ;
			return $cacheKey ;
		}
		
		private function getSubscriberInfoKey(){
			$cacheKey = $this->subcriberInfoCookieName . "_". $this->getUniqueKey() ;
			return $cacheKey ;	
		}
		
		/**
		 * Save the search query for the listing search results page.
		 * This function fires for each request, so we need to make
		 * sure the requested URL is the search URL
		 */
		public function saveLastSearch(){
			
			$requestUri=$_SERVER['REQUEST_URI'];
			$pos = strpos( $requestUri, IHomefinderUrlFactory::getInstance()->getListingsSearchResultsUrl(false) );
			
			if( $pos !== false ){
				$cacheKey=$this->getLastSearchKey() ;
				//IHomefinderLogger::getInstance()->debug("saveLastSearch cacheKey: " . $cacheKey);
				$lastSearchQueryString = $_SERVER["QUERY_STRING"] ;
				//setcookie($this->lastSearchCookie, $searchUrl, time()+3600);  /* expire in 1 hour */
				//IHomefinderLogger::getInstance()->debug("setLastSearchUrl: " .$lastSearchUrl);
				set_transient($cacheKey, $lastSearchQueryString, $this->cache_timeout);							
			}
		}

		public function getLastSearchQueryString(){
			$cacheKey=$this->getLastSearchKey() ;
			$lastSearchQueryString=get_transient($cacheKey);
			return $lastSearchQueryString;
		}		
		
		public function getLastSearchQueryArray(){
			$lastSearchQueryString=$this->getLastSearchQueryString() ;
			if( $lastSearchQueryString != null && trim($lastSearchQueryString) != '')
			$lastSearchNameValueArray=explode("&", $lastSearchQueryString);
			$lastSearchArray=array();
			if( count( $lastSearchNameValueArray ) > 0 ){
				foreach ($lastSearchNameValueArray as $value) {
					$nameValue=explode("=", $value);
					if( count( $nameValue) == 2){
						$lastSearchArray[$nameValue[0]]= $nameValue[1];
					}
				}
			}
			
			return $lastSearchArray ;
		}
		
		public function getLastSearchUrl(){
			$lastSearchUrl=null;
			$lastSearchQueryString=$this->getLastSearchQueryString() ;
			if( $lastSearchQueryString != null && !('' == trim($lastSearchQueryString))){
				$lastSearchUrl = IHomefinderUrlFactory::getInstance()->getListingsSearchResultsUrl(true) . '?' . $lastSearchQueryString ;	
			}
			return $lastSearchUrl;
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
		
	}//end class
}// end if class_exists

?>