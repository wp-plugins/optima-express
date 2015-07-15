<?php

interface iHomefinderConstants {

	const VERSION = "2.7.3";
	const VERSION_NAME = "Optima Express";
	const LEGACY_EXTERNAL_URL = "http://www.idxre.com/services/wordpress";
	const RESPONSIVE_EXTERNAL_URL = "http://www.idxhome.com/service/wordpress";
	const CONTROL_PANEL_EXTERNAL_URL = "http://www.idxre.com/idx/guid";
	
	/*
	 * menu slugs
	 */
	const PAGE_INFORMATION = "ihf-information";
	const PAGE_ACTIVATE = "ihf-option-activate";
	const PAGE_IDX_CONTROL_PANEL = "ihf-idx-control-panel";
	const PAGE_IDX_PAGES = "ihf-option-pages";
	const PAGE_CONFIGURATION = "ihf-config-page";
	const PAGE_BIO = "ihf-bio-page";
	const PAGE_SOCIAL = "ihf-social-page";
	const PAGE_EMAIL_BRANDING = "ihf-email-branding-page";
	const PAGE_COMMUNITY_PAGES = "ihf-community-pages";
	const PAGE_SEO_CITY_LINKS = "ihf-seo-city-links-page";
	
	/*
	 * activation options
	 */
	const OPTION_GROUP_ACTIVATE = "ihf-option-activate";
	const ACTIVATION_TOKEN_OPTION = "ihf_activation_token"; //key used to register and generate authentication token
	const AUTHENTICATION_TOKEN_OPTION = "ihf_authentication_token"; //token sent with every request
	
	/*
	 * IDX page options
	 */
	const OPTION_VIRTUAL_PAGE_CONFIG = "ihf-virtual-page-config";
	
	//Default Virtual Page options
	const OPTION_VIRTUAL_PAGE_TEMPLATE_DEFAULT = "ihf-virtual-page-template-default";
	
	//Listing Detail Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_DETAIL = "ihf-virtual-page-title-detail";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_DETAIL = "ihf-virtual-page-template-detail";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_DETAIL = "ihf-virtual-page-permalink-text-detail";
	const OPTION_VIRTUAL_PAGE_META_TAGS_DETAIL = "ihf-virtual-page-meta-tags-detail";
	
	//Listing Search Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_SEARCH = "ihf-virtual-page-title-search";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_SEARCH = "ihf-virtual-page-template-search";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SEARCH = "ihf-virtual-page-permalink-text-search";
	
	//Map Search Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_MAP_SEARCH = "ihf-virtual-page-title-map-search";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_MAP_SEARCH = "ihf-virtual-page-template-map-search";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_MAP_SEARCH = "ihf-virtual-page-permalink-text-map-search";
	
	//Advanced Listing Search Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_ADVANCED_SEARCH = "ihf-virtual-page-title-adv-search";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_ADVANCED_SEARCH = "ihf-virtual-page-template-adv-search";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_ADVANCED_SEARCH = "ihf-virtual-page-permalink-text-adv-search";
		
	//Organizer Login Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_ORGANIZER_LOGIN = "ihf-virtual-page-title-org-login";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_ORGANIZER_LOGIN = "ihf-virtual-page-template-org-login";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_ORGANIZER_LOGIN = "ihf-virtual-page-permalink-text-org-login";
	
	//Email Updated Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_EMAIL_UPDATES = "ihf-virtual-page-title-email-updates";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_EMAIL_UPDATES = "ihf-virtual-page-template-email-updates";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_EMAIL_UPDATES = "ihf-virtual-page-permalink-text-email-updates";
	
	//Featured Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_FEATURED = "ihf-virtual-page-title-featured";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_FEATURED = "ihf-virtual-page-template-featured";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_FEATURED = "ihf-virtual-page-permalink-text-featured";
	
