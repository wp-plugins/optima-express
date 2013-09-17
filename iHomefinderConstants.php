<?php
/**
 * iHomefinder plugin related constants.  These are used in many
 * different classes.
 *
 * @author ihomefinder
 */
if( !interface_exists('IHomefinderConstants')){
	interface IHomefinderConstants{

		const VERSION="1.4.4";
		const EXTERNAL_URL= "http://www.idxre.com/services/wordpress";

		//Group for Activation related options
		//Also used as menu slug for the Activate Menu
		const OPTION_ACTIVATE="ihf-option-activate";
		//menu slug for information page
		const INFORMATION="ihf-information" ;
		//Menu slug for the option pages
		const OPTION_PAGES="ihf-option-pages";
		//Group for Bio related options
		const OPTION_GROUP_BIO="ihf-option-bio";
		//Group for Social related options
		const OPTION_GROUP_SOCIAL="ihf-option-social";
		//Group for Email Display related options
		const OPTION_GROUP_EMAIL_DISPLAY="ihf-option-email-display";
		//Group for SEO City Links related options
		const OPTION_GROUP_SEO_CITY_LINKS="ihf-option-seo-city-links";

		//Community Pages setup
		const COMMUNITY_PAGES="ihf-community-pages";

		//Bio Information setup
		const AGENT_PHOTO_OPTION="ihf-bio-agent-photo-option";
		const AGENT_TEXT_OPTION="ihf-bio-agent-text-option";
		const AGENT_DESIGNATIONS_OPTION="ihf-bio-agent-designations-option";
		const AGENT_DISPLAY_TITLE_OPTION="ihf-agent-display-title-option";
		const AGENT_LICENSE_INFO_OPTION="ihf-agent-license-info-option";

		const CONTACT_PHONE_OPTION="ihf-bio-contact-phone";
		const CONTACT_EMAIL_OPTION="ihf-bio-contact-email";

		const OFFICE_LOGO_OPTION="ihf-bio-office-logo";


		//Social related settings
		const FACEBOOK_URL_OPTION="ihf-social-facebook-url-option";
		const LINKEDIN_URL_OPTION="ihf-social-linkedin-url-option";
		const TWITTER_URL_OPTION="ihf-social-twitter-url-option";

		//Email Display related settings
		const EMAIL_HEADER_OPTION="ihf-email-display-header-option";
		const EMAIL_FOOTER_OPTION="ihf-email-display-footer-option";
		const EMAIL_PHOTO_OPTION="ihf-email-photo-option";
		const EMAIL_LOGO_OPTION="ihf-email-logo-option";
		const EMAIL_NAME_OPTION="ihf-email-name-option";
		const EMAIL_COMPANY_OPTION="ihf-email-company-option";
		const EMAIL_ADDRESS_LINE1_OPTION="ihf-email-address-line1-option";
		const EMAIL_ADDRESS_LINE2_OPTION="ihf-email-address-line2-option";
		const EMAIL_PHONE_OPTION="ihf-email-phone-option";

		const EMAIL_DISPLAY_TYPE_OPTION="ihf-email-display-type-option";

		//SEO City Links settings
		const SE0_CITY_LINKS_SETTINGS="ihf-seo-city-links-settings";
		const SE0_CITY_LINKS_CITY_ZIP="ihf-seo-city-links-city-zip";
		const SE0_CITY_LINKS_TEXT="ihf-seo-city-links-text";
		const SE0_CITY_LINKS_MIN_PRICE="ihf-seo-city-links-min-price";
		const SE0_CITY_LINKS_MAX_PRICE="ihf-seo-city-links-max-price";
		const SE0_CITY_LINKS_PROPERTY_TYPE="ihf-seo-city-links-property-type";
		const SE0_CITY_LINK_WIDTH = "ihf-seo-city-link-width";



		//Activation related options EMAIL_DISPLAY_TYPE_OPTION
		const ACTIVATION_TOKEN_OPTION="ihf_activation_token";
		const ACTIVATION_DATE_OPTION="ihf_activation_date";

		//Group for configuration related options
		//Also menu slug for the configuration page
		const OPTION_CONFIG_PAGE="ihf-config-page";
		//Configuration realted option
		const CSS_OVERRIDE_OPTION="ihf-css-override";

		//Bio related options page
		const BIO_PAGE="ihf-bio-page";

		//Social related options page
		const SOCIAL_PAGE="ihf-social-page";

		//Email Display related options page
		const EMAIL_BRANDING_PAGE="ihf-email-branding-page";

		const SEO_CITY_LINKS_PAGE="ihf-seo-city-links-page";

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
		//Number of seconds for transient to timeout 60*30= 86400 = 30 minutes
		const PROPERTY_GALLERY_CACHE_TIMEOUT = 1800 ;
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
