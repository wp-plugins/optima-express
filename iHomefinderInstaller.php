<?php

/**
 * Singleton implementation of iHomefinderInstaller
 *
 * @author ihomefinder
 *
 */
class iHomefinderInstaller{

	private static $instance;
	private $ihfRewriteRules;
	private $ihfAdmin;

	private function __construct() {
		$this->ihfRewriteRules=iHomefinderRewriteRules::getInstance();
		$this->ihfAdmin=iHomefinderAdmin::getInstance();
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new iHomefinderInstaller();
		}
		return self::$instance;
	}

	/**
	 * Function installs the Optima Express plugin
	 * and initializes rewrite rules.
	 */
	public function install() {
		global $wpdb;
		$this->ihfRewriteRules->initialize();
		$this->ihfRewriteRules->flushRules();
		if(!$this->ihfAdmin->previouslyActivated() && !iHomefinderPermissions::getInstance()->isOmnipressSite()) {
			update_option(iHomefinderConstants::OPTION_LAYOUT_TYPE, iHomefinderConstants::OPTION_LAYOUT_TYPE_RESPONSIVE);
		}
	}

	/**
	 * Function removes Optima Express plugin related information.
	 */
	public function remove() {
		global $wpdb;
		global $wp_rewrite;
		//Clear out any rewrite rules associated with the plugin
		$this->ihfRewriteRules->flushRules();
		//Delete the authentication token
		$this->ihfAdmin->deleteAuthenticationToken();
	}


	/**
	 * Update authentictation and rewrite information after
	 * upgrade
	 */
	public function upgrade() {
		$currentVersion=get_option(iHomefinderConstants::VERSION_OPTION);
		if($currentVersion != iHomefinderConstants::VERSION) {	
			if($this->ihfAdmin->previouslyActivated()) {
				$this->ihfAdmin->updateAuthenticationToken();
				$this->ihfRewriteRules->initialize();
				$this->ihfRewriteRules->flushRules();
				update_option(iHomefinderConstants::VERSION_OPTION, iHomefinderConstants::VERSION);
			}
		}
	}
	
}