
jQuery(document).ready(function() {
	
	var formField=null;
	var formFieldId=null 
	
    jQuery('#ihf_upload_agent_photo_button').click(function() {
    	//debugger;
    	jQuery('html').addClass('Image');
    	formFieldId='ihf_upload_agent_photo';
    	formField = jQuery('#' + formFieldId ).attr('name');
    	tb_show('', 'media-upload.php?type=image&TB_iframe=true');
    	return false;
    });

    jQuery('#ihf_upload_email_logo_button').click(function() {
    	//debugger;
    	jQuery('html').addClass('Image');
    	formFieldId='ihf_upload_office_logo';
    	formField = jQuery('#' + formFieldId ).attr('name');
    	tb_show('', 'media-upload.php?type=image&TB_iframe=true');
    	return false;
    });
    

    window.original_send_to_editor=window.send_to_editor ;
    window.send_to_editor = function(html) {
    	var fileurl ;
    	if( formField!= null ){
    		fileurl=jQuery('img', html).attr('src');
    		jQuery('#' + formFieldId ).val(fileurl);
    		jQuery('#' + formFieldId + '_image').attr("src", fileurl);
    		jQuery('#' + formFieldId + '_image').show();
    		tb_remove();
    		jQuery('html').removeClass('Image');
    		formfield=null;
    	}
    	else{
    		window.original_send_to_editor(html);
    	}	
    };
});