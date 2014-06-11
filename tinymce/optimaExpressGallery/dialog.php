<html>
<head>
	<title>Insert Optima Express Shortcode</title>

<script type="text/javascript">// Get the parameters passed into the window from the top frame
	var ihfArgs = top.tinymce.activeEditor.windowManager.getParams();
	console.log('wpIncludes ' + ihfArgs.wpIncludes);
    console.log('optimaExpressBaseUrl ' + ihfArgs.optimaExpressBaseUrl);


    var loadPageContent = function(){
    	console.log('loading ' +  ihfArgs.ihfAdminAjaxUrl ); 
    	jQuery.ajax( {
           	url: ihfArgs.ihfAdminAjaxUrl, 
            data: {	'action': 'ihf_tiny_mce_shortcode_dialog' },
            type: "GET",
            dataType : "html",
            success: function( data ) {
            	console.log('inserting data '); 
        	   	jQuery('#ihf-dialog-container').html(data);
    		}	 
		});
    }
    
    var afterDialogJavaScript = function(){
		var bootstrapJs=ihfArgs.optimaExpressBaseUrl + 'js/bootstrap.js' ;
    	console.log('loading ' +  bootstrapJs ); 
    	jQuery.ajax({
    		  url: bootstrapJs ,
    		  dataType: "script",
    		  complete: function(){
    			  loadPageContent();
        		  console.log("calling loadPageContent");
        		}
       	});        	
    }
    
    var afterTinyMcePopupLoad = function(){
        var dialogJs=ihfArgs.optimaExpressBaseUrl + 'tinymce/optimaExpressGallery/js/dialog.js';
    	console.log('loading ' + dialogJs); 
    	jQuery.ajax({
  		  url: dialogJs,
  		  dataType: "script",
  		  complete: function(){
  	  		  afterDialogJavaScript();
      		  console.log("calling afterDialogJavaScript");
      		}
      	});    
    }
    
    var afterJQueryLoad = function(){	
    	var ihfBootstrapCss=ihfArgs.optimaExpressBaseUrl + "css/bootstrap.css";
    	jQuery("head").append(jQuery("<link rel='stylesheet' href='" + ihfBootstrapCss + "' type='text/css' media='screen' />"));
    	var tinyMcePopupJs= ihfArgs.wpIncludes + 'js/tinymce/tiny_mce_popup.js' ;
    	console.log( tinyMcePopupJs );
    	jQuery.ajax({
    		  url: tinyMcePopupJs,
    		  dataType: "script",
    		  complete: function(){
        		  afterTinyMcePopupLoad();
        		  console.log("calling afterTinyMcePopupLoad");
        		}
        });    		    	
    }

	var head = document.getElementsByTagName('head')[0];
    var script = document.createElement('script');
    script.type = 'text/javascript';
    script.src = ihfArgs.jQueryUrl;

    // Then bind the event to the callback function.
    // There are several events for cross browser compatibility.
    script.onreadystatechange = afterJQueryLoad;
    script.onload = afterJQueryLoad;
    head.appendChild(script);

</script>
</head>
<body>
<div id="ihf-dialog-container">
	Loading data ...
</div>

	

</body>

</html>