	//Hotsheet Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_HOTSHEET = "ihf-virtual-page-title-hotsheet";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_HOTSHEET = "ihf-virtual-page-template-hotsheet";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_HOTSHEET = "ihf-virtual-page-permalink-text-hotsheet";
	const OPTION_VIRTUAL_PAGE_META_TAGS_HOTSHEET = "ihf-virtual-page-meta-tags-hotsheet";
	
	//Hotsheet List Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_HOTSHEET_LIST = "ihf-virtual-page-title-hotsheet-list";
	
	//Contact Form Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_CONTACT_FORM = "ihf-virtual-page-title-contact-form";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_CONTACT_FORM = "ihf-virtual-page-template-contact-form";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_CONTACT_FORM = "ihf-virtual-page-permalink-text-contact-form";
	
	//Valuation Form Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_VALUATION_FORM = "ihf-virtual-page-title-valuation-form";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_VALUATION_FORM = "ihf-virtual-page-template-valuation-form";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_VALUATION_FORM = "ihf-virtual-page-permalink-text-valuation-form";
	
	//Open Home Search Form Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_OPEN_HOME_SEARCH_FORM = "ihf-virtual-page-title-open-home-search-form";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_OPEN_HOME_SEARCH_FORM = "ihf-virtual-page-template-open-home-search-form";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_OPEN_HOME_SEARCH_FORM = "ihf-virtual-page-open-home-search-form";
	
	//Featured Sold Listings Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_SOLD_FEATURED = "ihf-virtual-page-title-sold-featured";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_SOLD_FEATURED = "ihf-virtual-page-template-sold-featured";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SOLD_FEATURED = "ihf-virtual-page-permalink-text-sold-featured";
	
	//Supplemental listings
	const OPTION_VIRTUAL_PAGE_TITLE_SUPPLEMENTAL_LISTING = "ihf-virtual-page-title-supplemental-listing";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_SUPPLEMENTAL_LISTING = "ihf-virtual-page-template-supplemental-listing";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SUPPLEMENTAL_LISTING = "ihf-virtual-page-permalink-text-supplemental-listing";
	
	//Sold Detail Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_SOLD_DETAIL = "ihf-virtual-page-title-sold-detail";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_SOLD_DETAIL = "ihf-virtual-page-template-sold-detail";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SOLD_DETAIL = "ihf-virtual-page-permalink-text-sold-detail";
	const OPTION_VIRTUAL_PAGE_META_TAGS_SOLD_DETAIL = "ihf-virtual-page-meta-tags-sold-detail";
	
	//Office List Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_OFFICE_LIST = "ihf-virtual-page-title-office-list";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_OFFICE_LIST = "ihf-virtual-page-template-office-list";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_OFFICE_LIST = "ihf-virtual-page-permalink-text-office-list";
	
	//Listing Office Detail Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_OFFICE_DETAIL = "ihf-virtual-page-title-office-detail";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_OFFICE_DETAIL = "ihf-virtual-page-template-office-detail";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_OFFICE_DETAIL = "ihf-virtual-page-permalink-text-office-detail";
	
	//Agent List Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_AGENT_LIST = "ihf-virtual-page-title-agent-list";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_AGENT_LIST = "ihf-virtual-page-template-agent-list";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_AGENT_LIST = "ihf-virtual-page-permalink-text-agent-list";
	
	//Agent Detail Virtual Page Options
	const OPTION_VIRTUAL_PAGE_TITLE_AGENT_DETAIL = "ihf-virtual-page-title-agent-detail";
	const OPTION_VIRTUAL_PAGE_TEMPLATE_AGENT_DETAIL = "ihf-virtual-page-template-agent-detail";
	const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_AGENT_DETAIL = "ihf-virtual-page-permalink-text-agent-detail";
	
	/*
	 * configuration options
	 */
	const OPTION_GROUP_CONFIGURATION = "ihf-config-page";
	const OPTION_LAYOUT_TYPE = "ihf-option-layout-type";
	const OPTION_LAYOUT_TYPE_RESPONSIVE = "responsive";
	const OPTION_LAYOUT_TYPE_LEGACY = "legacy";
	const COLOR_SCHEME_OPTION = "ihf-color-scheme";
	const CSS_OVERRIDE_OPTION = "ihf-css-override";
	
