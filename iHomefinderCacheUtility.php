<?php

class iHomefinderCacheUtility {
	
	private static $instance;
	
	public function __construct() {
	}
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/**
	 * @param mixed $key
	 * @return mixed
	 */
	public function getItem($key) {
		$result = null;
		if(iHomefinderConstants::CACHE_ENABLED) {
			$cacheKey = $this->getKey($key);
			iHomefinderLogger::getInstance()->debug("get cached version cacheKey " . $cacheKey);
			$result = get_transient($cacheKey);
			if($result === false) {
				$result = null;
			}
			iHomefinderLogger::getInstance()->debugDumpVar($result);
		}
		return $result;
	}
	
	/**
	 * @param mixed $key
	 * @param mixed $value
	 * @param integer $expiration
	 * @return void
	 */
	public function updateItem($key, $value, $expiration) {
		$cacheKey = $this->getKey($key);
		iHomefinderLogger::getInstance()->debug("updating cache cacheKey " . $cacheKey);
		set_transient($cacheKey, $value, $expiration);
	}
	
	/**
	 * @param mixed $key
	 * @return void
	 */
	public function deleteItem($key) {
		$cacheKey = $this->getKey($key);
		iHomefinderLogger::getInstance()->debug("deleting cache cacheKey " . $cacheKey);
		delete_transient($cacheKey);
	}
	
	/**
	 * @return void
	 */
	public function deleteItems() {
		global $wpdb;
		$optionsTableName = $wpdb->options;
		$sql = "DELETE FROM " . $optionsTableName . " WHERE `option_name` LIKE '%" . iHomefinderConstants::CACHE_PREFIX . "%'";
		$wpdb->query($sql);
	}
	
	/**
	 * @param mixed $key
	 * @return string
	 */
	private function getKey($key) {
		$keyHash = md5(serialize($key));
		$cacheKey = iHomefinderConstants::CACHE_PREFIX . $keyHash;
		return $cacheKey;
	}
	
}