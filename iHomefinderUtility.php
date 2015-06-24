<?php

class iHomefinderUtility {

	private static $instance;

	private function __construct() {
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function getQueryVar($name) {
		return get_query_var($name, null);
	}

	public function getRequestVar($name) {
		$result = $this->getVarFromArray($name, $_REQUEST);
		return $result;
	}

	public function getVarFromArray($key, $array) {
		$result = null;
		$key = strtolower($key);
		$array = $this->arrayKeysToLowerCase($array);
		if(array_key_exists($key, $array)) {
			$result = $array[$key];
		}
		return $result;
	}
	
	private function arrayKeysToLowerCase($array) {
		$lowerCaseKeysArray = array();
		if(is_array($array)) {
			foreach($array as $key => $value) {
				$key = strtolower($key);
				$lowerCaseKeysArray[$key] = $value;
			}
		}
		return $lowerCaseKeysArray;
	}
	
	public function appendQueryString($url, $key, $value) {
		if(isset($value, $key)) {
			if(is_bool($value)) {
				$value = ($value) ? "true" : "false";
			}
			if($value !== null) {
				if(substr($url, -1) !== "?" && substr($url, -1) !== "&") {
					$url .= "&";
				}
				$url .= $key . "=" . urlencode(trim($value));
			}
		}
		return $url;
	}
	
	public function buildUrl($url, array $parameters = null) {
		if(strpos($url, "?") === false) {
			$url .= "?";
		}
		if($parameters !== null && is_array($parameters)) {
			foreach($parameters as $key => $values) {
				$paramValue = null;
				if(is_array($values)) {
					foreach($values as $value) {
						if($paramValue !== null) {
							$paramValue .= ",";
						}
						$paramValue .= $value;
					}
				} else {
					$paramValue = $values;
				}
				$url = $this->appendQueryString($url, $key, $paramValue);
			}
		}
		return $url;
	}

	/**
	 * Returns true is the user agent is a known web crawler
	 * @return boolean
	 */
	public function isWebCrawler() {
		$result = true;
		$userAgent = strtolower($_SERVER["HTTP_USER_AGENT"]);			
		$knownCrawlersArray = array("Mediapartners-Google", "Googlebot", "Baiduspider", "Bingbot", "msnbot", "Slurp", "Twiceler", "YandexBot");			
		foreach($knownCrawlersArray as $value) {
			if(strpos($userAgent, $value)) {
				$result = true;
				break;
			}
		}
		return $result;
	}
	
}