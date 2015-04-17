<?php

/**
 * Singleton class that provides convenience methods for building plugin URLs
 * 
 * @author ihomefinder
 */
class iHomefinderUrlFactory {
	
	private static $instance;
	private $virtualPageFactory;

	private function __construct() {
		$this->virtualPageFactory = iHomefinderVirtualPageFactory::getInstance();
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 *
	 * Gets the base URL for this blog
	 */
	public function getBaseUrl() {
		return home_url();
	}

	/**
	 * This is a Wordpress standard for AJAX handling.
	 */
	public function getAjaxBaseUrl() {
		$currentBlogAddress = site_url();
		return $currentBlogAddress . "/wp-admin/admin-ajax.php";
	}
	
	/**
	 * $includeBaseUrl is false when called from iHomefinderRewriteRules
	 */
	private function prependBaseUrl($path, $includeBaseUrl) {
		if($includeBaseUrl) {
			$path = $this->getBaseUrl() . "/" . $path . "/";
		}
		return $path;
	}
	
	public function makeRelativeUrl($url) {
		$urlParts = parse_url($url);
		$value = $urlParts["path"];
		$query = $urlParts["query"];
		if($query) {
			$value .= "?" . $query;
		}
		if($value == null || $value == "") {
			$value = "/";
		}
		return $value;
	}

	public function getListingsSearchResultsUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::LISTING_SEARCH_RESULTS);
		$path = $virtualPage->getPath();
		$value = $this->prependBaseUrl($path, $includeBaseUrl);
		return $value;
	}

	public function getListingsSearchFormUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::LISTING_SEARCH_FORM);
		$path = $virtualPage->getPath();
		$value = $this->prependBaseUrl($path, $includeBaseUrl);
		return $value;
	}
	
	public function getMapSearchFormUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::MAP_SEARCH_FORM);
		$path = $virtualPage->getPath();
		$value = $this->prependBaseUrl($path, $includeBaseUrl);
		return $value;
	}
	
	public function getListingsAdvancedSearchFormUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::LISTING_ADVANCED_SEARCH_FORM);
		$path = $virtualPage->getPath();	
		$value = $this->prependBaseUrl($path, $includeBaseUrl);
		return $value;
	}
	
	public function getListingDetailUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::LISTING_DETAIL);
		$path = $virtualPage->getPath();				
		$value = $this->prependBaseUrl($path, $includeBaseUrl);
		return $value;
	}
	
	public function getListingSoldDetailUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::LISTING_SOLD_DETAIL);
		$path = $virtualPage->getPath();				
		$value = $this->prependBaseUrl($path, $includeBaseUrl);
		return $value;
	}
	
	public function getFeaturedSearchResultsUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::FEATURED_SEARCH);
		$path = $virtualPage->getPath();				
		$value = $this->prependBaseUrl($path, $includeBaseUrl);			
		return $value;
	}

	public function getHotsheetSearchResultsUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::HOTSHEET_SEARCH_RESULTS);
		$path = $virtualPage->getPath();					
		$value = $this->prependBaseUrl($path, $includeBaseUrl);			
		return $value;
	}
	
	public function getHotsheetListUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::HOTSHEET_LIST);
		$path = $virtualPage->getPath();					
		$value = $this->prependBaseUrl($path, $includeBaseUrl);			
		return $value;
	}

	public function getOrganizerLoginUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_LOGIN);
		$path = $virtualPage->getPath();							
		$value = $this->prependBaseUrl($path, $includeBaseUrl);			
		return $value;
	}
	
	public function getOrganizerLogoutUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_LOGOUT);
		$path = $virtualPage->getPath();										
		$value = $this->prependBaseUrl($path, $includeBaseUrl);			
		return $value;
	}
	
	public function getOrganizerLoginSubmitUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_LOGIN_SUBMIT);
		$path = $virtualPage->getPath();				
		$value = $this->prependBaseUrl($path, $includeBaseUrl);			
		return $value;
	}
	
	public function getOrganizerEditSavedSearchUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_EDIT_SAVED_SEARCH);
		$path = $virtualPage->getPath();				
		$value = $this->prependBaseUrl($path, $includeBaseUrl);			
		return $value;
	}
	
	public function getOrganizerEditSavedSearchSubmitUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_EDIT_SAVED_SEARCH_SUBMIT);
		$path = $virtualPage->getPath();				
		$value = $this->prependBaseUrl($path, $includeBaseUrl);			
		return $value;
	}
	
	public function getOrganizerDeleteSavedSearchSubmitUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_DELETE_SAVED_SEARCH_SUBMIT);
		$path = $virtualPage->getPath();				
		$value = $this->prependBaseUrl($path, $includeBaseUrl);			
		return $value;
	}
	
	public function getOrganizerViewSavedSearchUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_VIEW_SAVED_SEARCH);
		$path = $virtualPage->getPath();							
		$value = $this->prependBaseUrl($path, $includeBaseUrl);			
		return $value;
	}
	
	public function getOrganizerViewSavedSearchListUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_VIEW_SAVED_SEARCH_LIST);
		$path = $virtualPage->getPath();				
		$value = $this->prependBaseUrl($path, $includeBaseUrl);			
		return $value;
	}
	
	public function getOrganizerResendConfirmationEmailUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_RESEND_CONFIRMATION_EMAIL);
		$path = $virtualPage->getPath();							
		$value = $this->prependBaseUrl($path, $includeBaseUrl);			
		return $value;
	}
	
	public function getOrganizerActivateSubscriberUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_ACTIVATE_SUBSCRIBER);
		$path = $virtualPage->getPath();						
		$value = $this->prependBaseUrl($path, $includeBaseUrl);			
		return $value;
	}
	
	public function getOrganizerSendSubscriberPasswordUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_SEND_SUBSCRIBER_PASSWORD);
		$path = $virtualPage->getPath();				
		$value = $this->prependBaseUrl($path, $includeBaseUrl);			
		return $value;
	}
		
	public function getOrganizerViewSavedListingListUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_VIEW_SAVED_LISTING_LIST);
		$path = $virtualPage->getPath();				
		$value = $this->prependBaseUrl($path, $includeBaseUrl);			
		return $value;			
	}

	public function getOrganizerDeleteSavedListingUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_DELETE_SAVED_LISTING_SUBMIT);
		$path = $virtualPage->getPath();				
		$value = $this->prependBaseUrl($path, $includeBaseUrl);			
		return $value;			
	}
	
	public function getOrganizerEmailUpdatesConfirmationUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_EMAIL_UPDATES_CONFIRMATION);
		$path = $virtualPage->getPath();				
		$value = $this->prependBaseUrl($path, $includeBaseUrl);
		return $value;			
	}
	
	public function getOrganizerHelpUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_HELP);
		$path = $virtualPage->getPath();				
		$value = $this->prependBaseUrl($path, $includeBaseUrl);
		return $value;			
	}

	public function getOrganizerEditSubscriberUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_EDIT_SUBSCRIBER);
		$path = $virtualPage->getPath();				
		$value = $this->prependBaseUrl($path, $includeBaseUrl);
		return $value;			
	}

	public function getContactFormUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::CONTACT_FORM);
		$path = $virtualPage->getPath();				
		$value = $this->prependBaseUrl($path, $includeBaseUrl);
		return $value;			
	}

	public function getValuationFormUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::VALUATION_FORM);
		$path = $virtualPage->getPath();				
		$value = $this->prependBaseUrl($path, $includeBaseUrl);
		return $value;			
	}

	public function getOpenHomeSearchFormUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::OPEN_HOME_SEARCH_FORM);
		$path = $virtualPage->getPath();				
		$value = $this->prependBaseUrl($path, $includeBaseUrl);
		return $value;			
	}
	
	public function getSoldFeaturedListingUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::SOLD_FEATURED_LISTING);
		$path = $virtualPage->getPath();				
		$value = $this->prependBaseUrl($path, $includeBaseUrl);
		return $value;			
	}
	
	public function getSupplementalListingUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::SUPPLEMENTAL_LISTING);
		$path = $virtualPage->getPath();				
		$value = $this->prependBaseUrl($path, $includeBaseUrl);
		return $value;			
	}
	
	public function getListingSearchByAddressResultsUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::LISTING_SEARCH_BY_ADDRESS_RESULTS);
		$path = $virtualPage->getPath();
		$value = $this->prependBaseUrl($path, $includeBaseUrl);
		return $value;
	}
	
	public function getListingSearchByListingIdResultsUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::LISTING_SEARCH_BY_LISTING_ID_RESULTS);
		$path = $virtualPage->getPath();
		$value = $this->prependBaseUrl($path, $includeBaseUrl);
		return $value;
	}

	public function getOfficeListUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::OFFICE_LIST);
		$path = $virtualPage->getPath();				
		$value = $this->prependBaseUrl($path, $includeBaseUrl);
		return $value;			
	}

	public function getOfficeDetailUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::OFFICE_DETAIL);
		$path = $virtualPage->getPath();				
		$value = $this->prependBaseUrl($path, $includeBaseUrl);
		return $value;			
	}

	public function getAgentListUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::AGENT_LIST);
		$path = $virtualPage->getPath();				
		$value = $this->prependBaseUrl($path, $includeBaseUrl);
		return $value;			
	}

	public function getAgentDetailUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::AGENT_DETAIL);
		$path = $virtualPage->getPath();				
		$value = $this->prependBaseUrl($path, $includeBaseUrl);
		return $value;			
	}
	
}