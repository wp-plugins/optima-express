tinyMCEPopup.requireLangPack();

var IhfTopPicksDialog = {
  init : function() {
  },

  insertToppicks : function( toppicksShortCodeToken ) {
    // Insert the contents from the input into the document
    var toppicksShortCode ="[" + toppicksShortCodeToken + " id=";
    toppicksShortCode += document.forms[0].toppickId.value ;
    toppicksShortCode += "]";
    tinyMCEPopup.editor.execCommand('mceInsertContent', false, toppicksShortCode );
    tinyMCEPopup.close();
  },
  
  insertFeaturedListings : function( featuredShortCodeToken ) {
	// Insert the contents from the input into the document
	var featuredShortCode = "[" + featuredShortCodeToken + "]";
	tinyMCEPopup.editor.execCommand('mceInsertContent', false,
			featuredShortCode);
	tinyMCEPopup.close();
  },
  
  insertSearchResults : function( searchShortCodeToken ) {
		// Insert the contents from the input into the document
		var searchShortCode = "[" + searchShortCodeToken ;
		searchShortCode += " cityId=" + document.forms[0].cityId.value ;
		searchShortCode += " propertyType=" + document.forms[0].propertyType.value ;
		searchShortCode += " bed=" + document.forms[0].bed.value ;
		searchShortCode += " bath=" + document.forms[0].bath.value ;
		searchShortCode += " minPrice=" + document.forms[0].minPrice.value ;
		searchShortCode += " maxPrice=" + document.forms[0].maxPrice.value ;
		searchShortCode += "]";

		tinyMCEPopup.editor.execCommand('mceInsertContent', false,
				searchShortCode);
		tinyMCEPopup.close();
	  }  
}

tinyMCEPopup.onInit.add(IhfTopPicksDialog.init, IhfTopPicksDialog);
