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
		    
		    wp_schedule_event( current_time( 'timestamp' ), 'hourly', 'ihf_expired_transients_cleanup');
			
			if(!$this->ihfAdmin->previouslyActivated() && !IHomefinderPermissions::getInstance()->isOmnipressSite()) {
				update_option( IHomefinderConstants::OPTION_LAYOUT_TYPE, IHomefinderConstants::OPTION_LAYOUT_TYPE_RESPONSIVE );
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
		   	$this->ihfAdmin->deleteAuthenticationToken() ;
		   	
		   	wp_clear_scheduled_hook( array(IHomefinderCleaner::getInstance(), 'removeExpiredIhfTransients') );
		}


		/**
		 * Update authentictation and rewrite information after
		 * upgrade
		 */
		public function upgrade(){
			
			$currentVersion=get_option(IHomefinderConstants::VERSION_OPTION);

			if( $currentVersion != IHomefinderConstants::VERSION ){
				
				$this->migrateWpidxToOe();
				
				if( $this->ihfAdmin->previouslyActivated() ){
					$this->ihfAdmin->updateAuthenticationToken() ;
					$this->ihfRewriteRules->initialize();
					$this->ihfRewriteRules->flushRules();
					
					update_option(IHomefinderConstants::VERSION_OPTION, IHomefinderConstants::VERSION );
				}
			
			}
		}
		
		private function migrateWpidxToOe() {
			
			if( IHomefinderConstants::VERSION_NAME == 'WordPress IDX' && ( get_option( 'ihf-wpidx-oe-migrated' ) != 'true' && get_option( 'ihf-wpidx-oe-migrated' ) != 'error' ) ) {
				
				global $wpdb;
				
				$optionsTableName = $wpdb->options;
				$postsTableName = $wpdb->posts;
				
				//get sidebar option from db
				$result = $wpdb->get_row( "SELECT option_value FROM " . $optionsTableName . " WHERE option_name = 'sidebars_widgets';", ARRAY_A );
				$widgets = $result['option_value'];
				
				$widgets = str_replace( 'wordpressidx', 'ihomefinder', $widgets );
				$widgets = preg_replace('!s:(\d+):"(.*?)";!e', "'s:'.strlen('$2').':\"$2\";'", $widgets );
				$widgets = mysql_real_escape_string( $widgets );
				
				$sqlList = array( 
					//make a backup of each affected table.
					"CREATE TABLE " . $optionsTableName . "_bu LIKE " . $optionsTableName . ";",
					"INSERT INTO " . $optionsTableName . "_bu SELECT * FROM " . $optionsTableName . ";",
					"CREATE TABLE " . $postsTableName . "_bu LIKE " . $postsTableName . ";",
					"INSERT INTO " . $postsTableName . "_bu SELECT * FROM " . $postsTableName . ";",
					
					//remove old oe options 
					"DELETE FROM " . $optionsTableName . " WHERE option_name LIKE '%ihf%';",
					"DELETE FROM " . $optionsTableName . " WHERE option_name LIKE '%ihomefinder%';",
					
					//replace wpidx values with oe
					"UPDATE " . $optionsTableName . " SET option_value = '" . $widgets . "' WHERE option_name = 'sidebars_widgets';",
					"UPDATE " . $optionsTableName . " SET option_name = REPLACE(option_name, 'wpidx', 'ihf') WHERE option_name LIKE '%wpidx%';",
					"UPDATE " . $optionsTableName . " SET option_name = REPLACE(option_name, 'wordpressidx', 'ihomefinder') WHERE option_name LIKE '%wordpressidx%';",
					"UPDATE " . $optionsTableName . " SET option_value = REPLACE(option_value, 'wpidx', 'ihf') WHERE option_value LIKE '%wpidx%';",
					"UPDATE " . $postsTableName . " SET post_content = REPLACE(post_content, '[wordpress_idx', '[optima_express') WHERE post_content LIKE '%[wordpress_idx%';",
				);
				
				$error = FALSE;
				$result = NULL;
				
				foreach( $sqlList as $sql ) {
					if( $error === FALSE ) {
						$result = $wpdb->query( $sql );
						if( $result === FALSE ) {
							$error = TRUE;
						}
					}
				}
				
				if( $error === TRUE ) {
					update_option( 'ihf-wpidx-oe-migrated', 'error' );
				} else {
					update_option( 'ihf-wpidx-oe-migrated', 'true' );
				}
				
				header( 'Location: ' . $_SERVER['REQUEST_URI'] );
				die();
				
			}
			
		}

		
	}
}//end if(!class_exists('IHomefinderInstaller'))
?>