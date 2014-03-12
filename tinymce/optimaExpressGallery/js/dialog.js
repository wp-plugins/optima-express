tinyMCEPopup.requireLangPack();

var IhfGalleryDialog = {
	init : function() {
	},
	
	insertFeaturedListings : function(theForm, featuredShortCodeToken) {
		// Insert the contents from the input into the document
		var featuredShortCode = "[" + featuredShortCodeToken;
		featuredShortCode += " sortBy=" + this.getFieldValue(theForm.sortBy);
		featuredShortCode += " header=" + this.getFieldValue(theForm.header);
		featuredShortCode += this.includeMap(theForm);
		featuredShortCode += "]";
		
		tinyMCEPopup.editor.execCommand('mceInsertContent', false, featuredShortCode);
		tinyMCEPopup.close();
	},
	
	insertToppicks : function(theForm, toppicksShortCodeToken) {
		// Insert the contents from the input into the document
		var toppicksShortCode = "[" + toppicksShortCodeToken;
		toppicksShortCode += " id=" + this.getFieldValue(theForm.toppickId);
		toppicksShortCode += " sortBy=" + this.getFieldValue(theForm.sortBy);
		toppicksShortCode += " header=" + this.getFieldValue(theForm.header);
		toppicksShortCode += this.includeMap(theForm);
		toppicksShortCode += "]";
		
		tinyMCEPopup.editor.execCommand('mceInsertContent', false, toppicksShortCode);
		tinyMCEPopup.close();
	},
	
	insertSearchResults : function(theForm, searchShortCodeToken) {		
		// Insert the contents from the input into the document
		var searchShortCode = "[" + searchShortCodeToken;
		searchShortCode += " cityId=" + this.getFieldValue(theForm.cityId);
		searchShortCode += " propertyType=" + this.getFieldValue(theForm.propertyType);
		searchShortCode += " bed=" + this.getFieldValue(theForm.bed);
		searchShortCode += " bath=" + this.getFieldValue(theForm.bath);
		searchShortCode += " minPrice=" + this.getFieldValue(theForm.minPrice);
		searchShortCode += " maxPrice=" + this.getFieldValue(theForm.maxPrice);
		searchShortCode += " sortBy=" + this.getFieldValue(theForm.sortBy);
		searchShortCode += " header=" + this.getFieldValue(theForm.header);
		searchShortCode += this.includeMap(theForm);
		searchShortCode += "]";
		
		tinyMCEPopup.editor.execCommand('mceInsertContent', false, searchShortCode);
		tinyMCEPopup.close();
	},
	
	insertListingGallery : function(theForm, listingGalleryShortCodeToken) {
		// Insert the contents from the input into the document
		var listingGalleryShortCode = "[" + listingGalleryShortCodeToken;
		if(theForm.toppickId.value != '') {
			listingGalleryShortCode += ' hotsheetid=' + this.getFieldValue(theForm.toppickId);
		}
		listingGalleryShortCode += ' width=' + this.getFieldValue(theForm.width);
		listingGalleryShortCode += ' height=' + this.getFieldValue(theForm.height);
		listingGalleryShortCode += ' rows=' + this.getFieldValue(theForm.rows);
		listingGalleryShortCode += ' columns=' + this.getFieldValue(theForm.columns);
		listingGalleryShortCode += ' effect=' + this.getFieldValue(theForm.effect);		
		listingGalleryShortCode += ' auto=' + this.getFieldValue(theForm.auto);
		listingGalleryShortCode += "]";
		
		tinyMCEPopup.editor.execCommand('mceInsertContent', false, listingGalleryShortCode);
		tinyMCEPopup.close();
	},
	
	insertQuickSearch : function(theForm, quickSearchShortCodeToken) {
		// Insert the contents from the input into the document
		var quickSearchShortCode = "[" + quickSearchShortCodeToken;
		if(typeof theForm.style.value != 'undefined') {
			quickSearchShortCode += ' style=' + this.getFieldValue(theForm.style);
		}
		quickSearchShortCode += "]";
		
		tinyMCEPopup.editor.execCommand('mceInsertContent', false, quickSearchShortCode);
		tinyMCEPopup.close();
	},
	
	insertSearchByAddress : function(theForm, searchByAddressShortCodeToken) {
		// Insert the contents from the input into the document
		var searchByAddressShortCode = "[" + searchByAddressShortCodeToken;
		searchByAddressShortCode += ' style=' + this.getFieldValue(theForm.style);
		searchByAddressShortCode += "]";
		
		tinyMCEPopup.editor.execCommand('mceInsertContent', false, searchByAddressShortCode);
		tinyMCEPopup.close();
	},
	
	insertSearchByListingId : function(theForm, searchByListingIdShortCodeToken) {
		// Insert the contents from the input into the document
		var searchByListingIdShortCode = "[" + searchByListingIdShortCodeToken;
		searchByListingIdShortCode += "]";
		
		tinyMCEPopup.editor.execCommand('mceInsertContent', false, searchByListingIdShortCode);
		tinyMCEPopup.close();
	},
	
	insertBasicSearch : function(theForm, basicSearchShortCodeToken) {
		// Insert the contents from the input into the document
		var basicSearchShortCode = "[" + basicSearchShortCodeToken;
		basicSearchShortCode += "]";
		
		tinyMCEPopup.editor.execCommand('mceInsertContent', false, basicSearchShortCode);
		tinyMCEPopup.close();
	},
	
	insertAdvancedSearch : function(theForm, advancedSearchShortCodeToken) {
		// Insert the contents from the input into the document
		var advancedSearchShortCode = "[" + advancedSearchShortCodeToken;
		advancedSearchShortCode += "]";
		
		tinyMCEPopup.editor.execCommand('mceInsertContent', false, advancedSearchShortCode);
		tinyMCEPopup.close();
	},
	
	insertOrganizerLogin : function(theForm, organizerLoginShortCodeToken) {
		// Insert the contents from the input into the document
		var organizerLoginShortCode = "[" + organizerLoginShortCodeToken;
		organizerLoginShortCode += "]";
		
		tinyMCEPopup.editor.execCommand('mceInsertContent', false, organizerLoginShortCode);
		tinyMCEPopup.close();
	},
	
	insertMapSearch : function(theForm, mapSearchShortCodeToken) {
		// Insert the contents from the input into the document
		var mapSearchShortCode = "[" + mapSearchShortCodeToken;
		if(typeof theForm.fitToWidth == 'undefined' || theForm.fitToWidth.checked == false) {
			mapSearchShortCode += ' width=' + this.getFieldValue(theForm.width);
		}
		mapSearchShortCode += ' height=' + this.getFieldValue(theForm.height);
		if(typeof theForm.centerlat != 'undefined') {
			mapSearchShortCode += ' centerlat=' + this.getFieldValue(theForm.centerlat);
		}
		if(typeof theForm.centerlong != 'undefined') {
			mapSearchShortCode += ' centerlong=' + this.getFieldValue(theForm.centerlong);
		}
		if(typeof theForm.address != 'undefined') {
			mapSearchShortCode += ' address="' + this.getFieldValue(theForm.address) + '"';
		}
		mapSearchShortCode += ' zoom=' + this.getFieldValue(theForm.zoom);
		mapSearchShortCode += "]";
		
		tinyMCEPopup.editor.execCommand('mceInsertContent', false, mapSearchShortCode);
		tinyMCEPopup.close();
	},
	
	insertAgentDetail : function(theForm, agentDetailShortCodeToken) {
		// Insert the contents from the input into the document
		var agentDetailShortCodeToken = "[" + agentDetailShortCodeToken;
		agentDetailShortCodeToken += " agentId=" + this.getFieldValue(theForm.agentId);
		agentDetailShortCodeToken += "]";
		
		tinyMCEPopup.editor.execCommand('mceInsertContent', false, agentDetailShortCodeToken);
		tinyMCEPopup.close();
	},

	insertAgentListings : function(theForm, agentListingsShortCodeToken) {
		// Insert the contents from the input into the document
		var agentListingsShortCodeToken = "[" + agentListingsShortCodeToken;
		agentListingsShortCodeToken += " agentId=" + this.getFieldValue(theForm.agentId);
		agentListingsShortCodeToken += "]";
		
		tinyMCEPopup.editor.execCommand('mceInsertContent', false, agentListingsShortCodeToken);
		tinyMCEPopup.close();
	},
	
	insertOfficeListings : function(theForm, officeListingsShortCodeToken) {
		// Insert the contents from the input into the document
		var officeListingsShortCodeToken = "[" + officeListingsShortCodeToken;
		officeListingsShortCodeToken += " officeId=" + this.getFieldValue(theForm.officeId);
		officeListingsShortCodeToken += "]";
		
		tinyMCEPopup.editor.execCommand('mceInsertContent', false, officeListingsShortCodeToken);
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
			value = "";
		}
		return value;
	},
		
	isEmpty: function( value ){
		if (value === null || value.length === 0) {
			return true;
		}
		return false;
	},
	
	validateForm: function( theForm ){
		returnValue = true;
		nodeList = theForm.querySelectorAll('input,select,textarea');
		for ( var i = 0, node; node = nodeList[i]; i++ ) {
			parent = node.parentNode;
			parent.className = parent.className.replace( ' has-error', '' );
			if ( node.getAttribute( 'required' ) && node.value == '' ) {
				parent.className = parent.className + ' has-error';
				returnValue = false;
			}
		}
		return returnValue;
	}
}

tinyMCEPopup.onInit.add(IhfGalleryDialog.init, IhfGalleryDialog);
