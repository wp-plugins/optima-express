<?php

/**
 * 
 * This singleton class remembers permissions.
 * 
 * @author ihomefinder
 */
class iHomefinderPermissions {	
	
	//Names of options in the database
	const EMAIL_UPDATES_OPTION = "ihf_email_updates_enabled";
	const SAVE_LISTING_OPTION= "ihf_save_listing_enabled";
	const HOTSHEET_OPTION = "ihf_hotsheet_enabled";
	const FEATURED_PROPERTIES_OPTION = "ihf_featured_properties_enabled";
	const ORGANIZER_OPTION = "ihf_organizer_enabled";
	const GALLERY_SHORTCODES_OPTION = "ihf_gallery_shortcodes_enabled";
	const OFFICE_OPTION = "ihf_office_enabled";
	const AGENT_BIO_OPTION = "ihf_agent_bio_enabled";
	const SOLD_PENDING_OPTION = "ihf_sold_pending_enabled";
	const VALUATION_OPTION = "ihf_valuation_enabled";
	const CONTACT_FORM_OPTION = "ihf_contact_form_enabled";
	const SUPPLEMENTAL_LISTINGS_OPTION = "ihf_supplemental_listings_enabled";
	const MAP_SEARCH_OPTION = "ihf_map_search_enabled";
	const SEO_CITY_LINKS_OPTION = "ihf_seo_city_links_enabled";
	const COMMUNITY_PAGES_OPTION = "ihf_community_pages_enabled";
	const PENDING_ACCOUNT_OPTION = "ihf_pending_account";
	const ACTIVE_TRIAL_ACCOUNT_OPTION = "ihf_active_trial_account";
	
	private $officeEnabled = false;
	private $agentBioEnabled = false;
	private $soldPendingEnabled = false;
	private $valuationEnabled = false;
	private $contactFormEnabled = false;
	private $supplementalListingsEnabled = false;
	private $organizerEnabled = false;
	private $emailUpdatesEnabled = false;
	private $saveListingEnabled = false;
	private $hotSheetEnabled = false;
	private $linkSearchEnabled = false;
	private $namedSearchEnabled = false;
	private $featuredPropertiesEnabled = false;
	private $mapSearchEnabled = false;
	private $communityPagesEnabled = false;
	private $seoCityLinksEnabled = false;
	private $galleryShortCodesEnabled = false;
	private $pendingAccount = false;
	private $activeTrialAccount = false;
	
	private static $instance;
	
