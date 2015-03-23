<?php

/**
 * iHomefinderSearchFormFieldsUtility Class
 * 
 * This singleton utility class is used to search form fields.
 */
class iHomefinderSearchFormFieldsUtility {
	
	private static $instance;
	
	private function __construct() {
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new iHomefinderSearchFormFieldsUtility();
		}
		return self::$instance;
	}
	
	public function getFormData() {
		$requestData = 'method=handleRequest&viewType=json&requestType=search-form-lists';
		$galleryFormData = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		//var_dump($galleryFormData);die();
		$hotsheetsList=array();
		$citiesList=array();
		$cityZipList=array();
		$propertyTypesList=array();
		$agentList=array();
		$officeList=array();
		
		if(isset($galleryFormData->hotsheetsList)) {
			$hotsheetsList =$this->convertItemValues($galleryFormData->hotsheetsList);
		}
		if(isset($galleryFormData->citiesList)) {
			$citiesList =$this->convertItemValues($galleryFormData->citiesList);
		}
		if(isset($galleryFormData->cityZipList)) {
			$cityZipList =$this->convertItemValues($galleryFormData->cityZipList);
		}
		if(isset($galleryFormData->propertyTypesList)) {
			$propertyTypesList =$this->convertItemValues($galleryFormData->propertyTypesList);
		}
		if(isset($galleryFormData->agentList)) {
			$agentList =$this->convertItemValues($galleryFormData->agentList);
		}
		if(isset($galleryFormData->officeList)) {
			$officeList =$this->convertItemValues($galleryFormData->officeList);
		}        
		
		$galleryFormData=
				new iHomefinderFormData($hotsheetsList, $citiesList, $cityZipList, $propertyTypesList, $agentList, $officeList);
					
		return $galleryFormData;
	 }	

	 /**
	  * This function was added to convert our xml based array into a proper
	  * php array for use in admin search forms.
	  * @param unknown_type $from
	  */
	 private function convertItemValues($fromValue) {
		if(iHomefinderLayoutManager::getInstance()->hasItemInSearchFormData()) {
			$result=array();
			foreach($fromValue->item as $element) {
				array_push($result, $element);
			} 
		}
		else{
			$result=$fromValue;
		}
		
		return $result;
	 }
} // class iHomefinderFormFieldsUtility



class iHomefinderFormData {
	
	private $hotsheetsList;
	private $citiesList; 
	private $cityZipList; 
	private $propertyTypesList;
	private $agentList;
	private $officeList;
	
	
	public function __construct($hotsheetsList, $citiesList, $cityZipList, $propertyTypesList, $agentList, $officeList) {
		$this->hotsheetsList=$hotsheetsList;
		$this->citiesList=$citiesList;
		$this->cityZipList=$cityZipList;
		$this->propertyTypesList=$propertyTypesList;
		$this->agentList=$agentList;
		$this->officeList=$officeList;
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
