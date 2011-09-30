<?php
if( !class_exists('IHomefinderUrlFactory')) {
	/**
	 * @author ihomefinder
	 */
	class IHomefinderUrlFactory {

		private $baseUrl=null  ;
		private $listingsSearchResultsUrl="homes-for-sale-results";
		private $listingsAdvancedSearchFormUrl="homes-for-sale-search-advanced";
		private $listingsSearchFormUrl="homes-for-sale-search";
		private $listingDetailUrl ="homes-for-sale-details";
		private $featuredSearchResultsUrl ="homes-for-sale-featured";
		private $hotseetSearchResultsUrl="homes-for-sale-toppicks";
		//Form to login or create a new account
		private $organizerLoginUrl="property-organizer-login";
		private $organizerLogoutUrl="property-organizer-logout";
		private $organizerLoginSubmitUrl="property-organizer-login-submit";
		//Display a list of saved searches.  User must be a logged in subscriber to view
		private $organizerSavedSearchesUrl="property-organizer-saved-searches";
		//This will have a search form, where one can alter search criteria
		private $organizerEditSavedSearchUrl="email-alerts";
		private $organizerEditSavedSearchSubmitUrl ="property-organizer-edit-saved-search-submit";
		//This will have a search form, where one can alter search criteria
		private $organizerDeleteSavedSearchSubmitUrl="property-organizer-delete-saved-search-submit";
		//This will display the results of a saved search
		private $organizerViewSavedSearchUrl="property-organizer-view-saved-search";
		private $organizerViewSavedSearchListUrl="property-organizer-view-saved-search-list";
		private $organizerResendConfirmationEmailUrl ="property-organizer-resend-confirmation-email";
		private $organizerActivateSubscriberUrl= "property-organizer-activate";
		private $organizerSendSubscriberPasswordUrl="property-organizer-send-login";
		
		//Organizer Saved Listings
		private $organizerViewSavedListingListUrl="property-organizer-saved-listings";	
		private $organizerEmailUpdatesConfirmationUrl="email-updates-confirmation";
		
		private static $instance ;

		private function __construct(){
		}

		public static function getInstance(){
			if( !isset(self::$instance)){
				self::$instance = new IHomefinderUrlFactory();
			}
			return self::$instance;
		}

		/**
		 *
		 * Gets the base URL for this blog
		 */
		public function getBaseUrl(){
			if( $this->baseUrl == null ){
				$baseUrl = site_url();
				//if almost pretty permalinks are used then alter the baseUrl to include
				$permalinkStructure= get_option('permalink_structure');
				$thePosition=strpos( $permalinkStructure, 'index.php');
				if( $thePosition > -1 ){
					$currentBlogAddress = $currentBlogAddress . '/index.php' ;
				}
			}
			return $baseUrl ;
		}

		/**
		 * This is a Wordpress standard for AJAX handling.
		 */
		public function getAjaxBaseUrl(){
			$currentBlogAddress = site_url();
			return $currentBlogAddress . '/wp-admin/admin-ajax.php';
		}

		private function prependBaseUrl($value, $includeBaseUrl ){
			if( $includeBaseUrl ){
				$value = $this->getBaseUrl() . "/" . $value ."/";
			}
			return $value;
		}

		public function getListingsSearchResultsUrl($includeBaseUrl=true){
			$value = $this->prependBaseUrl( $this->listingsSearchResultsUrl, $includeBaseUrl );
			return 	$value ;
		}

		public function getListingsSearchFormUrl($includeBaseUrl=true){
			$value = $this->prependBaseUrl( $this->listingsSearchFormUrl, $includeBaseUrl );
			return 	$value ;
		}

		public function getListingsAdvancedSearchFormUrl($includeBaseUrl=true){
			$value = $this->prependBaseUrl( $this->listingsAdvancedSearchFormUrl, $includeBaseUrl );
			return 	$value ;
		}
		
		public function getListingDetailUrl($includeBaseUrl=true){
			$value = $this->prependBaseUrl( $this->listingDetailUrl, $includeBaseUrl );
			return 	$value ;
		}

		public function getFeaturedSearchResultsUrl($includeBaseUrl=true){
			$value = $this->prependBaseUrl( $this->featuredSearchResultsUrl, $includeBaseUrl );
			return 	$value ;
		}

		public function getHotsheetSearchResultsUrl($includeBaseUrl=true){
			$value = $this->prependBaseUrl( $this->hotseetSearchResultsUrl, $includeBaseUrl );
			return 	$value ;
		}

		public function getOrganizerLoginUrl($includeBaseUrl=true){
			$value = $this->prependBaseUrl( $this->organizerLoginUrl, $includeBaseUrl );
			return 	$value ;
		}
		public function getOrganizerLogoutUrl($includeBaseUrl=true){
			$value = $this->prependBaseUrl( $this->organizerLogoutUrl, $includeBaseUrl );
			return 	$value ;
		}
		public function getOrganizerLoginSubmitUrl($includeBaseUrl=true){
			$value = $this->prependBaseUrl( $this->organizerLoginSubmitUrl, $includeBaseUrl );
			return 	$value ;
		}
		public function getOrganizerSavedSearchesUrl($includeBaseUrl=true){
			$value = $this->prependBaseUrl( $this->organizerSavedSearchesUrl, $includeBaseUrl );
			return 	$value ;
		}
		public function getOrganizerEditSavedSearchUrl($includeBaseUrl=true){
			$value = $this->prependBaseUrl( $this->organizerEditSavedSearchUrl, $includeBaseUrl );
			return 	$value ;
		}
		public function getOrganizerEditSavedSearchSubmitUrl($includeBaseUrl=true){
			$value = $this->prependBaseUrl( $this->organizerEditSavedSearchSubmitUrl, $includeBaseUrl );
			return 	$value ;
		}
		public function getOrganizerDeleteSavedSearchSubmitUrl($includeBaseUrl=true){
			$value = $this->prependBaseUrl( $this->organizerDeleteSavedSearchSubmitUrl, $includeBaseUrl );
			return 	$value ;
		}
		public function getOrganizerViewSavedSearchUrl($includeBaseUrl=true){
			$value = $this->prependBaseUrl( $this->organizerViewSavedSearchUrl, $includeBaseUrl );
			return 	$value ;
		}		
		public function getOrganizerViewSavedSearchListUrl($includeBaseUrl=true){
			$value = $this->prependBaseUrl( $this->organizerViewSavedSearchListUrl, $includeBaseUrl );
			return 	$value ;
		}
		public function getOrganizerResendConfirmationEmailUrl($includeBaseUrl=true){
			$value = $this->prependBaseUrl( $this->organizerResendConfirmationEmailUrl, $includeBaseUrl );
			return 	$value ;
		}
		public function getOrganizerActivateSubscriberUrl($includeBaseUrl=true){
			$value = $this->prependBaseUrl( $this->organizerActivateSubscriberUrl, $includeBaseUrl );
			return 	$value ;
		}
		public function getOrganizerSendSubscriberPasswordUrl($includeBaseUrl=true){
			$value = $this->prependBaseUrl( $this->organizerSendSubscriberPasswordUrl, $includeBaseUrl );
			return 	$value ;
		}		
		public function getOrganizerViewSavedListingListUrl($includeBaseUrl=true){
			$value = $this->prependBaseUrl( $this->organizerViewSavedListingListUrl, $includeBaseUrl );
			return 	$value ;			
		}
		
		public function getOrganizerEmailUpdatesConfirmationUrl($includeBaseUrl=true){
			$value = $this->prependBaseUrl( $this->organizerEmailUpdatesConfirmationUrl, $includeBaseUrl );
			return 	$value ;			
		}

	}//end class
}
?>