<?php

class iHomefinderPermissions {	
	
	//names of permission object in the database
	const PERMISSIONS = "ihf_permissions";
	
	private $featuredProperties = false;
	private $organizer = false;
	private $emailUpdates = false;
	private $saveListing = false;
	private $saveSearch = false;
	private $hotSheet = false;
	private $office = false;
	private $agentBio = false;
	private $soldPending = false;
	private $valuation = false;
	private $contactForm = false;
	private $supplementalListings = false;
	private $communityPages = false;
	private $seoCityLinks = false;
	private $mapSearch = false;
	private $basicSearch = true;
	private $advancedSearch = true;
	private $openHomeSearch = true;
	private $listingResults = true;
	private $listingDetail = true;
	private $pendingAccount = false;
	private $activeTrialAccount = false;
	private $linkSearch = false;
	private $namedSearch = false;
	private $galleryShortCodes = false;
	
	private static $instance;
	
	private function __construct() {
		$permissions = get_option(self::PERMISSIONS, null);
		$this->setPermissions($permissions);
	}
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function initialize($permissions) {
		update_option(self::PERMISSIONS, $permissions);
		$this->setPermissions($permissions);
	}
	
	private function setPermissions($permissions) {
		$this->featuredProperties = $this->getProperty($permissions, "featuredProperties", false);
		$this->organizer = $this->getProperty($permissions, "organizer", false);
		$this->emailUpdates = $this->getProperty($permissions, "emailUpdates", false);
		$this->saveListing = $this->getProperty($permissions, "saveListing", false);
		$this->saveSearch = $this->getProperty($permissions, "saveSearch", false);
		$this->hotSheet = $this->getProperty($permissions, "hotSheet", false);
		$this->office = $this->getProperty($permissions, "office", false);
		$this->agentBio = $this->getProperty($permissions, "agentBio", false);
		$this->soldPending = $this->getProperty($permissions, "soldPending", false);
		$this->valuation = $this->getProperty($permissions, "valuation", false);
		$this->contactForm = $this->getProperty($permissions, "contactForm", false);
		$this->supplementalListings = $this->getProperty($permissions, "supplementalListings", false);
		$this->communityPages = $this->getProperty($permissions, "communityPages", false);
		$this->seoCityLinks = $this->getProperty($permissions, "seoCityLinks", false);
		$this->mapSearch = $this->getProperty($permissions, "mapSearch", false);
		$this->basicSearch = $this->getProperty($permissions, "basicSearch", true);
		$this->advancedSearch = $this->getProperty($permissions, "advancedSearch", true);
		$this->openHomeSearch = $this->getProperty($permissions, "openHomeSearch", true);
		$this->listingResults = $this->getProperty($permissions, "listingResults", true);
		$this->listingDetail = $this->getProperty($permissions, "listingDetail", true);
		$this->pendingAccount = $this->getProperty($permissions, "pendingAccount", false);
		$this->activeTrialAccount = $this->getProperty($permissions, "activeTrialAccount", false);
		$this->linkSearch = $this->isHotSheetEnabled();
		$this->namedSearch = $this->isHotSheetEnabled();
		$this->galleryShortCodes = $this->isHotSheetEnabled();
	}
	
	private function getProperty($permissions, $property, $default = null) {
		$result = $default;
		if(is_object($permissions) && property_exists($permissions, $property)) {
			$result = $permissions->{$property};
			if($result === "true") {
				$result = true;
			} elseif($result === "false") {
				$result = false;
			}
		}
		return $result;
	}
	
	public function isMoreInfoEnabled() {
		$result = false;
		if(iHomefinderLayoutManager::getInstance()->isResponsive() && $this->isContactFormEnabled()) {
			$result = true;
		}
		return $result;
	}
	
	public function isSearchByAddressEnabled() {
		$result = false;
		if(iHomefinderLayoutManager::getInstance()->isResponsive()) {
			$result = true;
		}
		return $result;
	}
	
	public function isSearchByListingIdEnabled() {
		$result = false;
		if(iHomefinderLayoutManager::getInstance()->isResponsive()) {
			$result = true;
		}
		return $result;
	}
	
	public function isContactFormWidgetEnabled() {
		$result = false;
		if(iHomefinderLayoutManager::getInstance()->isResponsive() && $this->isContactFormEnabled()) {
			$result = true;
		}
		return $result;
	}
	
	public function isHotsheetListWidgetEnabled() {
		$result = false;
		if(iHomefinderLayoutManager::getInstance()->isResponsive() && $this->isHotSheetEnabled()) {
			$result = true;
		}
		return $result;
	}
		
	public function isOmnipressSite() {
		$result = false;
		$clientId = get_option("clientId", null);
		if(!empty($clientId)) {
			$result = true;
		}
		return $result;
	}
	
	public function isPropertiesGalleryEnabled() {
		$result = true;
		return $result;
	}
	
	public function isQuickSearchEnabled() {
		$result = true;
		return $result;
	}
			
	public function isSocialEnabled() {
		$result = true;
		return $result;
	}
	
	public function isAgentBioWidgetEnabled() {
		$result = true;
		return $result;
	}
	
	public function isFeaturedPropertiesEnabled() {
		return $this->featuredProperties;
	}
	
	public function isOrganizerEnabled() {
		return $this->organizer;
	}
	
	public function isEmailUpdatesEnabled() {
		return $this->emailUpdates;
	}
	
	public function isSaveListingEnabled() {
		return $this->saveListing;
	}
	
	public function isSaveSearchEnabled() {
		return $this->saveSearch;
	}
	
	public function isHotSheetEnabled() {
		return $this->hotSheet;
	}
	
	public function isOfficeEnabled() {
		return $this->office;
	}
	
	public function isAgentBioEnabled() {
		return $this->agentBio;
	}
	
	public function isSoldPendingEnabled() {
		return $this->soldPending;
	}
	
	public function isValuationEnabled() {
		return $this->valuation;
	}
	
	public function isContactFormEnabled() {
		return $this->contactForm;
	}
	
	public function isSupplementalListingsEnabled() {
		return $this->supplementalListings;
	}
	
	public function isCommunityPagesEnabled() {
		return $this->communityPages;
	}
	
	public function isSeoCityLinksEnabled() {
		return $this->seoCityLinks;
	}
	
	public function isMapSearchEnabled() {
		return $this->mapSearch;
	}
	
	public function isBasicSearchEnabled() {
		return $this->basicSearch;
	}
	
	public function isAdvancedSearchEnabled() {
		return $this->advancedSearch;
	}
	
	public function isOpenHomeSearchEnabled() {
		return $this->openHomeSearch;
	}
	
	public function isListingResultsEnabled() {
		return $this->listingResults;
	}
	
	public function isListingDetailEnabled() {
		return $this->listingDetail;
	}
	
	public function isPendingAccount() {
		return $this->pendingAccount;
	}
	
	public function isActiveTrialAccount() {
		return $this->activeTrialAccount;
	}
	
	public function isLinkSearchEnabled() {
		return $this->linkSearch;
	}
	
	public function isNamedSearchEnabled() {
		return $this->namedSearch;
	}
	
	public function isGalleryShortCodesEnabled() {
		return $this->galleryShortCodes;
	}
	
}