	/*
	 * bio widget options
	 */
	const OPTION_GROUP_BIO = "ihf-option-bio";
	const AGENT_PHOTO_OPTION = "ihf-bio-agent-photo-option";
	const AGENT_TEXT_OPTION = "ihf-bio-agent-text-option";
	const AGENT_DESIGNATIONS_OPTION = "ihf-bio-agent-designations-option";
	const AGENT_DISPLAY_TITLE_OPTION = "ihf-agent-display-title-option";
	const AGENT_LICENSE_INFO_OPTION = "ihf-agent-license-info-option";
	const CONTACT_PHONE_OPTION = "ihf-bio-contact-phone";
	const CONTACT_EMAIL_OPTION = "ihf-bio-contact-email";
	const OFFICE_LOGO_OPTION = "ihf-bio-office-logo";
	
	/*
	 * social widget options
	 */
	const OPTION_GROUP_SOCIAL = "ihf-option-social";
	const SOCIAL_FACEBOOK_URL_OPTION = "ihf-social-facebook-url-option";
	const SOCIAL_LINKEDIN_URL_OPTION = "ihf-social-linkedin-url-option";
	const SOCIAL_TWITTER_URL_OPTION = "ihf-social-twitter-url-option";
	const SOCIAL_PINTEREST_URL_OPTION = "ihf-social-pinterest-url";
	const SOCIAL_INSTAGRAM_URL_OPTION = "ihf-social-instagram-url";
	const SOCIAL_GOOGLE_PLUS_URL_OPTION = "ihf-social-google-plus-url";
	const SOCIAL_YOUTUBE_URL_OPTION = "ihf-social-youtube-url";
	const SOCIAL_YELP_URL_OPTION = "ihf-social-yelp-url";
	
	/*
	 * email branding options
	 */
	const OPTION_GROUP_EMAIL_DISPLAY = "ihf-option-email-display";
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
	
	/*
	 * community pages options
	 */
	const OPTION_GROUP_COMMUNITY_PAGES = "ihf-community-pages";
	
	/*
	 * SEO city links options
	 */
	const OPTION_GROUP_SEO_CITY_LINKS = "ihf-option-seo-city-links";
	const SEO_CITY_LINKS_SETTINGS = "ihf-seo-city-links-settings";
	const SEO_CITY_LINKS_CITY_ZIP = "ihf-seo-city-links-city-zip";
	const SEO_CITY_LINKS_TEXT = "ihf-seo-city-links-text";
	const SEO_CITY_LINKS_MIN_PRICE = "ihf-seo-city-links-min-price";
	const SEO_CITY_LINKS_MAX_PRICE = "ihf-seo-city-links-max-price";
	const SEO_CITY_LINKS_PROPERTY_TYPE = "ihf-seo-city-links-property-type";
	const SEO_CITY_LINK_WIDTH = "ihf-seo-city-link-width";
	
	/*
	 * compatibility check options
	 */
	const OPTION_GROUP_COMPATIBILITY_CHECK = "ihf-option-compatibility-check";
	const COMPATIBILITY_CHECK_ENABLED = "ihf-compatibility-check-enabled";
	
	//
	const OPTION_MOBILE_SITE_YN = "ihf-mobile-site-yn";
	
	//
	const VERSION_OPTION = "ihf_version_option";

	//Remember if this plugin has ever been activated on this site. This affects things like link creation, when the plugin is activated.
	const IS_ACTIVATED_OPTION = "ihf_links_created";
	const CSS_OVERRIDE_MIGRATED = "ihf_css_override_migrated";

	//Used throughout the application to discover iHomefinder requests and used to determine the proper filter to execute.
	const IHF_TYPE_URL_VAR = "ihf-type";
	
	const DEBUG = false;
	
}
