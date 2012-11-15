<?php
/**
 * iHomefinder plugin related constants.  These are used in many
 * different classes.
 *
 * @author ihomefinder
 */
if( !interface_exists('IHomefinderConstants')){
	interface IHomefinderConstants{

		const VERSION="1.3.3";
		const EXTERNAL_URL="http://www.idxre.com/services/wordpress";

		//Group for Activation related options
		//Also used as menu slug for the Activate Menu
		const OPTION_ACTIVATE="ihf-option-activate";
		//menu slug for information page
		const INFORMATION="ihf-information" ;
		//Menu slug for the option pages
		const OPTION_PAGES="ihf-option-pages";

        //Activation related options
		const ACTIVATION_TOKEN_OPTION="ihf_activation_token";
		const ACTIVATION_DATE_OPTION="ihf_activation_date";

		//Group for configuration related options
		//Also menu slug for the configuration page
		const OPTION_CONFIG_PAGE="ihf-config-page";
		//Configuration realted option
		const CSS_OVERRIDE_OPTION="ihf-css-override";

        const VERSION_OPTION="ihf_version_option";

		//Transient value - cached authentication token
		const AUTHENTICATION_TOKEN_CACHE="ihf_authentication_token";
		//Number of seconds for transient to timeout 60*60=3600=1 hour
		//const AUTHENTICATION_TOKEN_CACHE_TIMEOUT=3600;

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


		//Used to set the widget context.
		//A search widget should not display on a
		//search related virtual page
		const SEARCH_WIDGET_TYPE="searchWidget";
		const GALLERY_WIDGET_TYPE="galleryWidget";

		const DEBUG = false;
	}
}
?>
