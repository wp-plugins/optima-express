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
		
		private static $instance ;
		
		//Property Organizer functionality
		private $organizerEnabled=false;
		private $emailUpdatesEnabled = false;
		private $saveListingEnabled = false;
		
		//Property Gallery functionality
		private $hotSheetEnabled = false;
		private $linkSearchEnabled= false;
		private $namedSearchEnabled= false;
		private $featuredPropertiesEnabled= false;
		
		//Gallery shortcodes
		private $galleryShortCodesEnabled=false;
		
		
		private function __construct(){
			$this->emailUpdatesEnabled=get_option($this->emailUpdatesOptionName, false);
			$this->saveListingEnabled=get_option($this->saveListingOptionName, false);
			$this->hotSheetEnabled=get_option($this->hotSheetOptionName, false);
			$this->linkSearchEnabled=$this->isHotSheetEnabled() ;
			$this->namedSearchEnabled=$this->isHotSheetEnabled() ;
			$this->featuredPropertiesEnabled=get_option($this->featuredPropertiesOptionName, false);
			$this->organizerEnabled=get_option($this->organizerOptionName, false);
			$this->galleryShortCodesEnabled=get_option($this->galleryShortcodesOptionName, false );
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
			
			$this->emailUpdatesEnabled=$permissions->emailUpdates;
			$this->saveListingEnabled=$permissions->saveListing;
			$this->hotSheetEnabled=$permissions->hotSheet;
			$this->linkSearchEnabled=$this->isHotSheetEnabled() ;
			$this->namedSearchEnabled=$this->isHotSheetEnabled() ;
			$this->featuredPropertiesEnabled=$permissions->featuredProperties ;
			$this->organizerEnabled=$permissions->organizer ;
			$this->galleryShortCodesEnabled=$permissions->hotSheet;
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
	}
}