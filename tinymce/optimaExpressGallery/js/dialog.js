tinyMCEPopup.requireLangPack();

var IhfTopPicksDialog = {
	init : function() {
	},

	insertToppicks : function(toppicksShortCodeToken) {
		// Insert the contents from the input into the document
		var toppicksShortCode = "[" + toppicksShortCodeToken + " id=";
		toppicksShortCode += this.getFieldValue(document.forms[0].toppickId);
		toppicksShortCode += "]";
		tinyMCEPopup.editor.execCommand('mceInsertContent', false,
				toppicksShortCode);
		tinyMCEPopup.close();
	},

	insertFeaturedListings : function(featuredShortCodeToken) {
		// Insert the contents from the input into the document
		var featuredShortCode = "[" + featuredShortCodeToken + "]";
		tinyMCEPopup.editor.execCommand('mceInsertContent', false,
				featuredShortCode);
		tinyMCEPopup.close();
	},

	insertSearchResults : function(searchShortCodeToken) {
		var theForm=document.forms[0] ;
		var errorMessage=this.validateSearchResultsShortcode(theForm);
		if( errorMessage !== null ){
			jQuery('#searchMenuErrors').html( errorMessage );
			return;
		}
		
		// Insert the contents from the input into the document
		var searchShortCode = "[" + searchShortCodeToken;
		searchShortCode += " cityId="
				+ this.getFieldValue(theForm.cityId);
		searchShortCode += " propertyType="
				+ this.getFieldValue(theForm.propertyType);
		searchShortCode += " bed=" + this.getFieldValue(document.forms[0].bed);
		searchShortCode += " bath="
				+ this.getFieldValue(theForm.bath);
		searchShortCode += " minPrice="
				+ this.getFieldValue(theForm.minPrice);
		searchShortCode += " maxPrice="
				+ this.getFieldValue(theForm.maxPrice);
		searchShortCode += "]";
		
		

		tinyMCEPopup.editor.execCommand('mceInsertContent', false,
				searchShortCode);
		tinyMCEPopup.close();
	},

	getFieldValue : function(formField) {
		var value = formField.value;
		if (this.isEmpty( value )) {
			value = "''";
		}
		return value;
	},
	
	validateSearchResultsShortcode: function( theForm ){
		var errorMessage=null;
		if( this.isEmpty(theForm.cityId.value)){
			errorMessage="At least one city is required.";
		}
		return errorMessage;
	},
	
	isEmpty: function( value ){
		if (value === null || value.length === 0) {
			return true;
		}
		return false;
	}
}

tinyMCEPopup.onInit.add(IhfTopPicksDialog.init, IhfTopPicksDialog);
