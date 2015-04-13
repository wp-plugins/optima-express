<?php

/**
 * 
 * This singleton class remembers permissions.
 * 
 * @author ihomefinder
 */
class iHomefinderPermissions {	
	
	//Names of options in the database
	private $emailUpdatesOptionName = "ihf_email_updates_enabled";
	private $saveListingOptionName = "ihf_save_listing_enabled";
	private $hotSheetOptionName = "ihf_hotsheet_enabled";
	private $featuredPropertiesOptionName = "ihf_featured_properties_enabled";
	private $organizerOptionName = "ihf_organizer_enabled";
	private $galleryShortcodesOptionName = "ihf_gallery_shortcodes_enabled";
	private $officeOptionName = "ihf_office_enabled";
	private $agentBioOptionName = "ihf_agent_bio_enabled";
	private $soldPendingOptionName = "ihf_sold_pending_enabled";
	private $valuationOptionName = "ihf_valuation_enabled";
	private $contactFormOptionName = "ihf_contact_form_enabled";
	private $supplementalListingsOptionName = "ihf_supplemental_listings_enabled";
	private $mapSearchOptionName = "ihf_map_search_enabled";
	private $seoCityLinksOptionName = "ihf_seo_city_links_enabled";
	private $communityPagesOptionName = "ihf_community_pages_enabled";
	private $pendingAccountOptionName = "ihf_pending_account";
	private $activeTrialAccountOptionName = "ihf_active_trial_account";
	
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
		$this->emailUpdatesEnabled = get_option($this->emailUpdatesOptionName, false);
		$this->saveListingEnabled = get_option($this->saveListingOptionName, false);
		$this->hotSheetEnabled = get_option($this->hotSheetOptionName, false);
		$this->linkSearchEnabled = $this->isHotSheetEnabled();
		$this->namedSearchEnabled = $this->isHotSheetEnabled();
		$this->featuredPropertiesEnabled = get_option($this->featuredPropertiesOptionName, false);
		$this->organizerEnabled = get_option($this->organizerOptionName, false);
		$this->galleryShortCodesEnabled = get_option($this->galleryShortcodesOptionName, false);
		$this->officeEnabled = get_option($this->officeOptionName, false);
		$this->agentBioEnabled = get_option($this->agentBioOptionName, false);
		$this->soldPendingEnabled = get_option($this->soldPendingOptionName, false);
		$this->valuationEnabled = get_option($this->valuationOptionName, false);
		$this->contactFormEnabled = get_option($this->contactFormOptionName, false);
		$this->supplementalListingsEnabled = get_option($this->supplementalListingsOptionName, false);
		$this->mapSearchEnabled = get_option($this->mapSearchOptionName, false);
		$this->communityPagesEnabled = get_option($this->communityPagesOptionName, false);
		$this->seoCityLinksEnabled = get_option($this->seoCityLinksOptionName, false);
		$this->pendingAccount = get_option($this->pendingAccountOptionName);
		$this->activeTrialAccount = get_option($this->activeTrialAccountOptionName);
	}
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new iHomefinderPermissions();
		}
		return self::$instance;		
	}	
	
	public function initialize($permissions) {
		update_option($this->emailUpdatesOptionName, strval($permissions->emailUpdates));
		update_option($this->saveListingOptionName, strval($permissions->saveListing));
		update_option($this->hotSheetOptionName, strval($permissions->hotSheet));
		update_option($this->featuredPropertiesOptionName, strval($permissions->featuredProperties));
		update_option($this->organizerOptionName, strval($permissions->organizer));
		update_option($this->galleryShortcodesOptionName, strval($permissions->hotSheet));
		update_option($this->officeOptionName, strval($permissions->office));
		update_option($this->agentBioOptionName, strval($permissions->agentBio));
		update_option($this->soldPendingOptionName, strval($permissions->soldPending));
		update_option($this->valuationOptionName, strval($permissions->valuation));
		update_option($this->contactFormOptionName, strval($permissions->contactForm));
		update_option($this->supplementalListingsOptionName, strval($permissions->supplementalListings));
		update_option($this->communityPagesOptionName, strval($permissions->communityPages));
		update_option($this->seoCityLinksOptionName, strval($permissions->seoCityLinks));
		update_option($this->mapSearchOptionName, strval($permissions->mapSearch));
		update_option($this->pendingAccountOptionName, strval($permissions->pendingAccount));
		update_option($this->activeTrialAccountOptionName, strval($permissions->activeTrialAccount));
		
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