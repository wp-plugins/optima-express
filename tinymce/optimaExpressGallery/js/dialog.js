tinyMCEPopup.requireLangPack();

var IhfGalleryDialog = {
	init : function() {
	},

	insertAgentListings : function(agentListingsShortCodeToken) {
		// Insert the contents from the input into the document
		var agentListingsShortCodeToken = "[" + agentListingsShortCodeToken + " agentId=";
		agentListingsShortCodeToken += this.getFieldValue(document.forms[0].agentId);
		agentListingsShortCodeToken += "]";
		tinyMCEPopup.editor.execCommand('mceInsertContent', false,
				agentListingsShortCodeToken);
		tinyMCEPopup.close();
	},
	insertOfficeListings : function(officeListingsShortCodeToken) {
		// Insert the contents from the input into the document
		var officeListingsShortCodeToken = "[" + officeListingsShortCodeToken + " officeId=";
		officeListingsShortCodeToken += this.getFieldValue(document.forms[0].officeId);
		officeListingsShortCodeToken += "]";
		tinyMCEPopup.editor.execCommand('mceInsertContent', false,
				officeListingsShortCodeToken);
		tinyMCEPopup.close();
	},	
	insertToppicks : function(toppicksShortCodeToken) {
		// Insert the contents from the input into the document
		var toppicksShortCode = "[" + toppicksShortCodeToken + " id=";
		toppicksShortCode += this.getFieldValue(document.forms[0].toppickId);
		toppicksShortCode += this.includeMap(document.forms[0]);
			
		toppicksShortCode += "]";
		tinyMCEPopup.editor.execCommand('mceInsertContent', false,
				toppicksShortCode);
		tinyMCEPopup.close();
	},

	insertFeaturedListings : function(featuredShortCodeToken) {
		// Insert the contents from the input into the document
		var featuredShortCode = "[" + featuredShortCodeToken ;
		featuredShortCode += this.includeMap(document.forms[0]);
		featuredShortCode += "]";
		
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
		searchShortCode += this.includeMap(document.forms[0]);
		
		searchShortCode += "]";
		
		

		tinyMCEPopup.editor.execCommand('mceInsertContent', false,
				searchShortCode);
		tinyMCEPopup.close();
	},
	
	includeMap: function( theForm ){
		if( theForm.includeMap && theForm.includeMap.checked ){
			return " includeMap=true";
		}
		
		return " includeMap=false";
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

tinyMCEPopup.onInit.add(IhfGalleryDialog.init, IhfGalleryDialog);
