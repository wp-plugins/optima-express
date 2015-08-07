<?php

class iHomefinderFormData {
	
	private $initialized;
	private $hotsheets;
	private $cities; 
	private $cityZips; 
	private $propertyTypes;
	private $agents;
	private $offices;
	
	public function __construct() {
	}
	
	/**
	 * @deprecated use getHotsheets()
	 */
	public function getHotsheetList() {
		return $this->getHotsheets();
	}
	
	public function getHotsheets() {
		return $this->hotsheets;
	}
	
	public function setHotsheets($hotsheets) {
		return $this->hotsheets = $hotsheets;
	}
	
	/**
	 * @deprecated use getCities()
	 */
	public function getCitiesList() {
		return $this->getCities();
	}
	
	public function getCities() {
		return $this->cities;
	}
	
	public function setCities($cities) {
		return $this->cities = $cities;
	}
	
	/**
	 * @deprecated use getCityZips()
	 */
	public function getCityZipList() {
		return $this->getCityZips();
	}
	
	public function getCityZips() {
		return $this->cityZips;
	}
	
	public function setCityZips($cityZips) {
		return $this->cityZips = $cityZips;
	}
	
	/**
	 * @deprecated use getPropertyTypes()
	 */
	public function getPropertyTypesList() {
		return $this->getPropertyTypes();
	}
	
	public function getPropertyTypes() {
		return $this->propertyTypes;
	}
	
	public function setPropertyTypes($propertyTypes) {
		return $this->propertyTypes = $propertyTypes;
	}
	
	/**
	 * @deprecated use getAgents()
	 */
	public function getAgentList() {
		return $this->getAgents();
	}
	
	public function getAgents() {
		return $this->agents;
	}
	
	public function setAgents($agents) {
		return $this->agents = $agents;
	}
	
	/**
	 * @deprecated use getOffices()
	 */
	public function getOfficeList() {
		return $this->getOffices();
	}		
	
	public function getOffices() {
		return $this->offices;
	}		
	
	public function setOffices($offices) {
		return $this->offices = $offices;
	}		
	
}