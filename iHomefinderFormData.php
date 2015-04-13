<?php

class iHomefinderFormData {
	
	private $hotsheetsList;
	private $citiesList; 
	private $cityZipList; 
	private $propertyTypesList;
	private $agentList;
	private $officeList;
	
	public function __construct($hotsheetsList, $citiesList, $cityZipList, $propertyTypesList, $agentList, $officeList) {
		$this->hotsheetsList = $hotsheetsList;
		$this->citiesList = $citiesList;
		$this->cityZipList = $cityZipList;
		$this->propertyTypesList = $propertyTypesList;
		$this->agentList = $agentList;
		$this->officeList = $officeList;
	}
	
	public function getHotsheetList() {
		return $this->hotsheetsList;
	}
	
	public function getCitiesList() {
		return $this->citiesList;
	}
	
	public function getCityZipList() {
		return $this->cityZipList;
	}
	
	public function getPropertyTypesList() {
		return $this->propertyTypesList;
	}
	
	public function getAgentList() {
		return $this->agentList;
	}
	
	public function getOfficeList() {
		return $this->officeList;
	}		
	
}