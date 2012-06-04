<?php
if( !class_exists('IHomefinderVirtualPageFactory')) {
	include_once(   'virtualPage/iHomefinderVirtualPage.php');
	include_once(   'virtualPage/iHomefinderFeaturedSearchVirtualPageImpl.php');
	include_once(   'virtualPage/iHomefinderHotsheetVirtualPageImpl.php');
	include_once(   'virtualPage/iHomefinderHotsheetListVirtualPageImpl.php');
	
	include_once(   'virtualPage/iHomefinderAdvancedSearchFormVirtualPageImpl.php');
	include_once(   'virtualPage/iHomefinderSearchFormVirtualPageImpl.php');
	include_once(   'virtualPage/iHomefinderQuickSearchFormVirtualPageImpl.php');
	include_once(   'virtualPage/iHomefinderSearchResultsVirtualPageImpl.php');
	include_once(   'virtualPage/iHomefinderListingDetailVirtualPageImpl.php');
	
	include_once(   'virtualPage/iHomefinderOrganizerLoginFormVirtualPageImpl.php');
	include_once(   'virtualPage/iHomefinderOrganizerLogoutVirtualPageImpl.php');	
	include_once(   'virtualPage/iHomefinderOrganizerLoginSubmitVirtualPageImpl.php');
	
	include_once(   'virtualPage/iHomefinderOrganizerEditSavedSearchVirtualPageImpl.php');
	include_once(   'virtualPage/iHomefinderOrganizerEditSavedSearchFormVirtualPageImpl.php');
	include_once(   'virtualPage/iHomefinderOrganizerEmailUpdatesConfirmationVirtualPageImpl.php');	
	include_once(   'virtualPage/iHomefinderOrganizerDeleteSavedSearchVirtualPageImpl.php');
	
	include_once(   'virtualPage/iHomefinderOrganizerViewSavedSearchVirtualPageImpl.php');
	include_once(   'virtualPage/iHomefinderOrganizerViewSavedSearchListVirtualPageImpl.php');

	include_once(   'virtualPage/iHomefinderOrganizerViewSavedListingListVirtualPageImpl.php');
	include_once(   'virtualPage/iHomefinderOrganizerDeleteSavedListingVirtualPageImpl.php');
	include_once(   'virtualPage/iHomefinderOrganizerResendConfirmationVirtualPageImpl.php');
	include_once(   'virtualPage/iHomefinderOrganizerActivateSubscriberVirtualPageImpl.php');
	include_once(   'virtualPage/iHomefinderOrganizerSendSubscriberPasswordVirtualPageImpl.php');	
	include_once(   'virtualPage/iHomefinderOrganizerHelpVirtualPageImpl.php');
	include_once(   'virtualPage/iHomefinderOrganizerEditSubscriberVirtualPageImpl.php');

	/** 
	 * This singleton class creates instances of iHomefinder VirtualPages, based
	 * on a type parameter.
	 * @author ihomefinder
	 */
	class IHomefinderVirtualPageFactory {
	
		private static $instance ;
		
		private function __construct(){
		}
		
		public static function getInstance(){
			if( !isset(self::$instance)){
				self::$instance = new IHomefinderVirtualPageFactory();
			}
			return self::$instance;		
		}
		
		////////////////////////////////////////////////////////
		//Types used to determine the VirtualPage type in iHomefinderVirtualPageFactory.
		const LISTING_SEARCH_RESULTS="idx-results";
		const LISTING_DETAIL="idx-detail";
		const LISTING_SEARCH_FORM="idx-search";
		const LISTING_QUICK_SEARCH_FORM="idx-quick-search";
		const LISTING_ADVANCED_SEARCH_FORM="idx-advanced-search";
		const FEATURED_SEARCH="idx-featured-search";
		const HOTSHEET_SEARCH_RESULTS="idx-toppicks";
		const HOTSHEET_LIST="idx-toppicks-list";
		const ORGANIZER_LOGIN="idx-property-organizer-login";
		const ORGANIZER_LOGOUT="idx-property-organizer-logout";
		const ORGANIZER_LOGIN_SUBMIT="idx-property-organizer-submit-login";
		const ORGANIZER_EDIT_SAVED_SEARCH="idx-property-organizer-edit-saved-search";
		const ORGANIZER_EDIT_SAVED_SEARCH_SUBMIT="idx-property-organizer-edit-saved-search-submit";
		const ORGANIZER_EMAIL_UPDATES_CONFIRMATION="idx-property-organizer-email-updates-success";
		const ORGANIZER_DELETE_SAVED_SEARCH="idx-property-organizer-delete-saved-search";
		const ORGANIZER_DELETE_SAVED_SEARCH_SUBMIT="idx-property-organizer-delete-saved-search-submit";
		const ORGANIZER_VIEW_SAVED_SEARCH="idx-property-organizer-view-saved-search";
		const ORGANIZER_VIEW_SAVED_SEARCH_LIST="idx-property-organizer-view-saved-searches";
		const ORGANIZER_VIEW_SAVED_LISTING_LIST="idx-property-organizer-view-saved-listings";
		const ORGANIZER_DELETE_SAVED_LISTING_SUBMIT="idx-property-organizer-delete-saved-listing";
		const ORGANIZER_RESEND_CONFIRMATION_EMAIL="idx-property-organizer-resend-confirmation-email";
		const ORGANIZER_ACTIVATE_SUBSCRIBER ="idx-property-organizer-activate-subscriber";
		const ORGANIZER_SEND_SUBSCRIBER_PASSWORD="idx-property-organizer-send-login";
		const ORGANIZER_HELP="idx-property-organizer-help";
		const ORGANIZER_EDIT_SUBSCRIBER="idx-property-organizer-edit-subscriber";
		///////////////////////////////////////////////////////		

		public function getVirtualPage( $type ){
			$virtualPage ;
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderVirtualPageFactory.getVirtualPage type=' . $type);
			if($type == IHomefinderVirtualPageFactory::LISTING_SEARCH_RESULTS ){
				$virtualPage = new IHomefinderSearchResultsVirtualPageImpl();
			}	
			else if($type == IHomefinderVirtualPageFactory::LISTING_DETAIL ){
				$virtualPage = new IHomefinderListingDetailVirtualPageImpl();
			}	
			else if( $type == IHomefinderVirtualPageFactory::FEATURED_SEARCH){
				$virtualPage = new IHomefinderFeaturedSearchVirtualPageImpl();
			}
			else if( $type == IHomefinderVirtualPageFactory::LISTING_ADVANCED_SEARCH_FORM){
				$virtualPage = new IHomefinderAdvancedSearchFormVirtualPageImpl();
			}
	    	else if( $type == IHomefinderVirtualPageFactory::LISTING_SEARCH_FORM){
				$virtualPage = new IHomefinderSearchFormVirtualPageImpl();
			}
			else if( $type == IHomefinderVirtualPageFactory::LISTING_QUICK_SEARCH_FORM){
				$virtualPage = new IHomefinderQuickSearchFormVirtualPageImpl();
			}			
			else if( $type == IHomefinderVirtualPageFactory::HOTSHEET_SEARCH_RESULTS ){
				$virtualPage = new IHomefinderHotsheetVirtualPageImpl() ;
			}
			else if( $type == IHomefinderVirtualPageFactory::HOTSHEET_LIST ){
				$virtualPage = new IHomefinderHotsheetListVirtualPageImpl() ;
			}
			else if( $type == IHomefinderVirtualPageFactory::ORGANIZER_LOGIN ){
				$virtualPage = new iHomefinderOrganizerLoginFormVirtualPageImpl() ;
			}
			else if( $type == IHomefinderVirtualPageFactory::ORGANIZER_LOGOUT ){
				$virtualPage = new iHomefinderOrganizerLogoutVirtualPageImpl() ;
			}			
			else if( $type == IHomefinderVirtualPageFactory::ORGANIZER_LOGIN_SUBMIT ){
				$virtualPage = new iHomefinderOrganizerLoginSubmitVirtualPageImpl() ;
			}
			else if( $type == IHomefinderVirtualPageFactory::ORGANIZER_EDIT_SAVED_SEARCH ){
				$virtualPage = new IHomefinderOrganizerEditSavedSearchFormVirtualPageImpl() ;
			}
			else if( $type == IHomefinderVirtualPageFactory::ORGANIZER_EMAIL_UPDATES_CONFIRMATION ){
				$virtualPage = new IHomefinderOrganizerEmailUpdatesConfirmationVirtualPageImpl() ;
			}			
			else if( $type == IHomefinderVirtualPageFactory::ORGANIZER_EDIT_SAVED_SEARCH_SUBMIT ){
				$virtualPage = new IHomefinderOrganizerEditSavedSearchVirtualPageImpl() ;
			}			
			else if( $type == IHomefinderVirtualPageFactory::ORGANIZER_DELETE_SAVED_SEARCH_SUBMIT ){
				$virtualPage = new IHomefinderOrganizerDeleteSavedSearchVirtualPageImpl() ;
			}
			else if( $type == IHomefinderVirtualPageFactory::ORGANIZER_VIEW_SAVED_SEARCH ){
				$virtualPage = new IHomefinderOrganizerViewSavedSearchVirtualPageImpl() ;
			}				
			else if( $type == IHomefinderVirtualPageFactory::ORGANIZER_VIEW_SAVED_SEARCH_LIST ){
				$virtualPage = new IHomefinderOrganizerViewSavedSearchListVirtualPageImpl() ;
			}
			else if( $type == IHomefinderVirtualPageFactory::ORGANIZER_VIEW_SAVED_LISTING_LIST ){
				$virtualPage = new IHomefinderOrganizerViewSavedListingListVirtualPageImpl() ;
			}
			else if( $type == IHomefinderVirtualPageFactory::ORGANIZER_DELETE_SAVED_LISTING_SUBMIT){
				$virtualPage = new iHomefinderOrganizerDeleteSavedListingVirtualPageImpl();
			}
			else if( $type == IHomefinderVirtualPageFactory::ORGANIZER_ACTIVATE_SUBSCRIBER){
				$virtualPage = new iHomefinderOrganizerActivateSubscriberVirtualPageImpl() ;
			}
			else if( $type == IHomefinderVirtualPageFactory::ORGANIZER_RESEND_CONFIRMATION_EMAIL ){
				$virtualPage = new IHomefinderOrganizerResendConfirmationVirtualPageImpl() ;
			}			
			else if( $type == IHomefinderVirtualPageFactory::ORGANIZER_SEND_SUBSCRIBER_PASSWORD ){
				$virtualPage = new iHomefinderOrganizerSendSubscriberPasswordVirtualPageImpl() ;
			}			
			else if( $type == IHomefinderVirtualPageFactory::ORGANIZER_HELP ){
				$virtualPage = new iHomefinderOrganizerHelpVirtualPageImpl() ;
			}			
			else if( $type == IHomefinderVirtualPageFactory::ORGANIZER_EDIT_SUBSCRIBER ){
				$virtualPage = new iHomefinderOrganizerEditSubscriberVirtualPageImpl() ;
			}			
			
			IHomefinderLogger::getInstance()->debug('Complete IHomefinderVirtualPageFactory.getVirtualPage');
			return $virtualPage ;
		}
	}
}
?>