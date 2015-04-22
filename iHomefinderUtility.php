<?php

/**
 *
 *
 * @author ihomefinder
 */
class iHomefinderUtility {

	private static $instance;

	private function __construct() {
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new iHomefinderUtility();
		}
		return self::$instance;
	}

	public function getQueryVar($name) {
		global $wp;
		$result = $this->getVarFromArray($name, $wp->query_vars);
		return $result;
	}

	public function getRequestVar($name) {
		$result = $this->getVarFromArray($name, $_REQUEST);
		return $result;
	}

	public function getVarFromArray($name, $arrayVar) {
		$result = null;
		$name = strtolower($name);
		$arrayVar = $this->arrayKeysToLowerCase($arrayVar);
		if(array_key_exists($name, $arrayVar)) {
			$result = $arrayVar[$name];
		}
		return $result;
	}
	
	private function arrayKeysToLowerCase($arrayVar) {
		$lowerCaseKeysArray = array();
		foreach($arrayVar as $key => $value) {
			$key = strtolower($key);
			$lowerCaseKeysArray[$key] = $value;
		}
		return $lowerCaseKeysArray;
	}
	
	public function appendQueryString($url, $key, $value) {
		if(isset($value, $key)) {
			if(is_bool($value)) {
				$value = ($value) ? "true" : "false";
			}
			if($value !== null) {
				if(substr($url, -1) != "?" && substr($url, -1) != "&") {
					$url .= "&";
				}
				$url .= $key . "=" . urlencode(trim($value));
			}
		}
		return $url;
	}
	
	public function buildUrl($url, $parameters = null) {
		if(strpos($url, "?") === false) {
			$url .= "?";
		}
		if($parameters !== null && is_array($parameters)) {
			foreach($parameters as $key => $values) {
				$paramValue = null;
				if(is_array($values)) {
					foreach($values as $value) {
						if($paramValue != null) {
							$paramValue .=  ",";
						}
						$paramValue .=  $value;
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
	 * When navigating listing detail pages, we need to set the next and previous
	 * details and pass in the request, to properly create next and previous links
	 * 
	 * @param string $requestData
	 * @param int $boardId
	 * @param string $listingNumber
	 */
	public function getPreviousAndNextInformation($boardId, $listingNumber) {
		$result = array();
		$searchSummaryArray = iHomefinderStateManager::getInstance()->getSearchSummary();
		$key = $boardId . "|" . $listingNumber;
		if(isset($searchSummaryArray) && is_array($searchSummaryArray) && array_key_exists($key, $searchSummaryArray)) {
			$searchSummaryObject = $searchSummaryArray[$key];				
			if(isset($searchSummaryObject->previousId)) {
				$searchSummaryPrevious = $searchSummaryArray[$searchSummaryObject->previousId];
				$prevBoardAndListingNumber = explode("|", $searchSummaryObject->previousId);
				$result["prevBoardId"] = $prevBoardAndListingNumber[0];					
				$result["prevListingNumber"] = $prevBoardAndListingNumber[1];
				$result["prevAddress"] = urlencode($searchSummaryPrevious->address);
				$result["prevStatus"] = urlencode($searchSummaryPrevious->status);
			}
			
			if(isset($searchSummaryObject->nextId)) {
				$searchSummaryNext = $searchSummaryArray[$searchSummaryObject->nextId];
				$nextBoardAndListingNumber = explode("|", $searchSummaryObject->nextId);
				$result["nextBoardId"] = $nextBoardAndListingNumber[0];					
				$result["nextListingNumber"] = $nextBoardAndListingNumber[1];
				$result["nextAddress"] = urlencode($searchSummaryNext->address);
				$result["nextStatus"] = urlencode($searchSummaryNext->status);
			}
		}
		
		return $result;
	}

	/**
	 * Returns true is the user agent is a known web crawler
	 * @return boolean
	 */
	public function isWebCrawler() {
		$result = true;
		$userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);			
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