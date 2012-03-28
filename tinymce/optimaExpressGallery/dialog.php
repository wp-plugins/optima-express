<?php
	//Bootstrap Wordpress ....
	require ('../../../../../wp-load.php');
	require ('../../../../../wp-includes/pluggable.php');
	
	include_once '../../iHomefinderConstants.php';
	include_once '../../iHomefinderAdmin.php';
	include_once '../../iHomefinderRequestor.php';
	include_once '../../iHomefinderShortcodeDispatcher.php';
	

	
	function createTopPicksSelect(){
		$formData=IHomefinderShortcodeDispatcher::getInstance()->getTopPicksFormData();
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
		$formData=IHomefinderShortcodeDispatcher::getInstance()->getSearchFormData();
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
		$formData=IHomefinderShortcodeDispatcher::getInstance()->getSearchFormData();
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
			<input name="shortcodeType" type="radio" checked="checked" onclick="jQuery('#featuredMenu').toggle(); jQuery('#toppicksMenu').hide();jQuery('#searchMenu').hide();"/>
			Featured Listings<br/>
			<input name="shortcodeType" type="radio" onclick="jQuery('#toppicksMenu').toggle();jQuery('#featuredMenu').hide();jQuery('#searchMenu').hide();"/>
			Top Picks</br>
			<input name="shortcodeType" type="radio" onclick="jQuery('#searchMenu').toggle(); jQuery('#toppicksMenu').hide();jQuery('#featuredMenu').hide();"/>
			Search<br/>
		</div>		
		
		<div style="margin: 25px 5px 25px 5px;">
		<form onsubmit="return false;" action="#">

			<div id="toppicksMenu" style="display: none;">
							
				<div class="mceActionPanel">
					<div><?php createTopPicksSelect(); ?></div>
				</div>
				
				<div class="mceActionPanel">
					<input type="button" class="button"
					       name="insertToppicks" value="Insert"
					       onclick="IhfTopPicksDialog.insertToppicks('<?php echo(IHomefinderShortcodeDispatcher::getInstance()->getToppicksShortCode())?>');" />
				</div>
			</div>

			<div id="featuredMenu" >
				<div class="mceActionPanel">
					<input type="button" class="button"
					       name="insertFeatured" value="Insert"
					       onclick="IhfTopPicksDialog.insertFeaturedListings('<?php echo(IHomefinderShortcodeDispatcher::getInstance()->getFeaturedShortcode())	?>');" />
				</div>			
			</div>
			
			<div id="searchMenu" style="display:none">
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
					       onclick="IhfTopPicksDialog.insertSearchResults('<?php echo(IHomefinderShortcodeDispatcher::getInstance()->getSearchResultsShortcode())?>');" />
				</div>						
			</div>
		</form>
		</div>
	</body>
</html>

