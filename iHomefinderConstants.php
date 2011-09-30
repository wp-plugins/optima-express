<?php
/**
 * iHomefinder plugin related constants.  These are used in many
 * different classes.
 *
 * @author ihomefinder
 */
if( !interface_exists('IHomefinderConstants')){
	interface IHomefinderConstants{

		const VERSION="1.1.0";
		const EXTERNAL_URL="http://www.idxre.com/services/wordpress";
		const OPTION_ACTIVATE="ihf-option-activate";
        const OPTION_PAGES="ihf-option-pages";
        
		const ACTIVATION_TOKEN_OPTION="ihf_activation_token";
		const ACTIVATION_DATE_OPTION="ihf_activation_date";
		
		//Transient value - cached authentication token
		const AUTHENTICATION_TOKEN_CACHE="ihf_authentication_token";
		//Number of seconds for transient to timeout 60*60=3600=1 hour
		const AUTHENTICATION_TOKEN_CACHE_TIMEOUT=3600;
		
		//Remember if this plugin has ever been activated on this site.
		//This affects things like link creation, when the plugin is activated.
		const IS_ACTIVATED_OPTION="ihf_links_created";
		
		//Used throughout the application to discover iHomefinder requests
		//and used to determin the proper filter to execute.
		const IHF_TYPE_URL_VAR='ihf-type';
		
		///////////////////////////////////////////////////////
		//Transient Values
		const PROPERTY_GALLERY_CACHE = "ihf_property_gallery_cache";
		//Number of seconds for transient to timeout 60*60*24= 86400 = 1 day
		const PROPERTY_GALLERY_CACHE_TIMEOUT = 86400 ;
		///////////////////////////////////////////////////////
		
		const DEBUG = false;
	}
}
?>
