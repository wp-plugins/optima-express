<?php

class iHomefinderInstaller{

	private static $instance;
	private $rewriteRules;
	private $admin;

	private function __construct() {
		$this->rewriteRules = iHomefinderRewriteRules::getInstance();
		$this->admin = iHomefinderAdmin::getInstance();
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * installs the Optima Express plugin and initializes rewrite rules.
	 */
	public function install() {
		$this->rewriteRules->initialize();
		$this->rewriteRules->flushRules();
		if(!$this->admin->previouslyActivated() && !iHomefinderPermissions::getInstance()->isOmnipressSite()) {
			update_option(iHomefinderConstants::OPTION_LAYOUT_TYPE, iHomefinderConstants::OPTION_LAYOUT_TYPE_RESPONSIVE);
		}
	}

	/**
	 * removes Optima Express plugin related information.
	 */
	public function remove() {
		//Clear out any rewrite rules associated with the plugin
		$this->rewriteRules->flushRules();
	}


	/**
	 * Update authentication and rewrite information after upgrade
	 */
	public function upgrade() {
		$currentVersion = get_option(iHomefinderConstants::VERSION_OPTION, null);
		if($currentVersion !== iHomefinderConstants::VERSION) {
			if($this->admin->previouslyActivated()) {
				$this->admin->updateAuthenticationToken();
				$this->rewriteRules->initialize();
				$this->rewriteRules->flushRules();
				update_option(iHomefinderConstants::VERSION_OPTION, iHomefinderConstants::VERSION);
			}
			$this->cleanUp();
		}
	}
	
	/**
	 * used to delete old options
	 */
	private function cleanUp() {
		$options = array(
			"ihf_email_updates_enabled",
			"ihf_save_listing_enabled",
			"ihf_hotsheet_enabled",
			"ihf_featured_properties_enabled",
			"ihf_organizer_enabled",
			"ihf_gallery_shortcodes_enabled",
			"ihf_office_enabled",
			"ihf_agent_bio_enabled",
			"ihf_sold_pending_enabled",
			"ihf_valuation_enabled",
			"ihf_contact_form_enabled",
			"ihf_supplemental_listings_enabled",
			"ihf_map_search_enabled",
			"ihf_seo_city_links_enabled",
			"ihf_community_pages_enabled",
			"ihf_pending_account",
			"ihf_active_trial_account"
		);
		foreach($options as $option) {
			delete_option($option);
		}
	}
	
}