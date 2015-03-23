<?php
if( !class_exists('IHomefinderShortcodeDialog')) {
class IHomefinderShortcodeDialog {
	
	private $formData;
	
	public function __construct() {
		$this->formData=IHomefinderShortcodeDispatcher::getInstance()->getGalleryFormData();
	}
	
	public function createAgentSelect( $required = FALSE){
		if( $required == TRUE ) {
			$required = " required='required'";
		} else {
			$required = "";
		}
		$formData=$this->formData;
		if( isset($formData)){
			$agentBioList=$formData->getAgentList() ;
			$selectText = "<select class='form-control' id='agentId' name='agentId'" . $required . ">";
			$selectText .= "<option value=''>Select One</option>";
			foreach ($agentBioList as $i => $value) {
				$selectText .= "<option value='" . $agentBioList[$i]->agentId . "'>";
				$selectText .=  $agentBioList[$i]->agentName ;
				$selectText .=  "</option>" ;
			}
			$selectText .= "</select>";
			echo($selectText);			
		}
		else{
			echo("No Agents are currently available.");
		}		
	}
	
	public function createOfficeSelect( $required = FALSE){
		if( $required == TRUE ) {
			$required = " required='required'";
		} else {
			$required = "";
		}
		$formData=$this->formData;
		if( isset($formData)){
			$officeList=$formData->getOfficeList() ;
			$selectText = "<select class='form-control' id='officeId' name='officeId'" . $required . ">";
			$selectText .= "<option value=''>Select One</option>";
			foreach ($officeList as $i => $value) {
				$selectText .= "<option value='" . $officeList[$i]->officeId . "'>";
				$selectText .=  $officeList[$i]->officeName ;
				$selectText .=  "</option>" ;
			}
			$selectText .= "</select>";
			echo($selectText);			
		}
		else{
			echo("No Agents are currently available.");
		}				
	}
	
	public function createTopPicksSelect( $required = FALSE){
		if( $required == TRUE ) {
			$required = " required='required'";
		} else {
			$required = "";
		}
		$formData=$this->formData;
		if( isset($formData)){
			$hotsheetsList=$formData->getHotsheetList() ;
			$selectText = "<select class='form-control' id='toppickId' name='toppickId'" . $required . ">";
			$selectText .= "<option value=''>Select One</option>";
			foreach ($hotsheetsList as $i => $value) {
				$selectText .= "<option value='" . $hotsheetsList[$i]->hotsheetId . "'>";
				$selectText .=  $hotsheetsList[$i]->displayName ;
				$selectText .=  "</option>" ;
			}
			$selectText .= "</select>";
			echo($selectText);			
		}
		else{
			echo("No Saved Search Pages are currently available.");
		}
	}
	
	public function createCitySelect( $required = FALSE){
		if( $required == TRUE ) {
			$required = " required='required'";
		} else {
			$required = "";
		}
		$formData=$this->formData;
		if( isset( $formData)){
			$citiesList=$formData->getCitiesList() ;
			$selectText = "<select class='form-control' id='cityId' name='cityId'" . $required . ">";
			$selectText .= "<option value=''>Select One</option>";
			foreach ($citiesList as $i => $value) {
				$selectText .= "<option value='" . (string) $citiesList[$i]->cityId . "'>";
				$selectText .=  (string) $citiesList[$i]->displayName ;
				$selectText .=  "</option>" ;
			}
			$selectText .= "</select>";
			echo($selectText);			
		}
	}
	
	public function createPropertyTypeSelect( $required = FALSE){
		if( $required == TRUE ) {
			$required = " required='required'";
		} else {
			$required = "";
		}
		$formData=$this->formData;
		if( isset( $formData)){
			$propertyTypesList=$formData->getPropertyTypesList() ;
			$selectText = "<select class='form-control' id='propertyType' name='propertyType'" . $required . ">";
			$selectText .= "<option value=''>Select One</option>";
			foreach ($propertyTypesList as $i => $value) {
				if( $propertyTypesList[$i]->propertyTypeCode == 'SFR,CND') {
					$selected = " selected='selected'";
				} else {
					$selected = "";
				}
				$selectText .= "<option value='" . $propertyTypesList[$i]->propertyTypeCode . "'" . $selected . $required . ">";
				$selectText .=  $propertyTypesList[$i]->displayName ;
				$selectText .=  "</option>" ;
			}
			$selectText .= "</select>";
			echo($selectText);			
		}
	}
	
	public function createSortSelect( $required = FALSE){
		if( $required == TRUE ) {
			$required = " required='required'";
		} else {
			$required = "";
		}
		$selectText = "<select class='form-control' id='sortBy' name='sortBy'" . $required . ">";
		$selectText .= "<option value='pd'>Price Descending</option>";
		$selectText .= "<option value='pa'>Price Ascending</option>";
		$selectText .= "</select>";
		echo $selectText;
	}

}//class
}//if
?>