	private function __construct() {
		$this->emailUpdatesEnabled = get_option(self::EMAIL_UPDATES_OPTION, false);
		$this->saveListingEnabled = get_option(self::SAVE_LISTING_OPTION, false);
		$this->hotSheetEnabled = get_option(self::HOTSHEET_OPTION, false);
		$this->linkSearchEnabled = $this->isHotSheetEnabled();
		$this->namedSearchEnabled = $this->isHotSheetEnabled();
		$this->featuredPropertiesEnabled = get_option(self::FEATURED_PROPERTIES_OPTION, false);
		$this->organizerEnabled = get_option(self::ORGANIZER_OPTION, false);
		$this->galleryShortCodesEnabled = get_option(self::GALLERY_SHORTCODES_OPTION, false);
		$this->officeEnabled = get_option(self::OFFICE_OPTION, false);
		$this->agentBioEnabled = get_option(self::AGENT_BIO_OPTION, false);
		$this->soldPendingEnabled = get_option(self::SOLD_PENDING_OPTION, false);
		$this->valuationEnabled = get_option(self::VALUATION_OPTION, false);
		$this->contactFormEnabled = get_option(self::CONTACT_FORM_OPTION, false);
		$this->supplementalListingsEnabled = get_option(self::SUPPLEMENTAL_LISTINGS_OPTION, false);
		$this->mapSearchEnabled = get_option(self::MAP_SEARCH_OPTION, false);
		$this->communityPagesEnabled = get_option(self::COMMUNITY_PAGES_OPTION, false);
		$this->seoCityLinksEnabled = get_option(self::SEO_CITY_LINKS_OPTION, false);
		$this->pendingAccount = get_option(self::PENDING_ACCOUNT_OPTION);
		$this->activeTrialAccount = get_option(self::ACTIVE_TRIAL_ACCOUNT_OPTION);
	}
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;		
	}	
	
	public function initialize($permissions) {
		update_option(self::EMAIL_UPDATES_OPTION, strval($permissions->emailUpdates));
		update_option(self::SAVE_LISTING_OPTION, strval($permissions->saveListing));
		update_option(self::HOTSHEET_OPTION, strval($permissions->hotSheet));
		update_option(self::FEATURED_PROPERTIES_OPTION, strval($permissions->featuredProperties));
		update_option(self::ORGANIZER_OPTION, strval($permissions->organizer));
		update_option(self::GALLERY_SHORTCODES_OPTION, strval($permissions->hotSheet));
		update_option(self::OFFICE_OPTION, strval($permissions->office));
		update_option(self::AGENT_BIO_OPTION, strval($permissions->agentBio));
		update_option(self::SOLD_PENDING_OPTION, strval($permissions->soldPending));
		update_option(self::VALUATION_OPTION, strval($permissions->valuation));
		update_option(self::CONTACT_FORM_OPTION, strval($permissions->contactForm));
		update_option(self::SUPPLEMENTAL_LISTINGS_OPTION, strval($permissions->supplementalListings));
		update_option(self::COMMUNITY_PAGES_OPTION, strval($permissions->communityPages));
		update_option(self::SEO_CITY_LINKS_OPTION, strval($permissions->seoCityLinks));
		update_option(self::MAP_SEARCH_OPTION, strval($permissions->mapSearch));
		update_option(self::PENDING_ACCOUNT_OPTION, strval($permissions->pendingAccount));
		update_option(self::ACTIVE_TRIAL_ACCOUNT_OPTION, strval($permissions->activeTrialAccount));
		
		$this->emailUpdatesEnabled = $permissions->emailUpdates;
		$this->saveListingEnabled = $permissions->saveListing;
		$this->hotSheetEnabled = $permissions->hotSheet;
		$this->featuredPropertiesEnabled = $permissions->featuredProperties;
		$this->organizerEnabled = $permissions->organizer;
		$this->linkSearchEnabled = $this->isHotSheetEnabled();
		$this->namedSearchEnabled = $this->isHotSheetEnabled();
		$this->galleryShortCodesEnabled = $this->isHotSheetEnabled();
		$this->officeEnabled = $permissions->office;
		$this->agentBioEnabled = $permissions->agentBio;
		$this->soldPendingEnabled = $permissions->soldPending;
		$this->valuationEnabled = $permissions->valuation;
		$this->contactFormEnabled = $permissions->contactForm;
		$this->supplementalListingsEnabled = $permissions->supplementalListings;
		$this->mapSearchEnabled = $permissions->mapSearch;
		$this->communityPagesEnabled = $permissions->communityPages;
		$this->seoCityLinksEnabled = $permissions->seoCityLinks;		
		$this->pendingAccount = $permissions->pendingAccount;
		$this->activeTrialAccount = $permissions->activeTrialAccount;
	}
	
	public function isMoreInfoEnabled() {
		if(iHomefinderLayoutManager::getInstance()->isResponsive() && $this->isContactFormEnabled()) {
			return true;
		} else {
			return false;
		}
	}
	
	public function isSearchByAddressEnabled() {
		if(iHomefinderLayoutManager::getInstance()->isResponsive()) {
			return true;
		} else {
			return false;
		}
	}
	
	public function isSearchByListingIdEnabled() {
		if(iHomefinderLayoutManager::getInstance()->isResponsive()) {
			return true;
		} else {
			return false;
		}
	}
	
	public function isContactFormWidgetEnabled() {
		if(iHomefinderLayoutManager::getInstance()->isResponsive() && $this->isContactFormEnabled()) {
			return true;
		} else {
			return false;
		}
	}
	
	public function isHotsheetListWidgetEnabled() {
		if(iHomefinderLayoutManager::getInstance()->isResponsive() && $this->isHotSheetEnabled()) {
			return true;
		} else {
			return false;
		}
	}
	
	public function isMapSearchEnabled() {
		$result = filter_var($this->mapSearchEnabled, FILTER_VALIDATE_BOOLEAN);			
		return $result;
	}
			
	public function isCommunityPagesEnabled() {
		$result = filter_var($this->communityPagesEnabled, FILTER_VALIDATE_BOOLEAN);			
		return $result;
	}
	
	public function isSeoCityLinksEnabled() {
		$result = filter_var($this->seoCityLinksEnabled, FILTER_VALIDATE_BOOLEAN);			
		return $result;
	}
	public function isEmailUpdatesEnabled() {
		$result = filter_var($this->emailUpdatesEnabled, FILTER_VALIDATE_BOOLEAN);			
		return $result;
	}
		
	public function isSaveListingEnabled() {
		$result = filter_var($this->saveListingEnabled, FILTER_VALIDATE_BOOLEAN);			
		return $result;
	}		
	
	public function isHotSheetEnabled() {
		$result = filter_var($this->hotSheetEnabled, FILTER_VALIDATE_BOOLEAN);			
		return $result;
	}				

	public function isLinkSearchEnabled() {
		$result = filter_var($this->linkSearchEnabled, FILTER_VALIDATE_BOOLEAN);			
		return $result;
	}	
	
	public function isNamedSearchEnabled() {
		$result = filter_var($this->namedSearchEnabled, FILTER_VALIDATE_BOOLEAN);			
		return $result;
	}

	public function isOrganizerEnabled() {
		$result = filter_var($this->organizerEnabled, FILTER_VALIDATE_BOOLEAN);			
		return $result;
	}	
	public function isFeaturedPropertiesEnabled() {
		$result = filter_var($this->featuredPropertiesEnabled, FILTER_VALIDATE_BOOLEAN);			
		return $result;
	}	
		
	public function isGalleryShortCodesEnabled() {
		$result = filter_var($this->galleryShortCodesEnabled, FILTER_VALIDATE_BOOLEAN);			
		return $result;
	}
	
	public function isOfficeEnabled() {
		$result = filter_var($this->officeEnabled, FILTER_VALIDATE_BOOLEAN);			
		return $result;
	}
	
	public function isAgentBioEnabled() {
		$result = filter_var($this->agentBioEnabled, FILTER_VALIDATE_BOOLEAN);			
		return $result;
	}
	
	public function isSoldPendingEnabled() {
		$result = filter_var($this->soldPendingEnabled, FILTER_VALIDATE_BOOLEAN);			
		return $result;
	}
	
	public function isValuationEnabled() {
		$result = filter_var($this->valuationEnabled, FILTER_VALIDATE_BOOLEAN);			
		return $result;
	}		
	
	public function isContactFormEnabled() {
		$result = filter_var($this->contactFormEnabled, FILTER_VALIDATE_BOOLEAN);			
		return $result;
	}		
	
	public function isSupplementalListingsEnabled() {
		$result = filter_var($this->supplementalListingsEnabled, FILTER_VALIDATE_BOOLEAN);			
		return $result;
	}		

	public function isPendingAccount() {
		$result = filter_var($this->pendingAccount, FILTER_VALIDATE_BOOLEAN);			
		return $result;
	}

	public function isActiveTrialAccount() {
		$result = filter_var($this->activeTrialAccount, FILTER_VALIDATE_BOOLEAN);
		return $result;
	}
	
	public function isOmnipressSite() {
		$result = false;
		if(get_option("clientId")) {
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
	
}