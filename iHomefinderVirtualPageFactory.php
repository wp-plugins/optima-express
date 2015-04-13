<?php

include_once "virtualPage/iHomefinderVirtualPageInterface.php";
include_once "virtualPage/iHomefinderAbstractVirtualPage.php";
include_once "virtualPage/iHomefinderFeaturedSearchVirtualPageImpl.php";
include_once "virtualPage/iHomefinderHotsheetVirtualPageImpl.php";
include_once "virtualPage/iHomefinderHotsheetListVirtualPageImpl.php";
include_once "virtualPage/iHomefinderAdvancedSearchFormVirtualPageImpl.php";
include_once "virtualPage/iHomefinderSearchFormVirtualPageImpl.php";
include_once "virtualPage/iHomefinderMapSearchVirtualPageImpl.php";
include_once "virtualPage/iHomefinderQuickSearchFormVirtualPageImpl.php";
include_once "virtualPage/iHomefinderSearchResultsVirtualPageImpl.php";
include_once "virtualPage/iHomefinderListingDetailVirtualPageImpl.php";
include_once "virtualPage/iHomefinderListingSoldDetailVirtualPageImpl.php";
include_once "virtualPage/iHomefinderSearchByAddressResultsVirtualPageImpl.php";
include_once "virtualPage/iHomefinderSearchByListingIdResultsVirtualPageImpl.php";
include_once "virtualPage/iHomefinderOrganizerLoginFormVirtualPageImpl.php";
include_once "virtualPage/iHomefinderOrganizerLogoutVirtualPageImpl.php";
include_once "virtualPage/iHomefinderOrganizerLoginSubmitVirtualPageImpl.php";
include_once "virtualPage/iHomefinderOrganizerEditSavedSearchVirtualPageImpl.php";
include_once "virtualPage/iHomefinderOrganizerEditSavedSearchFormVirtualPageImpl.php";
include_once "virtualPage/iHomefinderOrganizerEmailUpdatesConfirmationVirtualPageImpl.php";
include_once "virtualPage/iHomefinderOrganizerDeleteSavedSearchVirtualPageImpl.php";
include_once "virtualPage/iHomefinderOrganizerViewSavedSearchVirtualPageImpl.php";
include_once "virtualPage/iHomefinderOrganizerViewSavedSearchListVirtualPageImpl.php";
include_once "virtualPage/iHomefinderOrganizerViewSavedListingListVirtualPageImpl.php";
include_once "virtualPage/iHomefinderOrganizerDeleteSavedListingVirtualPageImpl.php";
include_once "virtualPage/iHomefinderOrganizerResendConfirmationVirtualPageImpl.php";
include_once "virtualPage/iHomefinderOrganizerActivateSubscriberVirtualPageImpl.php";
include_once "virtualPage/iHomefinderOrganizerSendSubscriberPasswordVirtualPageImpl.php";
include_once "virtualPage/iHomefinderOrganizerHelpVirtualPageImpl.php";
include_once "virtualPage/iHomefinderOrganizerEditSubscriberVirtualPageImpl.php";
include_once "virtualPage/iHomefinderContactFormVirtualPageImpl.php";
include_once "virtualPage/iHomefinderValuationFormVirtualPageImpl.php";
include_once "virtualPage/iHomefinderOpenHomeSearchFormVirtualPageImpl.php";
include_once "virtualPage/iHomefinderSoldFeaturedListingVirtualPageImpl.php";
include_once "virtualPage/iHomefinderSupplementalListingVirtualPageImpl.php";
include_once "virtualPage/iHomefinderOfficeListVirtualPageImpl.php";
include_once "virtualPage/iHomefinderOfficeDetailVirtualPageImpl.php";
include_once "virtualPage/iHomefinderAgentListVirtualPageImpl.php";
include_once "virtualPage/iHomefinderAgentDetailVirtualPageImpl.php";
include_once "virtualPage/iHomefinderAgentOrOfficeListingsVirtualPageImpl.php";

/**
 * This singleton class creates instances of iHomefinder VirtualPages, based
 * on a type parameter.
 * @author ihomefinder
 */
class iHomefinderVirtualPageFactory {

	private static $instance;

