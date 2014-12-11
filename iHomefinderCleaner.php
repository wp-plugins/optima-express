<?php
if( !class_exists('IHomefinderCleaner')) {
	class IHomefinderCleaner{
		private static $instance ;
		private $transientPrefix='_transient_timeout_';
		private $ihfTransientPrefix= '_transient_timeout_ihf';

		private function __construct(){

		}

		public static function getInstance(){
			if( !isset(self::$instance)){
				self::$instance = new IHomefinderCleaner();
			}
			return self::$instance;
		}

		/**
		 * Cleanup orphaned transients that are not deleted from the database.
		 * Out of the box, Wordpress will delete stale transients, when they
		 * an attempt is made to access the stale transient.  But sometimes a
		 * transient is created and no new attempts are made to access the
		 * transient.  In these cases, they transients remain in the 
		 * database indefinitely.  For these cases, we delete stale
		 * transients on an hourly basis.  This helps keep a reasonable size
		 * wp_options table.
		 * 
		 * We limit the number of transients deleted to 500 to avoid major 
		 * database hits.
		 * 
		 * IHomefinderStateManager creates transients used to remeber user state, such
		 * as last search and subscriber info.
		 */
		public function removeExpiredIhfTransients(){
			global $wpdb ;
			$time = time() ;
			$theQuery="SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE '" . $this->ihfTransientPrefix . "%' AND option_value < {$time} LIMIT 500";
			$expiredTransients = 
				$wpdb->get_col( $theQuery  );

			foreach( $expiredTransients as $transient ) {
				$key = str_replace($this->transientPrefix, '', $transient);
				delete_transient($key);
			}
		}

	}
}
?>