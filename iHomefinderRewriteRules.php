<?php
if(!class_exists('IHomefinderRewriteRules')){
	/**
	 * 
	 * Singleton implementation of IHomefinderRewriteRules
	 * 
	 * All iHomefinder requests are directed to the $rootPageName, which tries to load a wordpress page that
	 * does not exist.  We do not want to load a real page.  We get the content from the iHomefinder services
	 * and display it as a virtual Wordpress post.
	 * 
	 * The rewrite rules below set a variable iHomefinderConstants::IHF_TYPE_URL_VAR  that is used to determine which filter retrieves the 
	 * content from iHomefinder
	 * 
	 * @author ihomefinder
	 *
	 */
	class IHomefinderRewriteRules{
		
		private static $instance ;
		private $urlFactory ;
		private $rootPageName ;
		
		private function __construct(){
			$this->urlFactory = IHomefinderUrlFactory::getInstance();
			$this->rootPageName = 'index.php?pagename=non_existent_page';
		}
		
		public static function getInstance(){
			if( !isset(self::$instance)){
				self::$instance = new IHomefinderRewriteRules();
			}
			return self::$instance;
		}
		
		public function initialize(){
			$this->initQueryVariables();
			$this->initRewriteRules();			
		}
		
		public function flushRules(){
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
		}		
		
		private function initQueryVariables(){
			global $wp ;	
			
			//Used for listing search, results and detail
			$wp->add_query_var( iHomefinderConstants::IHF_TYPE_URL_VAR );
	  		$wp->add_query_var('cityName');
	  		$wp->add_query_var('cityId[]');
	  		$wp->add_query_var('ln');
	  		$wp->add_query_var('bid');
	  		$wp->add_query_var('startRowNumber');
	  		$wp->add_query_var('bedrooms');
	  		$wp->add_query_var('bathCount');
	  		$wp->add_query_var('minListPrice');
	  		$wp->add_query_var('maxListPrice');
			$wp->add_query_var('propertyType');
			$wp->add_query_var('propertyCategory');
			$wp->add_query_var('squareFeet');
			$wp->add_query_var('lotAcres');
			$wp->add_query_var('sortBy');
			$wp->add_query_var('cityID');
			$wp->add_query_var('zip');
			$wp->add_query_var('streetName');
			$wp->add_query_var('streetNumber');
			$wp->add_query_var('action');
			$wp->add_query_var('listingNumber');
			$wp->add_query_var('interestLevel');
			$wp->add_query_var('name');
			$wp->add_query_var('fullname');
			$wp->add_query_var('phone');
			$wp->add_query_var('email');
			$wp->add_query_var('password');
			$wp->add_query_var('message');

			$wp->add_query_var('hotSheetId');
			$wp->add_query_var('agentId');
			
			$wp->add_query_var('subscriberName');
			$wp->add_query_var('actionType');
			$wp->add_query_var('subscriberID');
			$wp->add_query_var('searchProfileID');
			$wp->add_query_var('searchProfileName');
			$wp->add_query_var('htmlFormat');
			$wp->add_query_var('sendEmailYN');

			$wp->add_query_var('openHomesOnlyYN');
			$wp->add_query_var('dateRange');
			
			$wp->add_query_var('newSearch');
			$wp->add_query_var('includeMap');
		}
		/**
	 	 * Function to initialize rewrite rules for the IHF plugin.
	 	 *  
	 	 *  During development we initialize and flush the rules often, but
	 	 *  this should only be performed when the plugin is registered.
	 	 *  
	 	 *  We need to map certain URL patters ot an internal ihf page
	 	 *  Once requests are routed to that page, we can handle different
	 	 *  behavior in functions that listen for updates on that page.
	 	 */
		private function initRewriteRules(){
					
			$this->setAllRewriteRules( '');
			// set the rules again, to support almost pretty permalinks
			$this->setAllRewriteRules( 'index.php/');
		}
		
		private function setAllRewriteRules($matchRulePrefix){
			//The order of these search rules is important.
			$this->setListingSearchResultsPageRewriteRules($matchRulePrefix);
	  		$this->setFeaturedSearchPageRewriteRules($matchRulePrefix);
	  		$this->setDetailPageRewriteRules($matchRulePrefix);
	  		$this->setAdvancedSearchRewriteRules($matchRulePrefix);
			$this->setSearchRewriteRules($matchRulePrefix);
			$this->setHotsheetResultsRewriteRules($matchRulePrefix);								
			$this->setOrganizerLoginSubmitRewriteRules($matchRulePrefix);
			$this->setOrganizerLoginRewriteRules($matchRulePrefix);
			$this->setOrganizerLogoutRewriteRules($matchRulePrefix);
			$this->setOrganizerEditSavedSearchSubmitRewriteRules($matchRulePrefix);
			$this->setOrganizerEditSavedSearchRewriteRules($matchRulePrefix);
			$this->setOrganizerDeleteSavedSearchSubmitRewriteRules($matchRulePrefix);
			$this->setOrganizerViewSavedSearchRewriteRules($matchRulePrefix);
			$this->setOrganizerViewSavedSearchListRewriteRules($matchRulePrefix);
			$this->setOrganizerViewSavedListingListRewriteRules($matchRulePrefix);			
			$this->setOrganizerResendConfirmationRewriteRules($matchRulePrefix);	
			$this->setOrganizerReactivateSubscriberRewriteRules($matchRulePrefix);
			$this->setOrganizerResendSubscriberPasswordRewriteRules($matchRulePrefix);
			$this->setOrganizerEmailUpdatesConfirmationRewriteRules($matchRulePrefix);	
		}

		private function setAdvancedSearchRewriteRules($matchRulePrefix){
			global $wp_rewrite;
	  		// matches 'idx-search
	  		$rewriteUrl=$this->rootPageName ;
	  		$rewriteUrl .= '&' . iHomefinderConstants::IHF_TYPE_URL_VAR . '=' . IHomefinderFilterFactory::LISTING_ADVANCED_SEARCH_FORM ;
	  		
	  		$wp_rewrite->add_rule( 
	  			$matchRulePrefix . $this->urlFactory->getListingsAdvancedSearchFormUrl(false) . '/([^/]+)', 
	  			$rewriteUrl . '&bid=$matches[1]', 
	  			'top');
	  			
	  		$wp_rewrite->add_rule( 
	  			$matchRulePrefix . $this->urlFactory->getListingsAdvancedSearchFormUrl(false), 
	  			$rewriteUrl, 
	  			'top');
	  			
		}
		private function setSearchRewriteRules($matchRulePrefix){
			global $wp_rewrite;
	  		// matches 'idx-search
	  		$rewriteUrl=$this->rootPageName ;
	  		$rewriteUrl .= '&' . iHomefinderConstants::IHF_TYPE_URL_VAR . '=' . IHomefinderFilterFactory::LISTING_SEARCH_FORM ;

	  		//echo( 'rewriteUrl='.$rewriteUrl."<br>");
	  		$wp_rewrite->add_rule( 
	  			$matchRulePrefix . $this->urlFactory->getListingsSearchFormUrl(false), 
	  			$rewriteUrl, 
	  			'top');
		}

		private function setOrganizerLoginSubmitRewriteRules($matchRulePrefix ){
			global $wp_rewrite;
	  		// matches 
	  		$rewriteUrl=$this->rootPageName;
	  		$rewriteUrl.='&' . iHomefinderConstants::IHF_TYPE_URL_VAR . '=' . IHomefinderFilterFactory::ORGANIZER_LOGIN_SUBMIT ;
	  		//echo('getOrganizerLoginSubmitUrl=' . $this->urlFactory->getOrganizerLoginSubmitUrl(false)."|");
	  		//echo( 'rewriteUrl='.$rewriteUrl."<br><br>");
	  		$wp_rewrite->add_rule(
	  			$matchRulePrefix . $this->urlFactory->getOrganizerLoginSubmitUrl(false),
	            $rewriteUrl, 
	            'top'
	  		);
		}	
		private function setOrganizerDeleteSavedSearchSubmitRewriteRules($matchRulePrefix ){
			global $wp_rewrite;
	  		// matches 
	  		$wp_rewrite->add_rule(
	  			$matchRulePrefix . $this->urlFactory->getOrganizerDeleteSavedSearchSubmitUrl(false) ,
	            $this->rootPageName . '&' . iHomefinderConstants::IHF_TYPE_URL_VAR . '=' . IHomefinderFilterFactory::ORGANIZER_DELETE_SAVED_SEARCH_SUBMIT , 'top'
	  		);
		}	
				
		private function setOrganizerEditSavedSearchRewriteRules($matchRulePrefix ){
			global $wp_rewrite;
	  		// matches 
	  		$wp_rewrite->add_rule(
	  			$matchRulePrefix . $this->urlFactory->getOrganizerEditSavedSearchUrl(false) ,
	            $this->rootPageName . '&' . iHomefinderConstants::IHF_TYPE_URL_VAR . '=' . IHomefinderFilterFactory::ORGANIZER_EDIT_SAVED_SEARCH , 'top'
	  		);
		}		

		private function setOrganizerEditSavedSearchSubmitRewriteRules($matchRulePrefix ){
			global $wp_rewrite;
	  		// matches 
	  		$wp_rewrite->add_rule(
	  			$matchRulePrefix . $this->urlFactory->getOrganizerEditSavedSearchSubmitUrl(false),
	            $this->rootPageName . '&' . iHomefinderConstants::IHF_TYPE_URL_VAR . '=' . IHomefinderFilterFactory::ORGANIZER_EDIT_SAVED_SEARCH_SUBMIT , 'top'
	  		);
		}						

		private function setOrganizerEmailUpdatesConfirmationRewriteRules($matchRulePrefix ){
			global $wp_rewrite;
	  		// matches 
	  		$wp_rewrite->add_rule(
	  			$matchRulePrefix . $this->urlFactory->getOrganizerEmailUpdatesConfirmationUrl(false),
	            $this->rootPageName . '&' . iHomefinderConstants::IHF_TYPE_URL_VAR . '=' . IHomefinderFilterFactory::ORGANIZER_EMAIL_UPDATES_CONFIRMATION, 'top'
	  		);
		}	
				
		private function setOrganizerLoginRewriteRules($matchRulePrefix ){
			global $wp_rewrite;
	  		// matches 
	  		$wp_rewrite->add_rule(
	  			$matchRulePrefix . $this->urlFactory->getOrganizerLoginUrl(false),
	            $this->rootPageName . '&' . iHomefinderConstants::IHF_TYPE_URL_VAR . '=' . IHomefinderFilterFactory::ORGANIZER_LOGIN , 'top'
	  		);
		}		
		
		private function setOrganizerLogoutRewriteRules($matchRulePrefix ){
			global $wp_rewrite;
	  		// matches 
	  		$wp_rewrite->add_rule(
	  			$matchRulePrefix . $this->urlFactory->getOrganizerLogoutUrl(false),
	            $this->rootPageName . '&' . iHomefinderConstants::IHF_TYPE_URL_VAR . '=' . IHomefinderFilterFactory::ORGANIZER_LOGOUT , 'top'
	  		);
		}				

		private function setOrganizerViewSavedSearchRewriteRules($matchRulePrefix ){
			global $wp_rewrite;
	  		// matches 
	  		$wp_rewrite->add_rule(
	  			$matchRulePrefix .  $this->urlFactory->getOrganizerViewSavedSearchUrl(false) . '/([^/]+)',
	            $this->rootPageName . '&' . iHomefinderConstants::IHF_TYPE_URL_VAR . '=' . IHomefinderFilterFactory::ORGANIZER_VIEW_SAVED_SEARCH .  '&searchProfileID=$matches[1]', 'top'
	  		);
		}
		private function setOrganizerViewSavedSearchListRewriteRules($matchRulePrefix ){
			global $wp_rewrite;
	  		// matches 
	  		$wp_rewrite->add_rule(
	  			$matchRulePrefix .  $this->urlFactory->getOrganizerViewSavedSearchListUrl(false),
	            $this->rootPageName . '&' . iHomefinderConstants::IHF_TYPE_URL_VAR . '=' . IHomefinderFilterFactory::ORGANIZER_VIEW_SAVED_SEARCH_LIST, 'top'
	  		);
		}
		private function setOrganizerViewSavedListingListRewriteRules($matchRulePrefix ){
			global $wp_rewrite;
	  		// matches 
	  		$wp_rewrite->add_rule(
	  			$matchRulePrefix .  $this->urlFactory->getOrganizerViewSavedListingListUrl(false),
	            $this->rootPageName . '&' . iHomefinderConstants::IHF_TYPE_URL_VAR . '=' . IHomefinderFilterFactory::ORGANIZER_VIEW_SAVED_LISTING_LIST, 'top'
	  		);
		}
		private function setOrganizerResendConfirmationRewriteRules($matchRulePrefix ){
			global $wp_rewrite;
	  		// matches 
	  		$wp_rewrite->add_rule(
	  			$matchRulePrefix .  $this->urlFactory->getOrganizerResendConfirmationEmailUrl(false),
	            $this->rootPageName . '&' . iHomefinderConstants::IHF_TYPE_URL_VAR . '=' . IHomefinderFilterFactory::ORGANIZER_RESEND_CONFIRMATION_EMAIL , 'top'
	  		);
		}	
		private function setOrganizerReactivateSubscriberRewriteRules($matchRulePrefix ){
			global $wp_rewrite;
	  		// matches 
	  		$wp_rewrite->add_rule(
	  			$matchRulePrefix .  $this->urlFactory->getOrganizerActivateSubscriberUrl(false),
	            $this->rootPageName . '&' . iHomefinderConstants::IHF_TYPE_URL_VAR . '=' . IHomefinderFilterFactory::ORGANIZER_ACTIVATE_SUBSCRIBER , 'top'
	  		);
		}									

		private function setOrganizerResendSubscriberPasswordRewriteRules($matchRulePrefix ){
			global $wp_rewrite;
	  		// matches 
	  		$wp_rewrite->add_rule(
	  			$matchRulePrefix . $this->urlFactory->getOrganizerSendSubscriberPasswordUrl(false),
	            $this->rootPageName . '&' . iHomefinderConstants::IHF_TYPE_URL_VAR . '=' . IHomefinderFilterFactory::ORGANIZER_SEND_SUBSCRIBER_PASSWORD , 'top'
	  		);
		}									
		
		private function setHotsheetResultsRewriteRules($matchRulePrefix ){
			global $wp_rewrite;
	  		// matches '%hotsheetUrl%/%nameOfHotSheet$/1234', where the hotsheet id =1234
	  		$wp_rewrite->add_rule(
	  			$matchRulePrefix . $this->urlFactory->getHotsheetSearchResultsUrl(false) . '/([^/]+)/([^/]+)',
	            $this->rootPageName . '&' . iHomefinderConstants::IHF_TYPE_URL_VAR . '=' . IHomefinderFilterFactory::HOTSHEET_SEARCH_RESULTS . '&hotSheetId=$matches[2]', 'top'
	  		);	  		
	  		
	  		// matches '%hotsheetUrl%/1234', where the hotsheet id =1234
	  		$wp_rewrite->add_rule(
	  			$matchRulePrefix . $this->urlFactory->getHotsheetSearchResultsUrl(false) . '/([^/]+)',
	            $this->rootPageName . '&' . iHomefinderConstants::IHF_TYPE_URL_VAR . '=' . IHomefinderFilterFactory::HOTSHEET_SEARCH_RESULTS . '&hotSheetId=$matches[1]', 'top'
	  		);
	  		$wp_rewrite->add_rule(
	  			$matchRulePrefix . $this->urlFactory->getHotsheetSearchResultsUrl(false) ,
	            $this->rootPageName . '&' . iHomefinderConstants::IHF_TYPE_URL_VAR . '=' . IHomefinderFilterFactory::HOTSHEET_LIST . '&hotSheetId=$matches[1]', 'top'
	  		);
	  		
		}		
		
		private function setDetailPageRewriteRules($matchRulePrefix ){
			global $wp_rewrite;
	  		// matches 'idx-detail/%address%/12345678/1234', where the listing number =12345678 and bid=1234
	  		$wp_rewrite->add_rule(
	  			$matchRulePrefix . $this->urlFactory->getListingDetailUrl(false) . '/([^/]+)/([^/]+)/([^/]+)',
	            $this->rootPageName . '&' . iHomefinderConstants::IHF_TYPE_URL_VAR . '=' . IHomefinderFilterFactory::LISTING_DETAIL . '&ln=$matches[2]&bid=$matches[3]', 'top'
	  		);
		}
		private function setListingSearchResultsPageRewriteRules($matchRulePrefix ){
	  		global $wp_rewrite;
			// 'idx-results/Berkeley/SFR/3/2/11', where the city=Berkeley, propertyType=SFR, bedrooms=3, bathCount=2, startRowNumber=11
	  		$wp_rewrite->add_rule(
	  			$matchRulePrefix . $this->urlFactory->getListingsSearchResultsUrl(false) . '/([^/]+)/([^/]+)/([^/]+)/([^/]+)/([^/]+)',
	            $this->rootPageName . '&' . iHomefinderConstants::IHF_TYPE_URL_VAR . '=' . IHomefinderFilterFactory::LISTING_SEARCH_RESULTS . '&cityName=$matches[1]&propertyType=$matches[2]&bedrooms=$matches[3]&bathCount=$matches[4]&minListPrice=$matches[5]&maxListPrice=$matches[6]&startRowNumber=$matches[7]', 'top'
	  		);
	
	  		// 'homesearch/Berkeley/', where the city=Berkeley, propertyType=SFR, bedrooms=3, bathCount=2, startRowNumber=1
	  		$wp_rewrite->add_rule(
	  			$matchRulePrefix . $this->urlFactory->getListingsSearchResultsUrl(false) . '/([^/]+)/([^/]+)/([^/]+)/([^/]+)',
	            $this->rootPageName . '&' . iHomefinderConstants::IHF_TYPE_URL_VAR . '=' . IHomefinderFilterFactory::LISTING_SEARCH_RESULTS . '&cityName=$matches[1]&propertyType=$matches[2]&bedrooms=$matches[3]&bathCount=$matches[4]&minListPrice=$matches[5]&maxListPrice=$matches[6]&startRowNumber=1', 'top'
	  		);

	  		// 'homesearch/Berkeley/11', where the city=Berkeley, startRowNumber=11
	  		$wp_rewrite->add_rule(
	  			$matchRulePrefix . $this->urlFactory->getListingsSearchResultsUrl(false) . '/([^/]+)/([^/]+)',
	            $this->rootPageName . '&' . iHomefinderConstants::IHF_TYPE_URL_VAR . '=' . IHomefinderFilterFactory::LISTING_SEARCH_RESULTS . '&cityName=$matches[1]&startRowNumber=$matches[2]', 'top'
	  		);
	  		
	  		// 'homesearch/Berkeley/', where the city=Berkeley, startRowNumber=1
	  		$wp_rewrite->add_rule(
	  			$matchRulePrefix . $this->urlFactory->getListingsSearchResultsUrl(false) . '/([^/]+)',
	            $this->rootPageName . '&' . iHomefinderConstants::IHF_TYPE_URL_VAR . '=' . IHomefinderFilterFactory::LISTING_SEARCH_RESULTS . '&cityName=$matches[1]&startRowNumber=1', 'top'
	  		);
	  		// In this case, all of the parameters are either set by POST or GET
	  		// 'homesearch?cityName=Berkeley&propertyType=SFR,CNDbedrooms=5&bathCount=2
	  		$wp_rewrite->add_rule(
	  			$matchRulePrefix . $this->urlFactory->getListingsSearchResultsUrl(false),
	            $this->rootPageName . '&' . iHomefinderConstants::IHF_TYPE_URL_VAR . '=' . IHomefinderFilterFactory::LISTING_SEARCH_RESULTS . '&cityName=$matches[1]&propertyType=$matches[2]&bedrooms=$matches[3]&bathCount=$matches[4]&minListPrice=$matches[5]&maxListPrice=$matches[6]&startRowNumber=1', 'top'
	  		);  
		}
		
		private function setFeaturedSearchPageRewriteRules($matchRulePrefix ){
			global $wp_rewrite ;
	  		
	  		// 'featuredsearch/21/', where startRowNumber=21
	  		$wp_rewrite->add_rule(
	  			$matchRulePrefix . $this->urlFactory->getFeaturedSearchResultsUrl(false) . '/([^/]+)',
	            $this->rootPageName . '&' . iHomefinderConstants::IHF_TYPE_URL_VAR . '=' . IHomefinderFilterFactory::FEATURED_SEARCH . '&startRowNumber=$matches[1]', 'top'
	  		);
	  		
	  		// In this case, all of the parameters are either set by POST or GET
	  		// 'featuredsearch?cityName=Berkeley&propertyType=SFR,CNDbedrooms=5&bathCount=2
	  		$wp_rewrite->add_rule(
	  			$matchRulePrefix . $this->urlFactory->getFeaturedSearchResultsUrl(false) ,
	            $this->rootPageName . '&' . iHomefinderConstants::IHF_TYPE_URL_VAR . '=' . IHomefinderFilterFactory::FEATURED_SEARCH . '&startRowNumber=1', 'top'
	  		);  
		}
	}
}//end if(!class_exists('IHomefinderRewriteRules'))

?>