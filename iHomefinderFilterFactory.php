<?php
if( !class_exists('IHomefinderFilterFactory')) {
	include_once(   'filter/iHomefinderFilter.php');
	include_once(   'filter/iHomefinderFeaturedSearchFilterImpl.php');
	include_once(   'filter/iHomefinderHotsheetFilterImpl.php');
	include_once(   'filter/iHomefinderHotsheetListFilterImpl.php');
	
	include_once(   'filter/iHomefinderAdvancedSearchFormFilterImpl.php');
	include_once(   'filter/iHomefinderSearchFormFilterImpl.php');
	include_once(   'filter/iHomefinderQuickSearchFormFilterImpl.php');
	include_once(   'filter/iHomefinderSearchResultsFilterImpl.php');
	include_once(   'filter/iHomefinderListingDetailFilterImpl.php');
	
	include_once(   'filter/iHomefinderOrganizerLoginFormFilterImpl.php');
	include_once(   'filter/iHomefinderOrganizerLogoutFilterImpl.php');	
	include_once(   'filter/iHomefinderOrganizerLoginSubmitFilterImpl.php');
	
	include_once(   'filter/iHomefinderOrganizerEditSavedSearchFilterImpl.php');
	include_once(   'filter/iHomefinderOrganizerEditSavedSearchFormFilterImpl.php');
	include_once(   'filter/iHomefinderOrganizerEmailUpdatesConfirmationFilterImpl.php');	
	include_once(   'filter/iHomefinderOrganizerDeleteSavedSearchFilterImpl.php');
	
	include_once(   'filter/iHomefinderOrganizerViewSavedSearchFilterImpl.php');
	include_once(   'filter/iHomefinderOrganizerViewSavedSearchListFilterImpl.php');

	include_once(   'filter/iHomefinderOrganizerViewSavedListingListFilterImpl.php');
	include_once(   'filter/iHomefinderOrganizerResendConfirmationFilterImpl.php');
	include_once(   'filter/iHomefinderOrganizerActivateSubscriberFilterImpl.php');
	include_once(   'filter/iHomefinderOrganizerSendSubscriberPasswordFilterImpl.php');	

	/** 
	 * This singleton class creates instances of iHomefinder filters, based
	 * on a type parameter.
	 * @author ihomefinder
	 */
	class IHomefinderFilterFactory {
	
		private static $instance ;
		
		private function __construct(){
		}
		
		public static function getInstance(){
			if( !isset(self::$instance)){
				self::$instance = new IHomefinderFilterFactory();
			}
			return self::$instance;		
		}
		
		////////////////////////////////////////////////////////
		//Types used to determine the filter type in iHomefinderFilterFactory.
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
		const ORGANIZER_RESEND_CONFIRMATION_EMAIL="idx-property-organizer-resend-confirmation-email";
		const ORGANIZER_ACTIVATE_SUBSCRIBER ="idx-property-organizer-activate-subscriber";
		const ORGANIZER_SEND_SUBSCRIBER_PASSWORD="idx-property-organizer-send-login";
		///////////////////////////////////////////////////////		

		public function getFilter( $type ){
			$filter ;
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderFilterFactory.getFilter type=' . $type);
			if($type == IHomefinderFilterFactory::LISTING_SEARCH_RESULTS ){
				$filter = new IHomefinderSearchResultsFilterImpl();
			}	
			else if($type == IHomefinderFilterFactory::LISTING_DETAIL ){
				$filter = new IHomefinderListingDetailFilterImpl();
			}	
			else if( $type == IHomefinderFilterFactory::FEATURED_SEARCH){
				$filter = new IHomefinderFeaturedSearchFilterImpl();
			}
			else if( $type == IHomefinderFilterFactory::LISTING_ADVANCED_SEARCH_FORM){
				$filter = new IHomefinderAdvancedSearchFormFilterImpl();
			}
	    	else if( $type == IHomefinderFilterFactory::LISTING_SEARCH_FORM){
				$filter = new IHomefinderSearchFormFilterImpl();
			}
			else if( $type == IHomefinderFilterFactory::LISTING_QUICK_SEARCH_FORM){
				$filter = new IHomefinderQuickSearchFormFilterImpl();
			}			
			else if( $type == IHomefinderFilterFactory::HOTSHEET_SEARCH_RESULTS ){
				$filter = new IHomefinderHotsheetFilterImpl() ;
			}
			else if( $type == IHomefinderFilterFactory::HOTSHEET_LIST ){
				$filter = new IHomefinderHotsheetListFilterImpl() ;
			}
			else if( $type == IHomefinderFilterFactory::ORGANIZER_LOGIN ){
				$filter = new iHomefinderOrganizerLoginFormFilterImpl() ;
			}
			else if( $type == IHomefinderFilterFactory::ORGANIZER_LOGOUT ){
				$filter = new iHomefinderOrganizerLogoutFilterImpl() ;
			}			
			else if( $type == IHomefinderFilterFactory::ORGANIZER_LOGIN_SUBMIT ){
				$filter = new iHomefinderOrganizerLoginSubmitFilterImpl() ;
			}
			else if( $type == IHomefinderFilterFactory::ORGANIZER_EDIT_SAVED_SEARCH ){
				$filter = new IHomefinderOrganizerEditSavedSearchFormFilterImpl() ;
			}
			else if( $type == IHomefinderFilterFactory::ORGANIZER_EMAIL_UPDATES_CONFIRMATION ){
				$filter = new IHomefinderOrganizerEmailUpdatesConfirmationFilterImpl() ;
			}			
			else if( $type == IHomefinderFilterFactory::ORGANIZER_EDIT_SAVED_SEARCH_SUBMIT ){
				$filter = new IHomefinderOrganizerEditSavedSearchFilterImpl() ;
			}			
			else if( $type == IHomefinderFilterFactory::ORGANIZER_DELETE_SAVED_SEARCH_SUBMIT ){
				$filter = new IHomefinderOrganizerDeleteSavedSearchFilterImpl() ;
			}
			else if( $type == IHomefinderFilterFactory::ORGANIZER_VIEW_SAVED_SEARCH ){
				$filter = new IHomefinderOrganizerViewSavedSearchFilterImpl() ;
			}				
			else if( $type == IHomefinderFilterFactory::ORGANIZER_VIEW_SAVED_SEARCH_LIST ){
				$filter = new IHomefinderOrganizerViewSavedSearchListFilterImpl() ;
			}
			else if( $type == IHomefinderFilterFactory::ORGANIZER_VIEW_SAVED_LISTING_LIST ){
				$filter = new IHomefinderOrganizerViewSavedListingListFilterImpl() ;
			}
			else if( $type == IHomefinderFilterFactory::ORGANIZER_ACTIVATE_SUBSCRIBER){
				$filter = new iHomefinderOrganizerActivateSubscriberFilterImpl() ;
			}
			else if( $type == IHomefinderFilterFactory::ORGANIZER_RESEND_CONFIRMATION_EMAIL ){
				$filter = new IHomefinderOrganizerResendConfirmationFilterImpl() ;
			}			
			else if( $type == IHomefinderFilterFactory::ORGANIZER_SEND_SUBSCRIBER_PASSWORD ){
				$filter = new iHomefinderOrganizerSendSubscriberPasswordFilterImpl() ;
			}			
			IHomefinderLogger::getInstance()->debug('Complete IHomefinderFilterFactory.getFilter');
			return $filter ;
		}
	}
}
?>