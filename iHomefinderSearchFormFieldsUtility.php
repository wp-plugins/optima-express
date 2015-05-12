<?php

/**
 * iHomefinderSearchFormFieldsUtility Class
 * 
 * This singleton utility class is used to search form fields.
 */
class iHomefinderSearchFormFieldsUtility {
	
	private static $instance;
	private $formData;
	
	private function __construct() {
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function getFormData() {
		if($this->formData != null) {
			return $this->formData;
		}
		$remoteRequest = new iHomefinderRequestor();
		$remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "search-form-lists")
		;
		$remoteRequest->setCacheExpiration(60*60);
		$response = $remoteRequest->remoteGetRequest();
		$hotsheetsList = array();
		$citiesList = array();
		$cityZipList = array();
		$propertyTypesList = array();
		$agentList = array();
		$officeList = array();
		if(property_exists($response, "hotsheetsList")) {
			$hotsheetsList = $this->convertItemValues($response->hotsheetsList);
		}
		if(property_exists($response, "citiesList")) {
			$citiesList = $this->convertItemValues($response->citiesList);
		}
		if(property_exists($response, "cityZipList")) {
			$cityZipList = $this->convertItemValues($response->cityZipList);
		}
		if(property_exists($response, "propertyTypesList")) {
			$propertyTypesList = $this->convertItemValues($response->propertyTypesList);
		}
		if(property_exists($response, "agentList")) {
			$agentList = $this->convertItemValues($response->agentList);
		}
		if(property_exists($response, "officeList")) {
			$officeList = $this->convertItemValues($response->officeList);
		}
		$this->formData = new iHomefinderFormData($hotsheetsList, $citiesList, $cityZipList, $propertyTypesList, $agentList, $officeList);
		return $this->formData;
	 }	

	/**
	 * This function was added to convert our xml based array into a proper
	 * php array for use in admin search forms.
	 * @param unknown_type $from
	 */
	private function convertItemValues($fromValue) {
		if(iHomefinderLayoutManager::getInstance()->hasItemInSearchFormData()) {
			$result = array();
			foreach($fromValue->item as $element) {
				$result[] = $element;
			} 
		} else {
			$result = $fromValue;
		}
		return $result;
	}
}