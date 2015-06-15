<?php

class iHomefinderVirtualPageFactory {

	private static $instance;

	private function __construct() {
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	//Types used to determine the VirtualPage type in iHomefinderVirtualPageFactory.
	const DEFAULT_PAGE = "idx-default";
	const LISTING_SEARCH_RESULTS = "idx-results";
	const LISTING_DETAIL = "idx-detail";
	const LISTING_SOLD_DETAIL = "idx-sold-detail";
	const LISTING_SEARCH_FORM = "idx-search";
	const MAP_SEARCH_FORM = "idx-map-search";
	const LISTING_QUICK_SEARCH_FORM = "idx-quick-search";
	const LISTING_ADVANCED_SEARCH_FORM = "idx-advanced-search";
	const FEATURED_SEARCH = "idx-featured-search";
	const HOTSHEET_SEARCH_RESULTS = "idx-toppicks";
	const HOTSHEET_LIST = "idx-toppicks-list";
	const ORGANIZER_LOGIN = "idx-property-organizer-login";
	const ORGANIZER_LOGOUT = "idx-property-organizer-logout";
	const ORGANIZER_LOGIN_SUBMIT = "idx-property-organizer-submit-login";
	const ORGANIZER_EDIT_SAVED_SEARCH = "idx-property-organizer-edit-saved-search";
	const ORGANIZER_EDIT_SAVED_SEARCH_SUBMIT = "idx-property-organizer-edit-saved-search-submit";
	const ORGANIZER_EMAIL_UPDATES_CONFIRMATION = "idx-property-organizer-email-updates-success";
	const ORGANIZER_DELETE_SAVED_SEARCH = "idx-property-organizer-delete-saved-search";
	const ORGANIZER_DELETE_SAVED_SEARCH_SUBMIT = "idx-property-organizer-delete-saved-search-submit";
	const ORGANIZER_VIEW_SAVED_SEARCH = "idx-property-organizer-view-saved-search";
	const ORGANIZER_VIEW_SAVED_SEARCH_LIST = "idx-property-organizer-view-saved-searches";
	const ORGANIZER_VIEW_SAVED_LISTING_LIST = "idx-property-organizer-view-saved-listings";
	const ORGANIZER_DELETE_SAVED_LISTING_SUBMIT = "idx-property-organizer-delete-saved-listing";
	const ORGANIZER_RESEND_CONFIRMATION_EMAIL = "idx-property-organizer-resend-confirmation-email";
	const ORGANIZER_ACTIVATE_SUBSCRIBER = "idx-property-organizer-activate-subscriber";
	const ORGANIZER_SEND_SUBSCRIBER_PASSWORD = "idx-property-organizer-send-login";
	const ORGANIZER_HELP = "idx-property-organizer-help";
	const ORGANIZER_EDIT_SUBSCRIBER = "idx-property-organizer-edit-subscriber";
	const CONTACT_FORM = "idx-contact-form";
	const VALUATION_FORM = "idx-valuation-form";
	const OPEN_HOME_SEARCH_FORM = "idx-open-home-search-form";
	const SUPPLEMENTAL_LISTING = "idx-supplemental-listing";
	const SOLD_FEATURED_LISTING = "idx-sold-featured-listing";
	const OFFICE_LIST = "idx-office-list";
	const OFFICE_DETAIL = "idx-office-detail";
	const AGENT_LIST = "idx-agent-list";
	const AGENT_DETAIL = "idx-agent-detail";
	const AGENT_OR_OFFICE_LISTINGS = "idx-agent-or-office-listings";
	
	/**
	 * 
	 * @param string $type
	 * @return iHomefinderVirtualPageInterface
	 */
	public function getVirtualPage($virtualPageType) {
		$virtualPage = null;
		switch($virtualPageType) {
			case self::DEFAULT_PAGE:
				$virtualPage = new iHomefinderDefaultVirtualPageImpl();
				break;
			case self::LISTING_SEARCH_RESULTS:
				$virtualPage = new iHomefinderSearchResultsVirtualPageImpl();
				break;
			case self::LISTING_DETAIL:
				$virtualPage = new iHomefinderListingDetailVirtualPageImpl();
				break;
			case self::LISTING_SOLD_DETAIL:
				$virtualPage = new iHomefinderListingSoldDetailVirtualPageImpl();
				break;			
			case self::FEATURED_SEARCH:
				$virtualPage = new iHomefinderFeaturedSearchVirtualPageImpl();
				break;
			case self::LISTING_ADVANCED_SEARCH_FORM:
				$virtualPage = new iHomefinderAdvancedSearchFormVirtualPageImpl();
				break;
			case self::LISTING_SEARCH_FORM:
				$virtualPage = new iHomefinderSearchFormVirtualPageImpl();
				break;
			case self::MAP_SEARCH_FORM:
				$virtualPage = new iHomefinderMapSearchVirtualPageImpl();
				break;			
			case self::LISTING_QUICK_SEARCH_FORM:
				$virtualPage = new iHomefinderQuickSearchFormVirtualPageImpl();
				break;
			case self::HOTSHEET_SEARCH_RESULTS:
				$virtualPage = new iHomefinderHotsheetVirtualPageImpl();
				break;
			case self::HOTSHEET_LIST:
				$virtualPage = new iHomefinderHotsheetListVirtualPageImpl();
				break;
			case self::ORGANIZER_LOGIN:
				$virtualPage = new iHomefinderOrganizerLoginFormVirtualPageImpl();
				break;
			case self::ORGANIZER_LOGOUT:
				$virtualPage = new iHomefinderOrganizerLogoutVirtualPageImpl();
				break;
			case self::ORGANIZER_LOGIN_SUBMIT:
				$virtualPage = new iHomefinderOrganizerLoginSubmitVirtualPageImpl();
				break;
			case self::ORGANIZER_EDIT_SAVED_SEARCH:
				$virtualPage = new iHomefinderOrganizerEditSavedSearchFormVirtualPageImpl();
				break;
			case self::ORGANIZER_EMAIL_UPDATES_CONFIRMATION:
				$virtualPage = new iHomefinderOrganizerEmailUpdatesConfirmationVirtualPageImpl();
				break;
			case self::ORGANIZER_EDIT_SAVED_SEARCH_SUBMIT:
				$virtualPage = new iHomefinderOrganizerEditSavedSearchVirtualPageImpl();
				break;
			case self::ORGANIZER_DELETE_SAVED_SEARCH_SUBMIT:
				$virtualPage = new iHomefinderOrganizerDeleteSavedSearchVirtualPageImpl();
				break;
			case self::ORGANIZER_VIEW_SAVED_SEARCH:
				$virtualPage = new iHomefinderOrganizerViewSavedSearchVirtualPageImpl();
				break;
			case self::ORGANIZER_VIEW_SAVED_SEARCH_LIST:
				$virtualPage = new iHomefinderOrganizerViewSavedSearchListVirtualPageImpl();
				break;
			case self::ORGANIZER_VIEW_SAVED_LISTING_LIST:
				$virtualPage = new iHomefinderOrganizerViewSavedListingListVirtualPageImpl();
				break;
			case self::ORGANIZER_DELETE_SAVED_LISTING_SUBMIT:
				$virtualPage = new iHomefinderOrganizerDeleteSavedListingVirtualPageImpl();
				break;
			case self::ORGANIZER_ACTIVATE_SUBSCRIBER:
				$virtualPage = new iHomefinderOrganizerActivateSubscriberVirtualPageImpl();
				break;
			case self::ORGANIZER_RESEND_CONFIRMATION_EMAIL:
				$virtualPage = new iHomefinderOrganizerResendConfirmationVirtualPageImpl();
				break;
			case self::ORGANIZER_SEND_SUBSCRIBER_PASSWORD:
				$virtualPage = new iHomefinderOrganizerSendSubscriberPasswordVirtualPageImpl();
				break;
			case self::ORGANIZER_HELP:
				$virtualPage = new iHomefinderOrganizerHelpVirtualPageImpl();
				break;
			case self::ORGANIZER_EDIT_SUBSCRIBER:
				$virtualPage = new iHomefinderOrganizerEditSubscriberVirtualPageImpl();
				break;
			case self::CONTACT_FORM:
				$virtualPage = new iHomefinderContactFormVirtualPageImpl();
				break;
			case self::VALUATION_FORM:
				$virtualPage = new iHomefinderValuationFormVirtualPageImpl();
				break;
			case self::OPEN_HOME_SEARCH_FORM:
				$virtualPage = new iHomefinderOpenHomeSearchFormVirtualPageImpl();
				break;
			case self::SUPPLEMENTAL_LISTING:
				$virtualPage = new iHomefinderSupplementalListingVirtualPageImpl();
				break;
			case self::SOLD_FEATURED_LISTING:
				$virtualPage = new iHomefinderSoldFeaturedListingVirtualPageImpl();
				break;
			case self::OFFICE_LIST:
				$virtualPage = new iHomefinderOfficeListVirtualPageImpl();
				break;			
			case self::OFFICE_DETAIL:
				$virtualPage = new iHomefinderOfficeDetailVirtualPageImpl();
				break;			
			case self::AGENT_LIST:
				$virtualPage = new iHomefinderAgentListVirtualPageImpl();
				break;			
			case self::AGENT_DETAIL:
				$virtualPage = new iHomefinderAgentDetailVirtualPageImpl();
				break;			
			case self::AGENT_OR_OFFICE_LISTINGS:
				$virtualPage = new iHomefinderAgentOrOfficeListingsVirtualPageImpl();
				break;
		}
		return $virtualPage;
	}
	
}