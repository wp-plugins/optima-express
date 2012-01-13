<?php
if(!class_exists('IHomefinderInstaller')){
	/**
	 * Singleton implementation of IHomefinderInstaller
	 *
	 * @author ihomefinder
	 *
	 */
	class IHomefinderInstaller{

		private static $instance ;
		private $ihfRewriteRules ;
		private $ihfAdmin ;

		private function __construct(){
			$this->ihfRewriteRules=IHomefinderRewriteRules::getInstance();
			$this->ihfAdmin=IHomefinderAdmin::getInstance();
		}

		public static function getInstance(){
			if( !isset(self::$instance)){
				self::$instance = new IHomefinderInstaller();
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
		   	$this->ihfAdmin->deleteAuthenticationToken() ;
		}


		/**
		 * Update authentictation and rewrite information after
		 * upgrade
		 */
		public function upgrade(){

			$currentVersion=get_option(IHomefinderConstants::VERSION_OPTION);

			if( $currentVersion != IHomefinderConstants::VERSION && $this->ihfAdmin->previouslyActivated() ){
				$this->ihfAdmin->deleteAuthenticationToken() ;
				$this->ihfAdmin->updateAuthenticationToken() ;
				$this->ihfRewriteRules->initialize();
				$this->ihfRewriteRules->flushRules();

				update_option(IHomefinderConstants::VERSION_OPTION, IHomefinderConstants::VERSION );
			}

		}
	}
}//end if(!class_exists('IHomefinderInstaller'))
?>