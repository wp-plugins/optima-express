<?php

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
	 * Gets the base URL for this blog
	 */
	public function getBaseUrl() {
		return home_url();
	}

	/**
	 * This is a Wordpress standard for AJAX handling.
	 */
	public function getAjaxBaseUrl() {
		return admin_url("admin-ajax.php");
	}
	
	/**
	 * @param includeBaseUrl is false when called from iHomefinderRewriteRules
	 */
	private function prependBaseUrl($permalink, $includeBaseUrl) {
		$result = $permalink;
		if($includeBaseUrl) {
			$result = $this->getBaseUrl() . "/" . $result . "/";
		}
		return $result;
	}
	
	public function getListingsSearchResultsUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::LISTING_SEARCH_RESULTS);
		$permalink = $virtualPage->getPermalink();
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);
		return $result;
	}
	
	public function getListingsSearchFormUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::LISTING_SEARCH_FORM);
		$permalink = $virtualPage->getPermalink();
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);
		return $result;
	}
	
	public function getMapSearchFormUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::MAP_SEARCH_FORM);
		$permalink = $virtualPage->getPermalink();
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);
		return $result;
	}
	
	public function getListingsAdvancedSearchFormUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::LISTING_ADVANCED_SEARCH_FORM);
		$permalink = $virtualPage->getPermalink();	
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);
		return $result;
	}
	
	public function getListingDetailUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::LISTING_DETAIL);
		$permalink = $virtualPage->getPermalink();				
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);
		return $result;
	}
	
	public function getListingSoldDetailUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::LISTING_SOLD_DETAIL);
		$permalink = $virtualPage->getPermalink();				
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);
		return $result;
	}
	
	public function getFeaturedSearchResultsUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::FEATURED_SEARCH);
		$permalink = $virtualPage->getPermalink();				
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);			
		return $result;
	}
	
	public function getHotsheetSearchResultsUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::HOTSHEET_SEARCH_RESULTS);
		$permalink = $virtualPage->getPermalink();					
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);			
		return $result;
	}
	
	public function getHotsheetListUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::HOTSHEET_LIST);
		$permalink = $virtualPage->getPermalink();					
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);			
		return $result;
	}
	
	public function getOrganizerLoginUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_LOGIN);
		$permalink = $virtualPage->getPermalink();							
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);			
		return $result;
	}
	
	public function getOrganizerLogoutUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_LOGOUT);
		$permalink = $virtualPage->getPermalink();										
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);			
		return $result;
	}
	
	public function getOrganizerLoginSubmitUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_LOGIN_SUBMIT);
		$permalink = $virtualPage->getPermalink();				
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);			
		return $result;
	}
	
	public function getOrganizerEditSavedSearchUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_EDIT_SAVED_SEARCH);
		$permalink = $virtualPage->getPermalink();				
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);			
		return $result;
	}
	
	public function getOrganizerEditSavedSearchSubmitUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_EDIT_SAVED_SEARCH_SUBMIT);
		$permalink = $virtualPage->getPermalink();				
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);			
		return $result;
	}
	
	public function getOrganizerDeleteSavedSearchSubmitUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_DELETE_SAVED_SEARCH_SUBMIT);
		$permalink = $virtualPage->getPermalink();				
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);			
		return $result;
	}
	
	public function getOrganizerViewSavedSearchUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_VIEW_SAVED_SEARCH);
		$permalink = $virtualPage->getPermalink();							
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);			
		return $result;
	}
	
	public function getOrganizerViewSavedSearchListUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_VIEW_SAVED_SEARCH_LIST);
		$permalink = $virtualPage->getPermalink();				
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);			
		return $result;
	}
	
	public function getOrganizerResendConfirmationEmailUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_RESEND_CONFIRMATION_EMAIL);
		$permalink = $virtualPage->getPermalink();							
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);			
		return $result;
	}
	
	public function getOrganizerActivateSubscriberUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_ACTIVATE_SUBSCRIBER);
		$permalink = $virtualPage->getPermalink();						
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);			
		return $result;
	}
	
	public function getOrganizerSendSubscriberPasswordUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_SEND_SUBSCRIBER_PASSWORD);
		$permalink = $virtualPage->getPermalink();				
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);			
		return $result;
	}
	
	public function getOrganizerViewSavedListingListUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_VIEW_SAVED_LISTING_LIST);
		$permalink = $virtualPage->getPermalink();				
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);			
		return $result;			
	}
	
	public function getOrganizerDeleteSavedListingUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_DELETE_SAVED_LISTING_SUBMIT);
		$permalink = $virtualPage->getPermalink();				
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);			
		return $result;			
	}
	
	public function getOrganizerEmailUpdatesConfirmationUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_EMAIL_UPDATES_CONFIRMATION);
		$permalink = $virtualPage->getPermalink();				
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);
		return $result;			
	}
	
	public function getOrganizerHelpUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_HELP);
		$permalink = $virtualPage->getPermalink();				
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);
		return $result;			
	}
	
	public function getOrganizerEditSubscriberUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_EDIT_SUBSCRIBER);
		$permalink = $virtualPage->getPermalink();				
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);
		return $result;			
	}
	
	public function getContactFormUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::CONTACT_FORM);
		$permalink = $virtualPage->getPermalink();				
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);
		return $result;			
	}
	
	public function getValuationFormUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::VALUATION_FORM);
		$permalink = $virtualPage->getPermalink();				
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);
		return $result;			
	}
	
	public function getOpenHomeSearchFormUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::OPEN_HOME_SEARCH_FORM);
		$permalink = $virtualPage->getPermalink();				
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);
		return $result;			
	}
	
	public function getSoldFeaturedListingUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::SOLD_FEATURED_LISTING);
		$permalink = $virtualPage->getPermalink();				
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);
		return $result;			
	}
	
	public function getSupplementalListingUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::SUPPLEMENTAL_LISTING);
		$permalink = $virtualPage->getPermalink();				
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);
		return $result;			
	}
	
	public function getOfficeListUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::OFFICE_LIST);
		$permalink = $virtualPage->getPermalink();				
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);
		return $result;			
	}
	
	public function getOfficeDetailUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::OFFICE_DETAIL);
		$permalink = $virtualPage->getPermalink();				
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);
		return $result;			
	}
	
	public function getAgentListUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::AGENT_LIST);
		$permalink = $virtualPage->getPermalink();				
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);
		return $result;			
	}
	
	public function getAgentDetailUrl($includeBaseUrl = true) {
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::AGENT_DETAIL);
		$permalink = $virtualPage->getPermalink();				
		$result = $this->prependBaseUrl($permalink, $includeBaseUrl);
		return $result;			
	}
	
}