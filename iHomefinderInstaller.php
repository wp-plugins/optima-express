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

		    //We don't delete the activation token, in case the user performs an
		    //update of the plugin.
		    //delete_option(IHomefinderConstants::ACTIVATION_TOKEN_OPTION);
		    //delete_option(IHomefinderConstants::ACTIVATION_DATE_OPTION);
		   	//delete_option(IHomefinderConstants::ACTIVATION_EXPIRE_DATE_OPTION);

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
			$this->ihfAdmin->deleteAuthenticationToken() ;
			$this->ihfAdmin->updateAuthenticationToken() ;
		    $this->ihfRewriteRules->initialize();
		    $this->ihfRewriteRules->flushRules();			
		}
	}
}//end if(!class_exists('IHomefinderInstaller'))
?>