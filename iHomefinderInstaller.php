<?php

/**
 * Singleton implementation of iHomefinderInstaller
 *
 * @author ihomefinder
 *
 */
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
			self::$instance = new iHomefinderInstaller();
		}
		return self::$instance;
	}

	/**
	 * Function installs the Optima Express plugin and initializes rewrite rules.
	 */
	public function install() {
		$this->rewriteRules->initialize();
		$this->rewriteRules->flushRules();
		if(!$this->admin->previouslyActivated() && !iHomefinderPermissions::getInstance()->isOmnipressSite()) {
			update_option(iHomefinderConstants::OPTION_LAYOUT_TYPE, iHomefinderConstants::OPTION_LAYOUT_TYPE_RESPONSIVE);
		}
	}

	/**
	 * Function removes Optima Express plugin related information.
	 */
	public function remove() {
		//Clear out any rewrite rules associated with the plugin
		$this->rewriteRules->flushRules();
	}


	/**
	 * Update authentictation and rewrite information after upgrade
	 */
	public function upgrade() {
		$currentVersion = get_option(iHomefinderConstants::VERSION_OPTION);
		if($currentVersion != iHomefinderConstants::VERSION) {	
			if($this->admin->previouslyActivated()) {
				$this->admin->updateAuthenticationToken();
				$this->rewriteRules->initialize();
				$this->rewriteRules->flushRules();
				update_option(iHomefinderConstants::VERSION_OPTION, iHomefinderConstants::VERSION);
			}
		}
	}
	
}