	private function __construct() {
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new iHomefinderVirtualPageFactory();
		}
		return self::$instance;
	}
	
	//Types used to determine the VirtualPage type in iHomefinderVirtualPageFactory.
	const LISTING_SEARCH_RESULTS="idx-results";
	const LISTING_DETAIL="idx-detail";
	const LISTING_SOLD_DETAIL="idx-sold-detail";
	const LISTING_SEARCH_FORM="idx-search";
	const MAP_SEARCH_FORM="idx-map-search";
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
	const LISTING_SEARCH_BY_ADDRESS_RESULTS="idx-results-by-address";
	const LISTING_SEARCH_BY_LISTING_ID_RESULTS="idx-results-by-listing-id";
	const CONTACT_FORM="idx-contact-form";
	const VALUATION_FORM="idx-valuation-form";
	const OPEN_HOME_SEARCH_FORM="idx-open-home-search-form";
	const SUPPLEMENTAL_LISTING="idx-supplemental-listing";
	const SOLD_FEATURED_LISTING="idx-sold-featured-listing";
	const OFFICE_LIST="idx-office-list";
	const OFFICE_DETAIL="idx-office-detail";
	const AGENT_LIST="idx-agent-list";
	const AGENT_DETAIL="idx-agent-detail";
	const AGENT_OR_OFFICE_LISTINGS="idx-agent-or-office-listings";

	public function getVirtualPage($type) {
		$virtualPage;
		//iHomefinderLogger::getInstance()->debug("Begin iHomefinderVirtualPageFactory.getVirtualPage type=" . $type);
		if($type == iHomefinderVirtualPageFactory::LISTING_SEARCH_RESULTS) {
			$virtualPage = new iHomefinderSearchResultsVirtualPageImpl();
		}
		else if($type == iHomefinderVirtualPageFactory::LISTING_DETAIL) {
			$virtualPage = new iHomefinderListingDetailVirtualPageImpl();
		}
		else if($type == iHomefinderVirtualPageFactory::LISTING_SOLD_DETAIL) {
			$virtualPage = new iHomefinderListingSoldDetailVirtualPageImpl();
		}			
		else if($type == iHomefinderVirtualPageFactory::FEATURED_SEARCH) {
			$virtualPage = new iHomefinderFeaturedSearchVirtualPageImpl();
		}
		else if($type == iHomefinderVirtualPageFactory::LISTING_ADVANCED_SEARCH_FORM) {
			$virtualPage = new iHomefinderAdvancedSearchFormVirtualPageImpl();
		}
		else if($type == iHomefinderVirtualPageFactory::LISTING_SEARCH_FORM) {
			$virtualPage = new iHomefinderSearchFormVirtualPageImpl();
		}
		else if($type == iHomefinderVirtualPageFactory::LISTING_SEARCH_BY_ADDRESS_RESULTS) {
			$virtualPage = new iHomefinderSearchByAddressResultsVirtualPageImpl();
		}
		else if($type == iHomefinderVirtualPageFactory::LISTING_SEARCH_BY_LISTING_ID_RESULTS) {
			$virtualPage = new iHomefinderSearchByListingIdResultsVirtualPageImpl();
		}
		else if($type == iHomefinderVirtualPageFactory::MAP_SEARCH_FORM) {
			$virtualPage = new iHomefinderMapSearchVirtualPageImpl();
		}			
		else if($type == iHomefinderVirtualPageFactory::LISTING_QUICK_SEARCH_FORM) {
			$virtualPage = new iHomefinderQuickSearchFormVirtualPageImpl();
		}
		else if($type == iHomefinderVirtualPageFactory::HOTSHEET_SEARCH_RESULTS) {
			$virtualPage = new iHomefinderHotsheetVirtualPageImpl();
		}
		else if($type == iHomefinderVirtualPageFactory::HOTSHEET_LIST) {
			$virtualPage = new iHomefinderHotsheetListVirtualPageImpl();
		}
		else if($type == iHomefinderVirtualPageFactory::ORGANIZER_LOGIN) {
			$virtualPage = new iHomefinderOrganizerLoginFormVirtualPageImpl();
		}
		else if($type == iHomefinderVirtualPageFactory::ORGANIZER_LOGOUT) {
			$virtualPage = new iHomefinderOrganizerLogoutVirtualPageImpl();
		}
		else if($type == iHomefinderVirtualPageFactory::ORGANIZER_LOGIN_SUBMIT) {
			$virtualPage = new iHomefinderOrganizerLoginSubmitVirtualPageImpl();
		}
		else if($type == iHomefinderVirtualPageFactory::ORGANIZER_EDIT_SAVED_SEARCH) {
			$virtualPage = new iHomefinderOrganizerEditSavedSearchFormVirtualPageImpl();
		}
		else if($type == iHomefinderVirtualPageFactory::ORGANIZER_EMAIL_UPDATES_CONFIRMATION) {
			$virtualPage = new iHomefinderOrganizerEmailUpdatesConfirmationVirtualPageImpl();
		}
		else if($type == iHomefinderVirtualPageFactory::ORGANIZER_EDIT_SAVED_SEARCH_SUBMIT) {
			$virtualPage = new iHomefinderOrganizerEditSavedSearchVirtualPageImpl();
		}
		else if($type == iHomefinderVirtualPageFactory::ORGANIZER_DELETE_SAVED_SEARCH_SUBMIT) {
			$virtualPage = new iHomefinderOrganizerDeleteSavedSearchVirtualPageImpl();
		}
		else if($type == iHomefinderVirtualPageFactory::ORGANIZER_VIEW_SAVED_SEARCH) {
			$virtualPage = new iHomefinderOrganizerViewSavedSearchVirtualPageImpl();
		}
		else if($type == iHomefinderVirtualPageFactory::ORGANIZER_VIEW_SAVED_SEARCH_LIST) {
			$virtualPage = new iHomefinderOrganizerViewSavedSearchListVirtualPageImpl();
		}
		else if($type == iHomefinderVirtualPageFactory::ORGANIZER_VIEW_SAVED_LISTING_LIST) {
			$virtualPage = new iHomefinderOrganizerViewSavedListingListVirtualPageImpl();
		}
		else if($type == iHomefinderVirtualPageFactory::ORGANIZER_DELETE_SAVED_LISTING_SUBMIT) {
			$virtualPage = new iHomefinderOrganizerDeleteSavedListingVirtualPageImpl();
		}
		else if($type == iHomefinderVirtualPageFactory::ORGANIZER_ACTIVATE_SUBSCRIBER) {
			$virtualPage = new iHomefinderOrganizerActivateSubscriberVirtualPageImpl();
		}
		else if($type == iHomefinderVirtualPageFactory::ORGANIZER_RESEND_CONFIRMATION_EMAIL) {
			$virtualPage = new iHomefinderOrganizerResendConfirmationVirtualPageImpl();
		}
		else if($type == iHomefinderVirtualPageFactory::ORGANIZER_SEND_SUBSCRIBER_PASSWORD) {
			$virtualPage = new iHomefinderOrganizerSendSubscriberPasswordVirtualPageImpl();
		}
		else if($type == iHomefinderVirtualPageFactory::ORGANIZER_HELP) {
			$virtualPage = new iHomefinderOrganizerHelpVirtualPageImpl();
		}
		else if($type == iHomefinderVirtualPageFactory::ORGANIZER_EDIT_SUBSCRIBER) {
			$virtualPage = new iHomefinderOrganizerEditSubscriberVirtualPageImpl();
		}
		else if($type == iHomefinderVirtualPageFactory::CONTACT_FORM) {
			$virtualPage = new iHomefinderContactFormVirtualPageImpl();
		}
		else if($type == iHomefinderVirtualPageFactory::VALUATION_FORM) {
			$virtualPage = new iHomefinderValuationFormVirtualPageImpl();
		}
		else if($type == iHomefinderVirtualPageFactory::OPEN_HOME_SEARCH_FORM) {
			$virtualPage = new iHomefinderOpenHomeSearchFormVirtualPageImpl();
		}
		else if($type == iHomefinderVirtualPageFactory::SUPPLEMENTAL_LISTING) {
			$virtualPage = new iHomefinderSupplementalListingVirtualPageImpl();
		}
		else if($type == iHomefinderVirtualPageFactory::SOLD_FEATURED_LISTING) {
			$virtualPage = new iHomefinderSoldFeaturedListingVirtualPageImpl();
		}
		else if($type == iHomefinderVirtualPageFactory::OFFICE_LIST) {
			$virtualPage = new iHomefinderOfficeListVirtualPageImpl();
		}			
		else if($type == iHomefinderVirtualPageFactory::OFFICE_DETAIL) {
			$virtualPage = new iHomefinderOfficeDetailVirtualPageImpl();
		}			
		else if($type == iHomefinderVirtualPageFactory::AGENT_LIST) {
			$virtualPage = new iHomefinderAgentListVirtualPageImpl();
		}			
		else if($type == iHomefinderVirtualPageFactory::AGENT_DETAIL) {
			$virtualPage = new iHomefinderAgentDetailVirtualPageImpl();
		}			
		else if($type == iHomefinderVirtualPageFactory::AGENT_OR_OFFICE_LISTINGS) {
			$virtualPage = new iHomefinderAgentOrOfficeListingsVirtualPageImpl();
		}

		//iHomefinderLogger::getInstance()->debug("Complete iHomefinderVirtualPageFactory.getVirtualPage");
		return $virtualPage;
	}
}