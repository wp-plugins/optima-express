<?php

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
		if(empty($this->formData)) {
			$this->formData = new iHomefinderFormData();
			$remoteRequest = new iHomefinderRequestor();
			$remoteRequest
				->addParameter("method", "handleRequest")
				->addParameter("viewType", "json")
				->addParameter("requestType", "search-form-lists")
			;
			$remoteRequest->setCacheExpiration(60*60);
			$remoteResponse = $remoteRequest->remoteGetRequest();
			$response = $remoteResponse->getResponse();
			if(is_object($response)) {
				if(property_exists($response, "hotsheetsList")) {
					$hotsheets = $this->convertItemValues($response->hotsheetsList);
					$this->formData->setHotsheets($hotsheets);
				}
				if(property_exists($response, "citiesList")) {
					$cities = $this->convertItemValues($response->citiesList);
					$this->formData->setCities($cities);
				}
				if(property_exists($response, "cityZipList")) {
					$cityZips = $this->convertItemValues($response->cityZipList);
					$this->formData->setCityZips($cityZips);
				}
				if(property_exists($response, "propertyTypesList")) {
					$propertyTypes = $this->convertItemValues($response->propertyTypesList);
					$this->formData->setPropertyTypes($propertyTypes);
				}
				if(property_exists($response, "agentList")) {
					$agents = $this->convertItemValues($response->agentList);
					$this->formData->setAgents($agents);
				}
				if(property_exists($response, "officeList")) {
					$offices = $this->convertItemValues($response->officeList);
					$this->formData->setOffices($offices);
				}
			}
		}
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