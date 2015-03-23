
function selectAllCheckboxesReset(selectAllCheckbox, checkBoxesContainer) {

	// Check if all checkboxes are checked. If yes, then
	// make sure "Select All" is checked.
	var allItemsChecked = true;
	jQuery('#' + checkBoxesContainer)
	.find('input').each( 
		function(){
			if( !jQuery(this).attr('checked')){
				allItemsChecked= false ;
				// break out of the loop
				return false;
			}
		});
	
	if( allItemsChecked ) {
		jQuery('#' + selectAllCheckbox).attr('checked', 'checked' );
	} else if(jQuery('#' + selectAllCheckbox).attr('checked')) {
		jQuery('#' + selectAllCheckbox).removeAttr('checked') ;
	}
}

function selectAllCheckboxes(selectAllCheckbox, checkBoxesContainer){
	if( jQuery('#' + selectAllCheckbox).attr('checked') ){
		jQuery('#' + checkBoxesContainer)
			.find('input').each( function(){
				jQuery(this).attr('checked', 'checked')});
	}
	else{
		jQuery('#' + checkBoxesContainer)
			.find('input').each( function(){
				jQuery(this).removeAttr('checked')});
	}
}


