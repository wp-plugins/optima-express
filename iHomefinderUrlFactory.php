<?php
if( !class_exists('IHomefinderUrlFactory')) {
	/**
	 * Singleton class that provides convenience methods for building plugin URLs
	 * 
	 * @author ihomefinder
	 */
	class IHomefinderUrlFactory {

		private $baseUrl=null  ;
		
		private static $instance ;
		private $virtualPageFactory ;

		private function __construct(){
			$this->virtualPageFactory=IHomefinderVirtualPageFactory::getInstance() ;
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

		private function prependBaseUrl($path, $includeBaseUrl ){
			if( $includeBaseUrl ){
				$path = $this->getBaseUrl() . "/" . $path ."/";
			}
			return $path;
		}

		public function getListingsSearchResultsUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::LISTING_SEARCH_RESULTS );
			$path=$virtualPage->getPath();
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );
			return 	$value ;
		}

		public function getListingsSearchFormUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::LISTING_SEARCH_FORM );
			$path=$virtualPage->getPath();
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );
			return 	$value ;
		}
		
		public function getListingsAdvancedSearchFormUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::LISTING_ADVANCED_SEARCH_FORM );
			$path=$virtualPage->getPath();	
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );
			return 	$value ;
		}
		
		public function getListingDetailUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::LISTING_DETAIL );
			$path=$virtualPage->getPath();				
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );
			return 	$value ;
		}
		
		public function getListingSoldDetailUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::LISTING_SOLD_DETAIL );
			$path=$virtualPage->getPath();				
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );
			return 	$value ;
		}		


		public function getFeaturedSearchResultsUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::FEATURED_SEARCH );
			$path=$virtualPage->getPath();				
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );			
			return 	$value ;
		}	

		public function getHotsheetSearchResultsUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::HOTSHEET_SEARCH_RESULTS);
			$path=$virtualPage->getPath();					
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );			
			return 	$value ;
		}	
		
		public function getHotsheetListUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::HOTSHEET_LIST);
			$path=$virtualPage->getPath();					
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );			
			return 	$value ;
		}			

		public function getOrganizerLoginUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::ORGANIZER_LOGIN);
			$path=$virtualPage->getPath();							
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );			
			return 	$value ;
		}
		public function getOrganizerLogoutUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::ORGANIZER_LOGOUT);
			$path=$virtualPage->getPath();										
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );			
			return 	$value ;
		}
		public function getOrganizerLoginSubmitUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::ORGANIZER_LOGIN_SUBMIT);
			$path=$virtualPage->getPath();				
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );			
			return 	$value ;
		}
		public function getOrganizerEditSavedSearchUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::ORGANIZER_EDIT_SAVED_SEARCH);
			$path=$virtualPage->getPath();				
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );			
			return 	$value ;
		}
		public function getOrganizerEditSavedSearchSubmitUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::ORGANIZER_EDIT_SAVED_SEARCH_SUBMIT);
			$path=$virtualPage->getPath();				
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );			
			return 	$value ;
		}
		public function getOrganizerDeleteSavedSearchSubmitUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::ORGANIZER_DELETE_SAVED_SEARCH_SUBMIT);
			$path=$virtualPage->getPath();				
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );			
			return 	$value ;
		}
		public function getOrganizerViewSavedSearchUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::ORGANIZER_VIEW_SAVED_SEARCH);
			$path=$virtualPage->getPath();							
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );			
			return 	$value ;
		}		
		public function getOrganizerViewSavedSearchListUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::ORGANIZER_VIEW_SAVED_SEARCH_LIST);
			$path=$virtualPage->getPath();				
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );			
			return 	$value ;
		}
		public function getOrganizerResendConfirmationEmailUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::ORGANIZER_RESEND_CONFIRMATION_EMAIL);
			$path=$virtualPage->getPath();							
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );			
			return 	$value ;
		}
		public function getOrganizerActivateSubscriberUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::ORGANIZER_ACTIVATE_SUBSCRIBER);
			$path=$virtualPage->getPath();						
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );			
			return 	$value ;
		}
		public function getOrganizerSendSubscriberPasswordUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::ORGANIZER_SEND_SUBSCRIBER_PASSWORD);
			$path=$virtualPage->getPath();				
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );			
			return 	$value ;
		}		
		public function getOrganizerViewSavedListingListUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::ORGANIZER_VIEW_SAVED_LISTING_LIST);
			$path=$virtualPage->getPath();				
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );			
			return 	$value ;			
		}

		public function getOrganizerDeleteSavedListingUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::ORGANIZER_DELETE_SAVED_LISTING_SUBMIT);
			$path=$virtualPage->getPath();				
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );			
			return 	$value ;			
		}
		
		public function getOrganizerEmailUpdatesConfirmationUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::ORGANIZER_EMAIL_UPDATES_CONFIRMATION);
			$path=$virtualPage->getPath();				
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );
			return 	$value ;			
		}
		
		public function getOrganizerHelpUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::ORGANIZER_HELP );
			$path=$virtualPage->getPath();				
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );
			return 	$value ;			
		}		

		public function getOrganizerEditSubscriberUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::ORGANIZER_EDIT_SUBSCRIBER );
			$path=$virtualPage->getPath();				
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );
			return 	$value ;			
		}		

		public function getContactFormUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::CONTACT_FORM );
			$path=$virtualPage->getPath();				
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );
			return 	$value ;			
		}		

		public function getValuationFormUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::VALUATION_FORM );
			$path=$virtualPage->getPath();				
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );
			return 	$value ;			
		}		

		public function getOpenHomeSearchFormUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::OPEN_HOME_SEARCH_FORM);
			$path=$virtualPage->getPath();				
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );
			return 	$value ;			
		}		
		
		public function getSoldFeaturedListingUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::SOLD_FEATURED_LISTING );
			$path=$virtualPage->getPath();				
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );
			return 	$value ;			
		}		
		
		public function getSupplementalListingUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::SUPPLEMENTAL_LISTING );
			$path=$virtualPage->getPath();				
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );
			return 	$value ;			
		}	

		public function getOfficeListUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::OFFICE_LIST );
			$path=$virtualPage->getPath();				
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );
			return 	$value ;			
		}			

		public function getOfficeDetailUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::OFFICE_DETAIL );
			$path=$virtualPage->getPath();				
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );
			return 	$value ;			
		}		

		public function getAgentListUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::AGENT_LIST );
			$path=$virtualPage->getPath();				
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );
			return 	$value ;			
		}			

		public function getAgentDetailUrl($includeBaseUrl=true){
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::AGENT_DETAIL );
			$path=$virtualPage->getPath();				
			$value = $this->prependBaseUrl( $path, $includeBaseUrl );
			return 	$value ;			
		}				
		
	}//end class
}
?>