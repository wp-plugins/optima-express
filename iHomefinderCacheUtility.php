<?php
if( !class_exists('IHomefinderCacheUtility')) {
	/**
	 *
	 *
	 * @author ihomefinder
	 */
	class IHomefinderCacheUtility {
		
		public function __construct() {
			
		}
		
		public function getItem( $key ) {
			$cacheKey = $this->getKey( $key );
			IHomefinderLogger::getInstance()->debug( 'get cached version cacheKey ' . $cacheKey );
			// Fetch a saved transient
			$value = get_transient( $cacheKey );
			IHomefinderLogger::getInstance()->debug( 'value ' . $value );
			return $value;
		}
		
		public function updateItem( $key, $value, $expiration ) {
			$cacheKey = $this->getKey( $key );
			if( !strpos( $value, "You don't have permission to access" ) ) {
				IHomefinderLogger::getInstance()->debug( 'updating cache cacheKey ' . $cacheKey );
				// Set a new transient
				set_transient( $cacheKey, $value, $expiration );
			}
		}
		
		public function deleteItem( $key ) {
			$cacheKey = $this->getKey( $key );
			IHomefinderLogger::getInstance()->debug( 'deleting cache cacheKey ' . $cacheKey );
			// Delete a transient
			delete_transient($cacheKey);
		}
		
		private function getKey( $key ) {
			$keyHash = md5( $key );
			$cacheKey = IHomefinderConstants::CACHE_PREFIX .  $keyHash;
			return $cacheKey;
		}
		
	}
}
?>