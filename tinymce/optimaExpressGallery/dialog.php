<?php

//Bootstrap Wordpress ....
require ('../../../../../wp-load.php');
require ('../../../../../wp-includes/pluggable.php');

include_once '../../iHomefinderConstants.php';
include_once '../../iHomefinderAdmin.php';
include_once '../../iHomefinderPermissions.php';
include_once '../../iHomefinderRequestor.php';
include_once '../../iHomefinderShortcodeDispatcher.php';
include_once '../../iHomefinderLayoutManager.php';

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
			$selectText = "<SELECT class='form-control' id='agentId' name='agentId'" . $required . ">";
			$selectText .= "<option value=''>Select One</option>";
			foreach ($agentBioList as $i => $value) {
				$selectText .= "<option value='" . $agentBioList[$i]->agentId . "'>";
				$selectText .=  $agentBioList[$i]->agentName ;
				$selectText .=  "</option>" ;
			}
			$selectText .= "</SELECT>";
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
			$selectText = "<SELECT class='form-control' id='officeId' name='officeId'" . $required . ">";
			$selectText .= "<option value=''>Select One</option>";
			foreach ($officeList as $i => $value) {
				$selectText .= "<option value='" . $officeList[$i]->officeId . "'>";
				$selectText .=  $officeList[$i]->officeName ;
				$selectText .=  "</option>" ;
			}
			$selectText .= "</SELECT>";
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
			$selectText = "<SELECT class='form-control' id='toppickId' name='toppickId'" . $required . ">";
			$selectText .= "<option value=''>Select One</option>";
			foreach ($hotsheetsList as $i => $value) {
				$selectText .= "<option value='" . $hotsheetsList[$i]->hotsheetId . "'>";
				$selectText .=  $hotsheetsList[$i]->displayName ;
				$selectText .=  "</option>" ;
			}
			$selectText .= "</SELECT>";
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
			$selectText = "<SELECT class='form-control' id='cityId' name='cityId'" . $required . ">";
			$selectText .= "<option value=''>Select One</option>";
			foreach ($citiesList as $i => $value) {
				$selectText .= "<option value='" . (string) $citiesList[$i]->cityId . "'>";
				$selectText .=  (string) $citiesList[$i]->displayName ;
				$selectText .=  "</option>" ;
			}
			$selectText .= "</SELECT>";
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
			$selectText = "<SELECT class='form-control' id='propertyType' name='propertyType'" . $required . ">";
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
			$selectText .= "</SELECT>";
			echo($selectText);			
		}
	}
	
	public function createSortSelect( $required = FALSE){
		if( $required == TRUE ) {
			$required = " required='required'";
		} else {
			$required = "";
		}
		$selectText = "<SELECT class='form-control' id='sortBy' name='sortBy'" . $required . ">";
		$selectText .= "<option value='pd'>Price Descending</option>";
		$selectText .= "<option value='pa'>Price Ascending</option>";
		$selectText .= "</SELECT>";
		echo $selectText;
	}

}

