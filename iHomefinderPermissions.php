<?php
if( !class_exists('IHomefinderPermissions')) {
	/**
	 * 
	 * This singleton class remembers permissions.
	 * 
	 * @author ihomefinder
	 */
	class IHomefinderPermissions {	
		
		//Names of options in the database
		private $emailUpdatesOptionName="ihf_email_updates_enabled";
		private $saveListingOptionName="ihf_save_listing_enabled";
		private $hotSheetOptionName="ihf_hotsheet_enabled";
		private $featuredPropertiesOptionName="ihf_featured_properties_enabled";
		private $organizerOptionName="ihf_featured_properties_enabled";
		private $galleryShortcodesOptionName="ihf_gallery_shortcodes_enabled";
		
		private $officeOptionName="ihf_office_enabled";
		private $agentBioOptionName="ihf_agent_bio_enabled";
		private $soldPendingOptionName="ihf_sold_pending_enabled";
		private $valuationOptionName="ihf_valuation_enabled";
		private $contactFormOptionName="ihf_contact_form_enabled";
		private $supplementalListingsOptionName="ihf_supplemental_listings_enabled";
		
		private $mapSearchOptionName="ihf_map_search_enabled";
		private $seoCityLinksOptionName="ihf_seo_city_links_enabled";
		private $communityPagesOptionName="ihf_community_pages_enabled";
		
		private $pendingAccountOptionName="ihf_pending_account";
		
		private static $instance ;
		
		private $officeEnabled=false;
		private $agentBioEnabled=false;
		private $soldPendingEnabled=false;
		private $valuationEnabled=false;
		private $contactFormEnabled=false;
		private $supplementalListingsEnabled=false;
		
		//Property Organizer functionality
		private $organizerEnabled=false;
		private $emailUpdatesEnabled = false;
		private $saveListingEnabled = false;
		
		//Property Gallery functionality
		private $hotSheetEnabled = false;
		private $linkSearchEnabled= false;
		private $namedSearchEnabled= false;
		private $featuredPropertiesEnabled= false;
		
		private $mapSearchEnabled= false;
		private $communityPagesEnabled= false;
		private $seoCityLinksEnabled= false;
		
		//Gallery shortcodes
		private $galleryShortCodesEnabled=false;
		
		private $pendingAccount=false;
		
		
		private function __construct(){
			$this->emailUpdatesEnabled=get_option($this->emailUpdatesOptionName, false);
			$this->saveListingEnabled=get_option($this->saveListingOptionName, false);
			$this->hotSheetEnabled=get_option($this->hotSheetOptionName, false);
			$this->linkSearchEnabled=$this->isHotSheetEnabled() ;
			$this->namedSearchEnabled=$this->isHotSheetEnabled() ;
			$this->featuredPropertiesEnabled=get_option($this->featuredPropertiesOptionName, false);
			$this->organizerEnabled=get_option($this->organizerOptionName, false);
			$this->galleryShortCodesEnabled=get_option($this->galleryShortcodesOptionName, false );
			
			$this->officeEnabled=get_option($this->officeOptionName, false);
			$this->agentBioEnabled=get_option($this->agentBioOptionName, false);
			$this->soldPendingEnabled=get_option($this->soldPendingOptionName, false);
			$this->valuationEnabled=get_option($this->valuationOptionName, false);
			$this->contactFormEnabled=get_option($this->contactFormOptionName, false);
			$this->supplementalListingsEnabled=get_option($this->supplementalListingsOptionName, false);
			
			$this->mapSearchEnabled=get_option($this->mapSearchOptionName, false);
			$this->communityPagesEnabled=get_option($this->communityPagesOptionName, false);
			$this->seoCityLinksEnabled=get_option($this->seoCityLinksOptionName, false);
			
			$this->pendingAccount=get_option($this->pendingAccountOptionName);

		}
		
		public static function getInstance(){
			if( !isset(self::$instance)){
				self::$instance = new IHomefinderPermissions();
			}
			return self::$instance;		
		}	
		
		public function initialize( $permissions ){
			update_option($this->emailUpdatesOptionName, strval($permissions->emailUpdates ));
			update_option($this->saveListingOptionName, strval($permissions->saveListing ));
			update_option($this->hotSheetOptionName, strval($permissions->hotSheet ));
			update_option($this->featuredPropertiesOptionName, strval($permissions->featuredProperties ));
			update_option($this->organizerOptionName, strval($permissions->organizer ));
			update_option($this->galleryShortcodesOptionName, strval($permissions->hotSheet ));
			
			update_option($this->officeOptionName, strval($permissions->office ));
			update_option($this->agentBioOptionName, strval($permissions->agentBio ));
			update_option($this->soldPendingOptionName, strval($permissions->soldPending ));
			update_option($this->valuationOptionName, strval($permissions->valuation ));
			update_option($this->contactFormOptionName, strval($permissions->contactForm ));
			update_option($this->supplementalListingsOptionName, strval($permissions->supplementalListings ));
			
			update_option($this->communityPagesOptionName, strval($permissions->communityPages ));
			update_option($this->seoCityLinksOptionName, strval($permissions->seoCityLinks ));
			update_option($this->mapSearchOptionName, strval($permissions->mapSearch ));
			
			update_option($this->pendingAccountOptionName, strval($permissions->pendingAccount ));
			
			$this->emailUpdatesEnabled=$permissions->emailUpdates;
			$this->saveListingEnabled=$permissions->saveListing;
			$this->hotSheetEnabled=$permissions->hotSheet;
			
			$this->featuredPropertiesEnabled=$permissions->featuredProperties ;
			$this->organizerEnabled=$permissions->organizer ;
			$this->linkSearchEnabled=$this->isHotSheetEnabled() ;
			$this->namedSearchEnabled=$this->isHotSheetEnabled() ;
			$this->galleryShortCodesEnabled=$this->isHotSheetEnabled() ;
			
			$this->officeEnabled=$permissions->office;
			$this->agentBioEnabled=$permissions->agentBio;
			
			$this->soldPendingEnabled=$permissions->soldPending;
			$this->valuationEnabled=$permissions->valuation;
			$this->contactFormEnabled=$permissions->contactForm;
			$this->supplementalListingsEnabled=$permissions->supplementalListings;
			
			$this->mapSearchEnabled=$permissions->mapSearch;
			$this->communityPagesEnabled=$permissions->communityPages;
			$this->seoCityLinksEnabled=$permissions->seoCityLinks;		
			
			$this->pendingAccount=$permissions->pendingAccount ; 
		}

		public function isMapSearchEnabled(){
			return (bool) $this->mapSearchEnabled;
		}
				
		public function isCommunityPagesEnabled(){
			return (bool) $this->communityPagesEnabled;
		}
		
		public function isSeoCityLinksEnabled(){
			return (bool) $this->seoCityLinksEnabled;
		}
		public function isEmailUpdatesEnabled(){
			return (bool) $this->emailUpdatesEnabled;
		}
			
		public function isSaveListingEnabled(){
			return (bool) $this->saveListingEnabled;
		}		
		
		public function isHotSheetEnabled(){
			return (bool) $this->hotSheetEnabled;
		}				

		public function isLinkSearchEnabled(){
			return (bool) $this->linkSearchEnabled;
		}	
		
		public function isNamedSearchEnabled(){
			return (bool) $this->namedSearchEnabled;
		}

		public function isOrganizerEnabled(){
			return (bool) $this->organizerEnabled;
		}	
		public function isFeaturedPropertiesEnabled(){
			return (bool) $this->featuredPropertiesEnabled;
		}	
			
		public function isGalleryShortCodesEnabled(){
			return (bool) $this->galleryShortCodesEnabled ;
		}
		
		public function isOfficeEnabled(){
			return (bool) $this->officeEnabled ;
		}
		
		public function isAgentBioEnabled(){
			return (bool) $this->agentBioEnabled ;
		}
		
		public function isSoldPendingEnabled(){
			return (bool) $this->soldPendingEnabled ;
		}
		
		public function isValuationEnabled(){
			return (bool) $this->valuationEnabled ;
		}		
		
		public function isContactFormEnabled(){
			return (bool) $this->contactFormEnabled ;
		}		
		
		public function isSupplementalListingsEnabled(){
			return (bool) $this->supplementalListingsEnabled ;
		}		

		public function isPendingAccount(){
			return (bool) $this->pendingAccount ;
		}			
	}
}