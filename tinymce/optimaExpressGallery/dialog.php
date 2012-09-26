<?php
	//Bootstrap Wordpress ....
	require ('../../../../../wp-load.php');
	require ('../../../../../wp-includes/pluggable.php');
	
	include_once '../../iHomefinderConstants.php';
	include_once '../../iHomefinderAdmin.php';
	include_once '../../iHomefinderPermissions.php';
	include_once '../../iHomefinderRequestor.php';
	include_once '../../iHomefinderShortcodeDispatcher.php';
	

	function createAgentSelect(){
		$formData=IHomefinderShortcodeDispatcher::getInstance()->getGalleryFormData() ;
		if( isset($formData) && isset($formData->agentList)){
			$agentBioList=$formData->agentList ;
			$selectText = "<SELECT id='agentId' name='agentId'>";
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

	function createOfficeSelect(){
		$formData=IHomefinderShortcodeDispatcher::getInstance()->getGalleryFormData();
		if( isset($formData) && isset($formData->officeList)){
			$officeList=$formData->officeList ;
			$selectText = "<SELECT id='officeId' name='officeId'>";
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
	
	function createTopPicksSelect(){
		$formData=IHomefinderShortcodeDispatcher::getInstance()->getGalleryFormData();
		if( isset($formData) && isset($formData->hotsheetsList)){
			$hotsheetsList=$formData->hotsheetsList ;
			$selectText = "<SELECT id='toppickId' name='toppickId'>";
			foreach ($hotsheetsList as $i => $value) {
				$selectText .= "<option value='" . $hotsheetsList[$i]->hotsheetId . "'>";
				$selectText .=  $hotsheetsList[$i]->displayName ;
				$selectText .=  "</option>" ;
			}
			$selectText .= "</SELECT>";
			echo($selectText);			
		}
		else{
			echo("No Top Picks are currently available.");
		}
	}
	
	function createCitySelect(){
		$formData=IHomefinderShortcodeDispatcher::getInstance()->getGalleryFormData();
		if( isset( $formData) && isset( $formData->citiesList)){
			$citiesList=$formData->citiesList ;
			$selectText = "<SELECT id='cityId' name='cityId' size='5'>";
			foreach ($citiesList as $i => $value) {
				$selectText .= "<option value='" . $citiesList[$i]->cityId . "'>";
				$selectText .=  $citiesList[$i]->displayName ;
				$selectText .=  "</option>" ;
			}
			$selectText .= "</SELECT>";
			echo($selectText);			
		}
	}
	function createPropertyTypeSelect(){
		$formData=IHomefinderShortcodeDispatcher::getInstance()->getGalleryFormData();
		if( isset( $formData) && isset( $formData->propertyTypesList)){
			$propertyTypesList=$formData->propertyTypesList ;
			$selectText = "<SELECT id='propertyType' name='propertyType'>";
			foreach ($propertyTypesList as $i => $value) {
				$selectText .= "<option value='" . $propertyTypesList[$i]->propertyTypeCode . "'>";
				$selectText .=  $propertyTypesList[$i]->displayName ;
				$selectText .=  "</option>" ;
			}
			$selectText .= "</SELECT>";
			echo($selectText);			
		}
	}	
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Insert Listing Gallery</title>
		<script type="text/javascript"
			src="../../../../../wp-includes/js/tinymce/tiny_mce_popup.js"></script>
		<script type="text/javascript"
			src="../../../../../wp-includes/js/jquery/jquery.js"></script>	
		<script type="text/javascript" src="./js/dialog.js"></script>
		
	</head>
	<body>
		<div style="margin:5px;">
			<input name="shortcodeType" type="radio" checked="checked" onclick="jQuery('.ihfMenu').hide();jQuery('#featuredMenu').toggle();jQuery('#includeMap').show();"/>
			Featured Listings<br/>
			<input name="shortcodeType" type="radio" onclick="jQuery('.ihfMenu').hide();jQuery('#toppicksMenu').toggle();jQuery('#includeMap').show();"/>
			Top Picks</br>
			<input name="shortcodeType" type="radio" onclick="jQuery('.ihfMenu').hide();jQuery('#searchMenu').toggle();jQuery('#includeMap').show();"/>
			Search<br/>
			
			<?php if(IHomefinderPermissions::getInstance()->isAgentBioEnabled()){?>
				<input name="shortcodeType" type="radio" onclick="jQuery('.ihfMenu').hide();jQuery('#agentMenu').toggle();jQuery('#includeMap').hide();"/>
				Agent Listings<br/>
			<?php }?>
			<?php if(IHomefinderPermissions::getInstance()->isOfficeEnabled()){?>
				<input name="shortcodeType" type="radio" onclick="jQuery('.ihfMenu').hide();jQuery('#officeMenu').toggle();jQuery('#includeMap').hide();"/>
				Office Listings<br/>
			<?php }?>
		</div>		
		
		<div style="margin: 5px 5px 5px 5px;">
		<form onsubmit="return false;" action="#">
			<div id="includeMap">
				<input type="checkbox" value="true" name="includeMap"/>
				Include Map
			</div>
			<div id="agentMenu" class="ihfMenu" style="display: none;">
							
				<div class="mceActionPanel">
					<div><?php createAgentSelect(); ?></div>
				</div>
				
				<div class="mceActionPanel">
					<input type="button" class="button"
					       name="insertAgentListings" value="Insert"
					       onclick="IhfGalleryDialog.insertAgentListings('<?php echo(IHomefinderShortcodeDispatcher::getInstance()->getAgentListingsShortcode())?>');" />
				</div>
			</div>

			<div id="officeMenu" class="ihfMenu" style="display: none;">
							
				<div class="mceActionPanel">
					<div><?php createOfficeSelect(); ?></div>
				</div>
				
				<div class="mceActionPanel">
					<input type="button" class="button"
					       name="insertOfficeListings" value="Insert"
					       onclick="IhfGalleryDialog.insertOfficeListings('<?php echo(IHomefinderShortcodeDispatcher::getInstance()->getOfficeListingsShortcode())?>');" />
				</div>
			</div>
			
			<div id="toppicksMenu" class="ihfMenu" style="display: none;">
							
				<div class="mceActionPanel">
					<div><?php createTopPicksSelect(); ?></div>
				</div>
				
				<div class="mceActionPanel">
					<input type="button" class="button"
					       name="insertToppicks" value="Insert"
					       onclick="IhfGalleryDialog.insertToppicks('<?php echo(IHomefinderShortcodeDispatcher::getInstance()->getToppicksShortCode())?>');" />
				</div>
			</div>

			<div id="featuredMenu"  class="ihfMenu">
				<div class="mceActionPanel">
					<input type="button" class="button"
					       name="insertFeatured" value="Insert"
					       onclick="IhfGalleryDialog.insertFeaturedListings('<?php echo(IHomefinderShortcodeDispatcher::getInstance()->getFeaturedShortcode())	?>');" />
				</div>			
			</div>
			
			<div id="searchMenu" class="ihfMenu" style="display:none">
				<div class="mceActionPanel">
					<div id="searchMenuErrors"></div>
					<div style="float:left; margin: 10px;">
					    Cities:<br/>
						<div><?php createCitySelect(); ?></div>
						<br/>
						Property Type:<br/>
						<div><?php createPropertyTypeSelect(); ?></div>					
					</div>

					<div style="float:left; margin: 10px;">
						Bed:<br/>
						<div><input type="text" name="bed" /></div>
						Bath:<br/>
						<div><input type="text" name="bath" /></div>
						Min Price:<br/>
						<div><input type="text" name="minPrice" /></div>
						Max Price:<br/>
						<div><input type="text" name="maxPrice" /></div>
					</div>
					<div style="clear:both;"></div>
					
				</div>	
										
				<div class="mceActionPanel">
				
					<input type="button" class="button"
					       name="insertSearchResults" value="Insert"
					       onclick="IhfGalleryDialog.insertSearchResults('<?php echo(IHomefinderShortcodeDispatcher::getInstance()->getSearchResultsShortcode())?>');" />
				</div>						
			</div>
		</form>
		</div>
	</body>
</html>

