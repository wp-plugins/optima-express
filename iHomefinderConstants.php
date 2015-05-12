<?php
/**
 * iHomefinder plugin related constants.  These are used in many
 * different classes.
 *
 * @author ihomefinder
 */
interface iHomefinderConstants{

	const VERSION = "2.6.3";
	const VERSION_NAME = "Optima Express";
	const LEGACY_EXTERNAL_URL = "http://www.idxre.com/services/wordpress";
	const RESPONSIVE_EXTERNAL_URL = "http://www.idxhome.com/service/wordpress";
	const CONTROL_PANEL_EXTERNAL_URL = "http://www.idxre.com/idx/guid";

	//Group for Activation related options
	//Also used as menu slug for the Activate Menu
	const OPTION_ACTIVATE = "ihf-option-activate";
	
	//menu slug for information page
	const INFORMATION = "ihf-information";
	
	//menu slug for control panel page
	const OPTION_IDX_CONTROL_PANEL = "ihf-idx-control-panel";
	
	//Menu slug for the option pages
	const OPTION_PAGES = "ihf-option-pages";
	
	//Group for Bio related options
	const OPTION_GROUP_BIO = "ihf-option-bio";
	
	//Group for Social related options
	const OPTION_GROUP_SOCIAL = "ihf-option-social";
	
	//Group for Email Display related options
	const OPTION_GROUP_EMAIL_DISPLAY = "ihf-option-email-display";
	
	//Group for SEO City Links related options
	const OPTION_GROUP_SEO_CITY_LINKS = "ihf-option-seo-city-links";
	
	//Group for compatibility check related options
	const OPTION_GROUP_COMPATIBILITY_CHECK = "ihf-option-compatibility-check";

	//Select type of virtual pages
	//Possible vlaues are RESPONSIVE OR LEGACY
	const OPTION_LAYOUT_TYPE = "ihf-option-layout-type";
	const OPTION_LAYOUT_TYPE_RESPONSIVE = "responsive";
	const OPTION_LAYOUT_TYPE_LEGACY = "legacy";

	//Community Pages setup
	const COMMUNITY_PAGES = "ihf-community-pages";

	//Bio Information setup
	const AGENT_PHOTO_OPTION = "ihf-bio-agent-photo-option";
	const AGENT_TEXT_OPTION = "ihf-bio-agent-text-option";
	const AGENT_DESIGNATIONS_OPTION = "ihf-bio-agent-designations-option";
	const AGENT_DISPLAY_TITLE_OPTION = "ihf-agent-display-title-option";
	const AGENT_LICENSE_INFO_OPTION = "ihf-agent-license-info-option";
	const CONTACT_PHONE_OPTION = "ihf-bio-contact-phone";
	const CONTACT_EMAIL_OPTION = "ihf-bio-contact-email";
	const OFFICE_LOGO_OPTION = "ihf-bio-office-logo";

	//Social related settings
	const FACEBOOK_URL_OPTION = "ihf-social-facebook-url-option";
	const LINKEDIN_URL_OPTION = "ihf-social-linkedin-url-option";
	const TWITTER_URL_OPTION = "ihf-social-twitter-url-option";

	//Email Display related settings
	const EMAIL_HEADER_OPTION = "ihf-email-display-header-option";
	const EMAIL_FOOTER_OPTION = "ihf-email-display-footer-option";
	const EMAIL_PHOTO_OPTION = "ihf-email-photo-option";
	const EMAIL_LOGO_OPTION = "ihf-email-logo-option";
	const EMAIL_NAME_OPTION = "ihf-email-name-option";
	const EMAIL_COMPANY_OPTION = "ihf-email-company-option";
	const EMAIL_ADDRESS_LINE1_OPTION = "ihf-email-address-line1-option";
	const EMAIL_ADDRESS_LINE2_OPTION = "ihf-email-address-line2-option";
	const EMAIL_PHONE_OPTION = "ihf-email-phone-option";
	const EMAIL_DISPLAY_TYPE_OPTION = "ihf-email-display-type-option";

	//SEO City Links settings
	const SEO_CITY_LINKS_SETTINGS = "ihf-seo-city-links-settings";
	const SEO_CITY_LINKS_CITY_ZIP = "ihf-seo-city-links-city-zip";
	const SEO_CITY_LINKS_TEXT = "ihf-seo-city-links-text";
	const SEO_CITY_LINKS_MIN_PRICE = "ihf-seo-city-links-min-price";
	const SEO_CITY_LINKS_MAX_PRICE = "ihf-seo-city-links-max-price";
	const SEO_CITY_LINKS_PROPERTY_TYPE = "ihf-seo-city-links-property-type";
	const SEO_CITY_LINK_WIDTH = "ihf-seo-city-link-width";

	//Group for configuration related options
	//Also menu slug for the configuration page
	const OPTION_CONFIG_PAGE = "ihf-config-page";
	
	//Configuration realted option
	const CSS_OVERRIDE_OPTION = "ihf-css-override";
	
	//Configuration realted option
	const COLOR_SCHEME_OPTION = "ihf-color-scheme";
	
	//
	const OPTION_MOBILE_SITE_YN = "ihf-mobile-site-yn";

	//Bio related options page
	const BIO_PAGE = "ihf-bio-page";

	//Social related options page
	const SOCIAL_PAGE = "ihf-social-page";

	//Email Display related options page
	const EMAIL_BRANDING_PAGE = "ihf-email-branding-page";
	
	//
	const SEO_CITY_LINKS_PAGE = "ihf-seo-city-links-page";
	
	//
	const COMPATIBILITY_CHECK_ENABLED = "ihf-compatibility-check-enabled";
	
	//
	const VERSION_OPTION = "ihf_version_option";
	
	//key used to register and generate authentication token
	const ACTIVATION_TOKEN_OPTION = "ihf_activation_token";
	
	//token sent with every request
	const AUTHENTICATION_TOKEN_OPTION = "ihf_authentication_token";

	//Remember if this plugin has ever been activated on this site.
	//This affects things like link creation, when the plugin is activated.
	const IS_ACTIVATED_OPTION = "ihf_links_created";

	//Used throughout the application to discover iHomefinder requests
	//and used to determin the proper filter to execute.
	const IHF_TYPE_URL_VAR = "ihf-type";
	
	// Used to set the widget context.
	// A search widget should not display on a search related virtual page
	const SEARCH_WIDGET_TYPE = "searchWidget";
	const GALLERY_WIDGET_TYPE = "galleryWidget";
	
	//contact widget should not display on contact form virtual page
	const CONTACT_WIDGET_TYPE = "contactWidget";
	
	// Search widgets that can display on search pages
	const SEARCH_OTHER_WIDGET_TYPE = "searchOtherWidget";
	
	//prefix should only be up 13 character in length because cache key can only be 45 characters. prefix (13) + md5 hash (32). 
	const CACHE_PREFIX = "ihf_cache_";
	
	const DEBUG = false;
}
