<?php

/**
 * @author ihomefinder
 */
class iHomefinderCacheUtility {
	
	public function __construct() {
		
	}
	
	public function getItem($key) {
		return null;
		$cacheKey = $this->getKey($key);
		iHomefinderLogger::getInstance()->debug('get cached version cacheKey ' . $cacheKey);
		// Fetch a saved transient
		$value = get_transient($cacheKey);
		iHomefinderLogger::getInstance()->debug('value ' . $value);
		return $value;
	}
	
	public function updateItem($key, $value, $expiration) {
		$cacheKey = $this->getKey($key);
		if(!strpos($value, "You don't have permission to access")) {
			iHomefinderLogger::getInstance()->debug('updating cache cacheKey ' . $cacheKey);
			// Set a new transient
			set_transient($cacheKey, $value, $expiration);
		}
	}
	
	public function deleteItem($key) {
		$cacheKey = $this->getKey($key);
		iHomefinderLogger::getInstance()->debug('deleting cache cacheKey ' . $cacheKey);
		// Delete a transient
		delete_transient($cacheKey);
	}
	
	public function deleteItems() {
		global $wpdb;
		$optionsTableName = $wpdb->options;
		$sql = "DELETE FROM " . $optionsTableName . " WHERE `option_name` LIKE '%". iHomefinderConstants::CACHE_PREFIX ."%'";
		$wpdb->query($sql);
	}
	
	private function getKey($key) {
		$keyHash = md5($key);
		$cacheKey = iHomefinderConstants::CACHE_PREFIX .  $keyHash;
		return $cacheKey;
	}
	
}