$IHomefinderShortcodeDialog = new IHomefinderShortcodeDialog();

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Insert Optima Express Shortcode</title>
		<script type="text/javascript" src="../../../../../wp-includes/js/tinymce/tiny_mce_popup.js"></script>
		<script type="text/javascript" src="../../../../../wp-includes/js/jquery/jquery.js"></script>
		<script type="text/javascript" src="./js/dialog.js"></script>
		<script src="../../js/bootstrap.js"></script>
		<link href="../../css/bootstrap.css" rel="stylesheet">
		<style type="text/css">

		</style>
	</head>
	<body>
		<div class="panel-body">
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#Listings" data-toggle="tab">Listings</a>
				</li>
				<li>
					<a href="#Search" data-toggle="tab">Search</a>
				</li>
				<li>
					<a href="#IdxPages" data-toggle="tab">IDX Pages</a>
				</li>
				<?php if(IHomefinderPermissions::getInstance()->isAgentBioEnabled()){?>
					<li>
						<a href="#Broker" data-toggle="tab">Broker</a>
					</li>
				<?php }?>
			</ul>
			<div class="tab-content">
				<div class="tab-pane fade in active" id="Listings">
					<div class="row">
						<h4></h4>
						<div class="col-xs-4">
							
							<div class="form-group">
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.listingGalleryMenu').show(); jQuery('.ihfMenu').hide();">
										Listing Gallery
									</label>
								</div>
								<div class="form-group listingGalleryMenu" style="display: none;">
									<select class="form-control" name="header" onchange="jQuery('.ihfMenu').hide(); jQuery('#' + this.value ).toggle();">
										<option value="">Type</option>
										<option value="featuredMenu">Featured Listings</option>
										<?php if(IHomefinderPermissions::getInstance()->isAgentBioEnabled()){?>
											<option value="agentMenu">Agent Listing</option>
										<?php }?>
										<?php if(IHomefinderPermissions::getInstance()->isOfficeEnabled()){?>
											<option value="officeMenu">Office Listing</option>
										<?php }?>
										<option value="toppicksMenu">Saved Search</option>
										<option value="searchMenu">Search</option>
									</select>
								</div>
								<?php if(IHomefinderLayoutManager::getInstance()->supportsListingGallery()){?>
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.listingGalleryMenu').hide(); jQuery('.ihfMenu').hide(); jQuery('#listingGalleryMenu').toggle();">
										Gallery Slider
									</label>
								</div>
								<?php }?>
							</div>
						</div>
						<div class="col-xs-8">
							<div id="featuredMenu" style="display: none;" class="ihfMenu">
								<form onsubmit="return false;" action="#">
									<div class="checkbox">
										<label class="control-label">
											<input type="checkbox" value="true" name="includeMap" />
											Include Map
										</label>
									</div>
									<div class="form-group">
										<label class="control-label">Sort</label>
										<div>
											<?php $IHomefinderShortcodeDialog->createSortSelect( TRUE ); ?>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label">Display Header</label>
										<div>
											<select class="form-control" name="header" required="required">
												<option value="true">Yes</option>
												<option value="false">No</option>
											</select>
										</div>
									</div>
									<input class="btn btn-default" type="button" name="insertFeatured" value="Insert" onclick="return IhfGalleryDialog.validateForm(this.form) && IhfGalleryDialog.insertFeaturedListings(this.form, '<?php echo(IHomefinderShortcodeDispatcher::getInstance()->getFeaturedShortcode()) ?>');" />
								</form>
							</div>
							
							
							<div id="toppicksMenu" style="display: none;" class="ihfMenu">
								<form onsubmit="return false;" action="#">
									<div class="form-group">
										<label class="control-label">Saved Search</label>
										<div>
											<?php $IHomefinderShortcodeDialog->createTopPicksSelect( TRUE ); ?>
										</div>
									</div>
									<div class="checkbox">
										<label class="control-label">
											<input type="checkbox" value="true" name="includeMap" />
											Include Map
										</label>
									</div>
									<div class="form-group">
										<label class="control-label">Sort</label>
										<div>
											<?php $IHomefinderShortcodeDialog->createSortSelect(); ?>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label">Display Header</label>
										<div>
											<select class="form-control" name="header" required="required">
												<option value="true">Yes</option>
												<option value="false">No</option>
											</select>
										</div>
									</div>
									<input class="btn btn-default" type="button" name="insertToppicks" value="Insert" onclick="return IhfGalleryDialog.validateForm(this.form) && IhfGalleryDialog.insertToppicks(this.form, '<?php echo(IHomefinderShortcodeDispatcher::getInstance()->getToppicksShortCode()) ?>');" />
								</form>
							</div>
							
							
							<div id="searchMenu" style="display: none;" class="ihfMenu">
								<form onsubmit="return false;" action="#">
									<div class="form-group">
										<label class="control-label">Cities</label>
										<div>
											<?php $IHomefinderShortcodeDialog->createCitySelect( TRUE ); ?>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label">Property Type</label>
										<div>
											<?php $IHomefinderShortcodeDialog->createPropertyTypeSelect( TRUE ); ?>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label">Bed</label>
										<div>
											<input class="form-control" type="number" name="bed" />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label">Bath</label>
										<div>
											<input class="form-control" type="number" name="bath" />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label">Min Price</label>
										<div>
											<input class="form-control" type="number" name="minPrice" />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label">Max Price</label>
										<div>
											<input class="form-control" type="number" name="maxPrice" />
										</div>
									</div>
									<div class="checkbox">
										<label class="control-label">
											<input type="checkbox" value="true" name="includeMap" />
											Include Map
										</label>
									</div>
									<div class="form-group">
										<label class="control-label">Sort</label>
										<div>
											<?php $IHomefinderShortcodeDialog->createSortSelect( TRUE ); ?>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label">Display Header</label>
										<div>
											<select class="form-control" name="header" required="required">
												<option value="true">Yes</option>
												<option value="false">No</option>
											</select>
										</div>
									</div>
									<input class="btn btn-default" type="button" name="insertSearchResults" value="Insert" onclick="return IhfGalleryDialog.validateForm(this.form) && IhfGalleryDialog.insertSearchResults(this.form, '<?php echo(IHomefinderShortcodeDispatcher::getInstance()->getSearchResultsShortcode()) ?>');" />
								</form>
							</div>
							
							
							<div id="agentMenu" style="display: none;" class="ihfMenu">
								<form onsubmit="return false;" action="#">
									<div class="form-group">
										<label class="control-label">Agent</label>
										<div>
											<?php $IHomefinderShortcodeDialog->createAgentSelect( TRUE ); ?>
										</div>
									</div>
									<input class="btn btn-default" type="button" name="insertAgentListings" value="Insert" onclick="return IhfGalleryDialog.validateForm(this.form) && IhfGalleryDialog.insertAgentListings(this.form, '<?php echo(IHomefinderShortcodeDispatcher::getInstance()->getAgentListingsShortcode()) ?>');" />
								</form>
							</div>
							
							
							<div id="officeMenu" style="display: none;" class="ihfMenu">
								<form onsubmit="return false;" action="#">
									<div class="form-group">
										<label class="control-label">Office</label>
										<div>
											<?php $IHomefinderShortcodeDialog->createOfficeSelect( TRUE ); ?>
										</div>
									</div>
									<input class="btn btn-default" type="button" name="insertOfficeListings" value="Insert" onclick="return IhfGalleryDialog.validateForm(this.form) && IhfGalleryDialog.insertOfficeListings(this.form, '<?php echo(IHomefinderShortcodeDispatcher::getInstance()->getOfficeListingsShortcode()) ?>');" />
								</form>
							</div>
							
							
							<div id="listingGalleryMenu" style="display: none;" class="ihfMenu">
								<form onsubmit="return false;" action="#">
									<div class="form-group">
										<label class="radio-inline">
											<input type="radio" name="type" checked onclick="jQuery('#TopPicksSelect').hide(); jQuery('select#toppickId').prop('selectedIndex', 0); jQuery('select#toppickId').removeAttr('required');" />
											Featured
										</label>
										<label class="radio-inline">
											<input type="radio" name="type" onclick="jQuery('#TopPicksSelect').show(); jQuery('select#toppickId').attr('required', 'required');" />
											Saved Search
										</label>
									</div>
									<div id="TopPicksSelect" class="form-group" style="display: none;">
											<?php $IHomefinderShortcodeDialog->createTopPicksSelect(); ?>
									</div>
									<div class="form-group">
										<label class="control-label">Width</label>
										<div class="input-group">
											<input class="form-control" type="number" name="width" required="required" />
											<span class="input-group-addon">px</span>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label">Height</label>
										<div class="input-group">
											<input class="form-control" type="number" name="height" required="required" />
											<span class="input-group-addon">px</span>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label">Rows</label>
										<div>
											<input class="form-control" type="number" name="rows" required="required" />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label">Columns</label>
										<div>
											<input class="form-control" type="number" name="columns" required="required" />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label">Effect</label>
										<div>
											<select class="form-control" name="effect" required="required">
												<option value="slide">Slide</option>
												<option value="fade">Fade</option>
											</select>
										</div>
									</div>
									<div class="form-group">
										<label class="control-label">Auto Advance</label>
										<div>
											<select class="form-control" name="auto" required="required">
												<option value="true">Yes</option>
												<option value="false">No</option>
											</select>
										</div>
									</div>
									<input class="btn btn-default" type="button" name="insertListingGallery" value="Insert" onclick="return IhfGalleryDialog.validateForm(this.form) && IhfGalleryDialog.insertListingGallery(this.form, '<?php echo(IHomefinderShortcodeDispatcher::getInstance()->getListingGalleryShortcode()) ?>');" />
								</form>
							</div>
							
							
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="Search">
					<h4></h4>
					<div class="col-xs-4">
						<div class="form-group">
							<div class="radio">
								<label class="control-label">
									<input name="shortcodeType" type="radio" onclick="jQuery('.ihfMenu').hide(); jQuery('#quickSearchMenu').toggle();">
									Quick Search
								</label>
							</div>
							<?php if(IHomefinderPermissions::getInstance()->isMapSearchEnabled()){?>
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.ihfMenu').hide(); jQuery('#mapSearchMenu').toggle();">
										Map Search
									</label>
								</div>
							<?php }?>
							<?php if(IHomefinderPermissions::getInstance()->isSearchByAddressEnabled()){?>
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.ihfMenu').hide(); jQuery('#searchByAddressMenu').toggle();">
										Address Search
									</label>
								</div>
							<?php }?>
							<?php if(IHomefinderPermissions::getInstance()->isSearchByListingIdEnabled()){?>
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.ihfMenu').hide(); jQuery('#searchByListingIdMenu').toggle();">
										Listing ID Search
									</label>
								</div>
							<?php }?>
						</div>
					</div>
					<div class="col-xs-8">
						<div id="quickSearchMenu" style="display: none;" class="ihfMenu">
							<form onsubmit="return false;" action="#">
								<?php if(IHomefinderLayoutManager::getInstance()->supportsMultipleQuickSearchLayouts()){?>
									<div class="form-group">
										<label class="control-label">Style</label>
										<div>
											<select class="form-control" name="style" required="required">
												<option value="">Select One</option>
												<option value="horizontal">Horizontal</option>
												<option value="twoline">Two Line</option>
												<option value="vertical">Vertical</option>
											</select>
										</div>
									</div>
								<?php }?>
								<input class="btn btn-default" type="button" name="insertQuickSearch" value="Insert" onclick="return IhfGalleryDialog.validateForm(this.form) && IhfGalleryDialog.insertQuickSearch(this.form, '<?php echo(IHomefinderShortcodeDispatcher::getInstance()->getQuickSearchShortcode()) ?>');" />
							</form>
						</div>
						<div id="mapSearchMenu" style="display: none;" class="ihfMenu">
							<form onsubmit="return false;" action="#">
							<?php if(IHomefinderLayoutManager::getInstance()->supportsMapSearchResponsiveness()){?>
									<div class="form-group">
										<label class="control-label">Width</label>
											<div class="checkbox">
												<label class="control-label">
													<input type="checkbox" name="fitToWidth" checked onchange="jQuery('#mapSearchWidth').toggle();">
													Fit to column
												</label>
											</div>
											<div class="input-group" style="display: none;" id="mapSearchWidth">
												<input class="form-control" type="text" name="width" />
												<span class="input-group-addon">px</span>
											</div>
									</div>
								<?php } else {?>
									<div class="form-group">
										<label class="control-label">Width</label>
										<div class="input-group">
											<input class="form-control" type="number" name="width" required="required" />
											<span class="input-group-addon">px</span>
										</div>
									</div>
								<?php }?>
								<div class="form-group">
									<label class="control-label">Height</label>
									<div class="input-group">
										<input class="form-control" type="number" name="height" required="required" />
										<span class="input-group-addon">px</span>
									</div>
								</div>
								<?php if(IHomefinderLayoutManager::getInstance()->supportsMapSearchCenterLatLong()){?>
									<div class="form-group">
										<label class="control-label">Center Latitude</label>
										<div>
											<input class="form-control" type="text" name="centerlat" />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label">Center Longitude</label>
										<div>
											<input class="form-control" type="text" name="centerlong" />
										</div>
									</div>
								<?php }?>
								<?php if(IHomefinderLayoutManager::getInstance()->supportsMapSearchCenterAddress()){?>
									<div class="form-group">
										<label class="control-label">Center Address</label>
										<div>
											<input class="form-control" type="text" name="address" placeholder="e.g., 1900 Addison Street, Berkeley, CA" />
										</div>
									</div>
								<?php }?>
								<div class="form-group">
									<label class="control-label">Zoom Level</label>
									<div>
										<select class="form-control" name="zoom" required="required">
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="7">7</option>
											<option value="8">8</option>
											<option value="9">9</option>
											<option value="10" selected>10</option>
											<option value="11">11</option>
											<option value="12">12</option>
											<option value="13">13</option>
											<option value="14">14</option>
											<option value="15">15</option>
											<option value="16">16</option>
											<option value="17">17</option>
											<option value="18">18</option>
											<option value="19">19</option>
											<option value="20">20</option>
										</select>
									</div>
								</div>
								
								<input class="btn btn-default" type="button" name="insertMapSearch" value="Insert" onclick="return IhfGalleryDialog.validateForm(this.form) && IhfGalleryDialog.insertMapSearch(this.form, '<?php echo(IHomefinderShortcodeDispatcher::getInstance()->getMapSearchShortcode()) ?>');" />
							</form>
						</div>
						<div id="searchByAddressMenu" style="display: none;" class="ihfMenu">
							<form onsubmit="return false;" action="#">
								<div class="form-group">
									<label class="control-label">Style</label>
									<div>
										<select class="form-control" name="style" required="required">
											<option value="">Select One</option>
											<option value="horizontal">Horizontal</option>
											<option value="vertical">Vertical</option>
										</select>
									</div>
								</div>
								<input class="btn btn-default" type="button" name="insertSearchByAddress" value="Insert" onclick="return IhfGalleryDialog.validateForm(this.form) && IhfGalleryDialog.insertSearchByAddress(this.form, '<?php echo(IHomefinderShortcodeDispatcher::getInstance()->getSearchByAddressShortcode()) ?>');" />
							</form>
						</div>
						<div id="searchByListingIdMenu" style="display: none;" class="ihfMenu">
							<form onsubmit="return false;" action="#">
								<input class="btn btn-default" type="button" name="insertSearchByListingId" value="Insert" onclick="return IhfGalleryDialog.validateForm(this.form) && IhfGalleryDialog.insertSearchByListingId(this.form, '<?php echo(IHomefinderShortcodeDispatcher::getInstance()->getSearchByListingIdShortcode()) ?>');" />
							</form>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="IdxPages">
					<h4></h4>
					<div class="col-xs-4">
						<div class="form-group">
							<div class="radio">
								<label class="control-label">
									<input name="shortcodeType" type="radio" onclick="jQuery('.ihfMenu').hide(); jQuery('#basicSearchMenu').toggle();">
									Basic Search Form
								</label>
							</div>
							<div class="radio">
								<label class="control-label">
									<input name="shortcodeType" type="radio" onclick="jQuery('.ihfMenu').hide(); jQuery('#advancedSearchMenu').toggle();">
									Advanced Search Form
								</label>
							</div>
							<div class="radio">
								<label class="control-label">
									<input name="shortcodeType" type="radio" onclick="jQuery('.ihfMenu').hide(); jQuery('#organizerLoginMenu').toggle();">
									Property Organizer Login
								</label>
							</div>
						</div>
					</div>
					<div class="col-xs-8">
						<div id="basicSearchMenu" style="display: none;" class="ihfMenu">
							<form onsubmit="return false;" action="#">
								<input class="btn btn-default" type="button" name="insertBasicSearch" value="Insert" onclick="return IhfGalleryDialog.validateForm(this.form) && IhfGalleryDialog.insertBasicSearch(this.form, '<?php echo(IHomefinderShortcodeDispatcher::getInstance()->getBasicSearchShortcode()) ?>');" />
							</form>
						</div>
						<div id="advancedSearchMenu" style="display: none;" class="ihfMenu">
							<form onsubmit="return false;" action="#">
								<input class="btn btn-default" type="button" name="insertAdvancedSearch" value="Insert" onclick="return IhfGalleryDialog.validateForm(this.form) && IhfGalleryDialog.insertAdvancedSearch(this.form, '<?php echo(IHomefinderShortcodeDispatcher::getInstance()->getAdvancedSearchShortcode()) ?>');" />
							</form>
						</div>
						<div id="organizerLoginMenu" style="display: none;" class="ihfMenu">
							<form onsubmit="return false;" action="#">
								<input class="btn btn-default" type="button" name="insertOrganizerLogin" value="Insert" onclick="return IhfGalleryDialog.validateForm(this.form) && IhfGalleryDialog.insertOrganizerLogin(this.form, '<?php echo(IHomefinderShortcodeDispatcher::getInstance()->getOrganizerLoginShortcode()) ?>');" />
							</form>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="Broker">
					<h4></h4>
					<div class="col-xs-5">
						<?php if(IHomefinderPermissions::getInstance()->isAgentBioEnabled()){?>
							<div class="form-group">
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.ihfMenu').hide(); jQuery('#agentDetailMenu').toggle();">
										Agent Bio
									</label>
								</div>
							</div>
						<?php }?>
					</div>
					<div class="col-xs-7">
						<div id="agentDetailMenu" style="display: none;" class="ihfMenu">
							<form onsubmit="return false;" action="#">
								<div class="form-group">
									<label class="control-label">Agent</label>
									<div>
										<?php $IHomefinderShortcodeDialog->createAgentSelect( TRUE ); ?>
									</div>
								</div>
								<input class="btn btn-default" type="button" name="insertAgentDetail" value="Insert" onclick="return IhfGalleryDialog.validateForm(this.form) && IhfGalleryDialog.insertAgentDetail(this.form, '<?php echo(IHomefinderShortcodeDispatcher::getInstance()->getAgentDetailShortcode()) ?>');" />
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
