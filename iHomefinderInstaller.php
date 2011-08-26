<?php
if(!class_exists('IHomefinderInstaller')){
	/**
	 *
	 * Singleton implementation of IHomefinderInstaller
	 *
	 * @author ihomefinder
	 *
	 */
	class IHomefinderInstaller{

		private static $instance ;
		private $ihfRewriteRules ;

		private function __construct(){
			$this->ihfRewriteRules=IHomefinderRewriteRules::getInstance();
		}

		public static function getInstance(){
			if( !isset(self::$instance)){
				self::$instance = new IHomefinderInstaller();
			}
			return self::$instance;
		}

		/**
		 * Function installs the IHF plugin and sets up required
		 * information in the database:  Creates page if needed and sets
		 * ihf related options
		 */
		public function install() {

		    global $wpdb;

		    $this->ihfRewriteRules->initialize();
		    $this->ihfRewriteRules->flushRules();

		}

		/**
		 * Function removes the IHF plugin related information like page related options
		 */
		public function remove() {
		    global $wpdb;
		    global $wp_rewrite;

		    //We don't delete the activation or authentication token, in case the user performs an
		    //update of the plugin.
		    //delete_option(IHomefinderConstants::ACTIVATION_TOKEN_OPTION);
		    //delete_option(IHomefinderConstants::ACTIVATION_DATE_OPTION);
		   	//delete_option(IHomefinderConstants::ACTIVATION_EXPIRE_DATE_OPTION);

		   	//Clear out any rewrite rules associated with the plugin
		   	$this->ihfRewriteRules->flushRules();
		}

	}
}//end if(!class_exists('IHomefinderInstaller'))
?>