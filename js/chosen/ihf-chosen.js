jQuery(document).ready( function(){
    
	var config = {
	    '.ihf-chosen-select'           : {},
	    '.ihf-chosen-select-deselect'  : {allow_single_deselect:true},
	    '.ihf-chosen-select-no-single' : {disable_search_threshold:10},
	    '.ihf-chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
	    '.ihf-chosen-select-width'     : {width:"100%",
	    		disable_search_threshold:10, 
	    		placeholder_text_multiple: ' ', 
	    		placeholder_text_single: ' ' }
	}
    for (var selector in config) {
      jQuery(selector).chosen(config[selector]);
    }
});