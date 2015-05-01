<?php

/**
 *
 * Singleton implementation of iHomefinderRewriteRules
 *
 * All iHomefinder requests are directed to the $rootPageName, which tries to load a wordpress page that
 * does not exist. We do not want to load a real page. We get the content from the iHomefinder services
 * and display it as a virtual Wordpress post.
 *
 * The rewrite rules below set a variable iHomefinderConstants::IHF_TYPE_URL_VAR that is used to determine
 * which VirtualPage retrieves the content from iHomefinder
 *
 * @author ihomefinder
 *
 */
class iHomefinderRewriteRules{

	private static $instance;
	private $urlFactory;
	private $rootPageName;

	private function __construct() {
		$this->urlFactory = iHomefinderUrlFactory::getInstance();
		$this->rootPageName = "index.php?pagename=non_existent_page";
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function initialize() {
		$this->addQueryVar(iHomefinderConstants::IHF_TYPE_URL_VAR);
		$this->initRewriteRules();
	}

	public function flushRules() {
		global $wp_rewrite;
		$wp_rewrite->flush_rules();
	}
	
	/**
	 * Function to initialize rewrite rules for the IHF plugin.
	 *
	 * During development we initialize and flush the rules often, but
	 * this should only be performed when the plugin is registered.
	 *
	 * We need to map certain URL patters ot an internal ihf page
	 * Once requests are routed to that page, we can handle different
	 * behavior in functions that listen for updates on that page.
	 */
	private function initRewriteRules() {
		$this->setRewriteRules("");
		//set the rules again, to support almost pretty permalinks
		$this->setRewriteRules("index.php/");
	}
	
	private function addRule($type, $pattern) {
		$matches = array();
		preg_match_all("/\{(.*?)\}/", $pattern, $matches);
		$matches = $matches[1];
		$regex = $pattern;
		$redirect = $this->rootPageName . "&" . iHomefinderConstants::IHF_TYPE_URL_VAR . "=" . $type;
		foreach($matches as $key => $value) {
			$key += 1;
			if(!empty($value)) {
				$regex = str_replace("{" . $value . "}", "([^/]+)", $regex);
				$redirect .= "&" . $value . "=\$matches[" . $key . "]";
				$this->addQueryVar($value);
			}
		}
		add_rewrite_rule($regex, $redirect, "top");
	}
	
	private function addQueryVar($name) {
		global $wp;
		$wp->add_query_var($name);
	}
	
	/**
	 * Note: The order of these search rules is important. The match will pick
	 * the first page it finds that matches any of the first few selected characters.
	
	 * For example:
	 * listing-search
	 * listing-search-results
	
	 * When "listing-search-results" is selected, the "listing-search" may be
	 * returned instead. If you encounter this problem, a simple fix is to change
	 * the first few characters of the problem page to something unique.
	 */
	private function setRewriteRules($matchRulePrefix) {
		$this->addRule(
			iHomefinderVirtualPageFactory::LISTING_ADVANCED_SEARCH_FORM,
			$matchRulePrefix . $this->urlFactory->getListingsAdvancedSearchFormUrl(false) . "/{boardId}"
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::LISTING_ADVANCED_SEARCH_FORM,
			$matchRulePrefix . $this->urlFactory->getListingsAdvancedSearchFormUrl(false)
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::OFFICE_LIST,
			$matchRulePrefix . $this->urlFactory->getOfficeListUrl(false)
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::OFFICE_DETAIL,
			$matchRulePrefix . $this->urlFactory->getOfficeDetailUrl(false) . "/{name}/{officeId}"
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::AGENT_LIST,
			$matchRulePrefix . $this->urlFactory->getAgentListUrl(false) . "/{officeId}"
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::AGENT_LIST,
			$matchRulePrefix . $this->urlFactory->getAgentListUrl(false)
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::AGENT_DETAIL,
			$matchRulePrefix . $this->urlFactory->getAgentDetailUrl(false) . "/{name}/{agentId}"
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::CONTACT_FORM,
			$matchRulePrefix . $this->urlFactory->getContactFormUrl(false)
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::VALUATION_FORM,
			$matchRulePrefix . $this->urlFactory->getValuationFormUrl(false)
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::OPEN_HOME_SEARCH_FORM,
			$matchRulePrefix . $this->urlFactory->getOpenHomeSearchFormUrl(false)
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::SOLD_FEATURED_LISTING,
			$matchRulePrefix . $this->urlFactory->getSoldFeaturedListingUrl(false)
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::SUPPLEMENTAL_LISTING,
			$matchRulePrefix . $this->urlFactory->getSupplementalListingUrl(false)
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::LISTING_SEARCH_BY_ADDRESS_RESULTS,
			$matchRulePrefix . $this->urlFactory->getListingSearchByAddressResultsUrl(false)
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::LISTING_SEARCH_BY_LISTING_ID_RESULTS,
			$matchRulePrefix . $this->urlFactory->getListingSearchByListingIdResultsUrl(false)
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::LISTING_SEARCH_FORM,
			$matchRulePrefix . $this->urlFactory->getListingsSearchFormUrl(false)
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::MAP_SEARCH_FORM,
			$matchRulePrefix . $this->urlFactory->getMapSearchFormUrl(false)
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::ORGANIZER_LOGIN_SUBMIT,
			$matchRulePrefix . $this->urlFactory->getOrganizerLoginSubmitUrl(false)
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::ORGANIZER_DELETE_SAVED_SEARCH_SUBMIT,
			$matchRulePrefix . $this->urlFactory->getOrganizerDeleteSavedSearchSubmitUrl(false)
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::ORGANIZER_DELETE_SAVED_LISTING_SUBMIT,
			$matchRulePrefix . $this->urlFactory->getOrganizerDeleteSavedListingUrl(false) . "/{savedListingId}"
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::ORGANIZER_EDIT_SAVED_SEARCH,
			$matchRulePrefix . $this->urlFactory->getOrganizerEditSavedSearchUrl(false) . "/{boardId}"
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::ORGANIZER_EDIT_SAVED_SEARCH,
			$matchRulePrefix . $this->urlFactory->getOrganizerEditSavedSearchUrl(false)
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::ORGANIZER_EDIT_SAVED_SEARCH_SUBMIT,
			$matchRulePrefix . $this->urlFactory->getOrganizerEditSavedSearchSubmitUrl(false)
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::ORGANIZER_EMAIL_UPDATES_CONFIRMATION,
			$matchRulePrefix . $this->urlFactory->getOrganizerEmailUpdatesConfirmationUrl(false)
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::ORGANIZER_HELP,
			$matchRulePrefix . $this->urlFactory->getOrganizerHelpUrl(false)
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::ORGANIZER_EDIT_SUBSCRIBER,
			$matchRulePrefix . $this->urlFactory->getOrganizerEditSubscriberUrl(false)
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::ORGANIZER_LOGIN,
			$matchRulePrefix . $this->urlFactory->getOrganizerLoginUrl(false)
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::ORGANIZER_LOGOUT,
			$matchRulePrefix . $this->urlFactory->getOrganizerLogoutUrl(false)
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::ORGANIZER_VIEW_SAVED_SEARCH,
			$matchRulePrefix . $this->urlFactory->getOrganizerViewSavedSearchUrl(false) . "/{searchProfileId}"
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::ORGANIZER_VIEW_SAVED_SEARCH_LIST,
			$matchRulePrefix . $this->urlFactory->getOrganizerViewSavedSearchListUrl(false)
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::ORGANIZER_VIEW_SAVED_LISTING_LIST,
			$matchRulePrefix . $this->urlFactory->getOrganizerViewSavedListingListUrl(false)
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::ORGANIZER_RESEND_CONFIRMATION_EMAIL,
			$matchRulePrefix . $this->urlFactory->getOrganizerResendConfirmationEmailUrl(false)
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::ORGANIZER_ACTIVATE_SUBSCRIBER,
			$matchRulePrefix . $this->urlFactory->getOrganizerActivateSubscriberUrl(false)
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::ORGANIZER_SEND_SUBSCRIBER_PASSWORD,
			$matchRulePrefix . $this->urlFactory->getOrganizerSendSubscriberPasswordUrl(false)
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::HOTSHEET_SEARCH_RESULTS,
			$matchRulePrefix . $this->urlFactory->getHotsheetSearchResultsUrl(false) . "/{name}/{hotSheetId}"
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::HOTSHEET_SEARCH_RESULTS,
			$matchRulePrefix . $this->urlFactory->getHotsheetSearchResultsUrl(false) . "/{hotSheetId}"
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::HOTSHEET_LIST,
			$matchRulePrefix . $this->urlFactory->getHotsheetListUrl(false)
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::LISTING_SOLD_DETAIL,
			$matchRulePrefix . $this->urlFactory->getListingSoldDetailUrl(false) . "/{address}/{listingNumber}/{boardId}"
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::LISTING_DETAIL,
			$matchRulePrefix . $this->urlFactory->getListingDetailUrl(false) . "/{address}/{listingNumber}/{boardId}"
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::LISTING_SEARCH_RESULTS,
			$matchRulePrefix . $this->urlFactory->getListingsSearchResultsUrl(false)
		);
		$this->addRule(
			iHomefinderVirtualPageFactory::FEATURED_SEARCH,
			$matchRulePrefix . $this->urlFactory->getFeaturedSearchResultsUrl(false)
		);
	}
	
}