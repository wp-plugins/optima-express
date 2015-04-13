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
	
	/**
	 * When navigating listing detail pages, we need to set the next and previous
	 * details and pass in the request, to properly create next and previous links
	 * 
	 * @param string $requestData
	 * @param int $boardId
	 * @param string $listingNumber
	 */
	public function setPreviousAndNextInformation($requestData, $boardId, $listingNumber) {
		$searchSummaryArray = iHomefinderStateManager::getInstance()->getSearchSummary();
		$key = $boardId . "|" . $listingNumber;
		if(isset($searchSummaryArray) && is_array($searchSummaryArray) && array_key_exists($key, $searchSummaryArray)) {
			$searchSummaryObject = $searchSummaryArray[$key];				
			if(isset($searchSummaryObject->previousId)) {
				$searchSummaryPrevious = $searchSummaryArray[ $searchSummaryObject->previousId ];
				$prevBoardAndListingNumber = explode("|", $searchSummaryObject->previousId);
				$requestData .= "&prevBoardId=" . $prevBoardAndListingNumber[0];					
				$requestData .= "&prevListingNumber=" . $prevBoardAndListingNumber[1];
				$requestData .= "&prevAddress=" . urlencode($searchSummaryPrevious->address);
				$requestData .= "&prevStatus=" . urlencode($searchSummaryPrevious->status);
			}
			
			if(isset($searchSummaryObject->nextId)) {
				$searchSummaryNext = $searchSummaryArray[$searchSummaryObject->nextId];
				$nextBoardAndListingNumber = explode("|", $searchSummaryObject->nextId);
				$requestData .= "&nextBoardId=" . $nextBoardAndListingNumber[0];					
				$requestData .= "&nextListingNumber=" . $nextBoardAndListingNumber[1];
				$requestData .= "&nextAddress=" . urlencode($searchSummaryNext->address);
				$requestData .= "&nextStatus=" . urlencode($searchSummaryNext->status);
			}
		}
		
		return $requestData;
	}

	/**
	 * Returns true is the user agent is a known web crawler
	 * @return boolean
	 */
	public function isWebCrawler() {
		$result = true;
		$userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);			
		$knownCrawlersArray = array("Mediapartners-Google","Googlebot","Baiduspider","Bingbot","msnbot","Slurp","Twiceler","YandexBot");			
		foreach($knownCrawlersArray as $value) {
			if(strpos($userAgent, $value)) {
				$result = true;
				break;
			}
		}
		return $result;
	}
	
	/**
	 * 
	 * Return true if the string is empty, else return false
	 * @param unknown_type $value
	 */
	public function isStringEmpty($value) {
		$result=true;
		if($value != null && strlen($value) > 0) {
			$result=false;
		}
		return $result;
	}
}