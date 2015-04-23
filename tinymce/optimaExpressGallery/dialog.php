<html>
	<head>
		<title>Insert Optima Express Shortcode</title>
		<script type="text/javascript">
			var ihfArgs = top.tinymce.activeEditor.windowManager.getParams();
			var afterJQueryLoad = function() {
				setTimeout(function() {
					jQuery("<link>", {
						rel: "stylesheet",
						href: ihfArgs.optimaExpressBaseUrl + "css/bootstrap.css"
					}).appendTo("head");
					jQuery.ajax({
						url: ihfArgs.wpIncludes + "js/tinymce/tiny_mce_popup.js",
						dataType: "script"
					});
					jQuery.ajax({
						url: ihfArgs.optimaExpressBaseUrl + "tinymce/optimaExpressGallery/js/dialog.js",
						dataType: "script"
					});
					jQuery.ajax({
						url: ihfArgs.optimaExpressBaseUrl + "js/bootstrap.js",
						dataType: "script"
				 	});
					jQuery.ajax({
					 	url: ihfArgs.ihfAdminAjaxUrl,
						data: {
							"action": "ihf_tiny_mce_shortcode_dialog"
						},
						type: "GET",
						dataType : "html",
						success: function(data) {
							jQuery("body").html(data);
						}
					});
				}, 500);				
			}
			var head = document.getElementsByTagName("head")[0];
			var script = document.createElement("script");
			script.type = "text/javascript";
			script.src = ihfArgs.jQueryUrl;
			//Then bind the event to the callback function.
			//There are several events for cross browser compatibility.
			script.onreadystatechange = afterJQueryLoad;
			script.onload = afterJQueryLoad;
			head.appendChild(script);
		</script>
	</head>
	<body>
	</body>
</html>