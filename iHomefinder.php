<?php
/*
Plugin Name: Optima Express IDX Plugin
Plugin URI: http://wordpress.org/extend/plugins/optima-express/
Description: Adds MLS / IDX property search and listings to your site. Includes search and listing pages, widgets and shortcodes. Requires an IDX account from iHomefinder. Get a free trial account with sample IDX data, or a paid account with data from your MLS.
Version: 2.7.4
Author: ihomefinder
Author URI: http://www.ihomefinder.com
License: GPL
*/

include "iHomefinderAutoloader.php";

$autoloader = iHomefinderAutoloader::getInstance();

//Runs when plugin is activated
register_activation_hook(__FILE__, array(iHomefinderInstaller::getInstance(), "install"));
//Runs on plugin deactivation
register_deactivation_hook(__FILE__, array(iHomefinderInstaller::getInstance(), "remove"));

//Runs just before the auto upgrader installs the plugin
add_filter("upgrader_post_install", array(iHomefinderInstaller::getInstance(), "upgrade"), 10, 2);

//uncomment during development, so rule changes can be viewed.
//in production this should not run, because it is a slow operation.
//add_action("init", array(IHomefinderRewriteRules::getInstance(), "flushRules"));

//Rewrite Rules
add_action("init", array(iHomefinderRewriteRules::getInstance(), "initialize"), 1);

if(is_admin()) {
	add_action("admin_enqueue_scripts", array(iHomefinderAdmin::getInstance(), "addScripts"));	
	add_action("admin_menu", array(iHomefinderAdmin::getInstance(), "createAdminMenu"));
	add_action("admin_init", array(iHomefinderInstaller::getInstance(), "upgrade"));
	add_action("admin_init", array(iHomefinderAdmin::getInstance(), "registerSettings"));
	//Adds functionality to the text editor for pages and posts
	//Add buttons to text editor and initialize short codes
	add_action("admin_init", array(iHomefinderShortcodeSelector::getInstance(), "addButtons"));	
	//Remember the users state in the application (subscriber info and last search)
	add_action("admin_init", array(iHomefinderStateManager::getInstance(), "initialize"), 5);
	//add error check
	add_action("admin_notices", array(iHomefinderAdmin::getInstance(), "checkError"));
} else {
	/*
	Call upgrade method on every non-admin page load. This is for the case that the plugin is updated through
	multisite network admin	or if the plugin files were manually copied into wordpress.
	*/
	add_action("setup_theme", array(iHomefinderInstaller::getInstance(), "upgrade"));
	add_action("init", array(iHomefinderEnqueueResource::getInstance(), "loadStandardJavaScript"));
	add_action("init", array(iHomefinderEnqueueResource::getInstance(), "loadJavaScript"));
	add_action("init", array(iHomefinderEnqueueResource::getInstance(), "loadCSS"));
	//Remember the users state in the application (subscriber info and last search)
	add_action("plugins_loaded", array(iHomefinderStateManager::getInstance(), "initialize"), 5);	
	add_action("wp_head", array(iHomefinderEnqueueResource::getInstance(), "getMetaTags"), -100);
	add_action("wp_head", array(iHomefinderEnqueueResource::getInstance(), "getHeader"));
	add_action("wp_footer", array(iHomefinderEnqueueResource::getInstance(), "getFooter"));
	add_filter("page_template", array(iHomefinderVirtualPageDispatcher::getInstance(), "getPageTemplate"));
	add_filter("the_content", array(iHomefinderVirtualPageDispatcher::getInstance(), "getContent"), 20);
	add_filter("the_excerpt", array(iHomefinderVirtualPageDispatcher::getInstance(), "getExcerpt"), 20);
	add_filter("the_posts", array(iHomefinderVirtualPageDispatcher::getInstance(), "postCleanUp"), 20000);
	add_filter("comments_array", array(iHomefinderVirtualPageDispatcher::getInstance(), "clearComments"));
}

//shortcode
add_action("init", array(iHomefinderShortcodeDispatcher::getInstance(), "init"));

$permissions = iHomefinderPermissions::getInstance();

//widgets
if($permissions->isPropertiesGalleryEnabled()) {
	add_action("widgets_init", create_function("", "return register_widget('iHomefinderPropertiesGallery');"));
}
if($permissions->isQuickSearchEnabled()) {
	add_action("widgets_init", create_function("", "return register_widget('iHomefinderQuickSearchWidget');"));
}
if($permissions->isSeoCityLinksEnabled()) {
	add_action("widgets_init", create_function("", "return register_widget('iHomefinderLinkWidget');"));
}
if($permissions->isSearchByAddressEnabled()) {
	add_action("widgets_init", create_function("", "return register_widget('iHomefinderSearchByAddressWidget');"));
}
if($permissions->isSearchByListingIdEnabled()) {
	add_action("widgets_init", create_function("", "return register_widget('iHomefinderSearchByListingIdWidget');"));
}
if($permissions->isContactFormWidgetEnabled()) {
	add_action("widgets_init", create_function("", "return register_widget('iHomefinderContactFormWidget');"));
}
if($permissions->isMoreInfoEnabled()) {
	add_action("widgets_init", create_function("", "return register_widget('iHomefinderMoreInfoWidget');"));
}
if($permissions->isAgentBioWidgetEnabled()) {
	add_action("widgets_init", create_function("", "return register_widget('iHomefinderAgentBioWidget');"));
}
if($permissions->isSocialEnabled()) {
	add_action("widgets_init", create_function("", "return register_widget('iHomefinderSocialWidget');"));
}
if($permissions->isHotsheetListWidgetEnabled()) {
	add_action("widgets_init", create_function("", "return register_widget('iHomefinderHotsheetListWidget');"));
}

//AJAX request handling
add_action("wp_ajax_nopriv_ihf_more_info_request", array(iHomefinderAjaxHandler::getInstance(), "requestMoreInfo"));
add_action("wp_ajax_nopriv_ihf_schedule_showing", array(iHomefinderAjaxHandler::getInstance(), "scheduleShowing"));
add_action("wp_ajax_nopriv_ihf_save_property", array(iHomefinderAjaxHandler::getInstance(), "saveProperty"));
add_action("wp_ajax_nopriv_ihf_photo_tour", array(iHomefinderAjaxHandler::getInstance(), "photoTour"));
add_action("wp_ajax_nopriv_ihf_save_search", array(iHomefinderAjaxHandler::getInstance(), "saveSearch"));
add_action("wp_ajax_nopriv_ihf_lead_capture_login", array(iHomefinderAjaxHandler::getInstance(), "leadCaptureLogin"));
add_action("wp_ajax_nopriv_ihf_saved_listing_comments", array(iHomefinderAjaxHandler::getInstance(), "addSavedListingComments"));
add_action("wp_ajax_nopriv_ihf_saved_listing_rating", array(iHomefinderAjaxHandler::getInstance(), "addSavedListingRating"));
add_action("wp_ajax_nopriv_ihf_save_listing_subscriber_session", array(iHomefinderAjaxHandler::getInstance(), "saveListingForSubscriberInSession"));
add_action("wp_ajax_nopriv_ihf_save_search_subscriber_session", array(iHomefinderAjaxHandler::getInstance(), "saveSearchForSubscriberInSession"));
add_action("wp_ajax_nopriv_ihf_contact_form_request", array(iHomefinderAjaxHandler::getInstance(), "contactFormRequest"));
add_action("wp_ajax_nopriv_ihf_send_password", array(iHomefinderAjaxHandler::getInstance(), "sendPassword"));
add_action("wp_ajax_nopriv_ihf_email_alert_popup", array(iHomefinderAjaxHandler::getInstance(), "emailAlertPopup"));
add_action("wp_ajax_nopriv_ihf_email_listing", array(iHomefinderAjaxHandler::getInstance(), "emailListing"));
add_action("wp_ajax_nopriv_ihf_advanced_search_multi_selects", array(iHomefinderAjaxHandler::getInstance(), "advancedSearchMultiSelects")); //@deprecated
add_action("wp_ajax_nopriv_ihf_advanced_search_fields", array(iHomefinderAjaxHandler::getInstance(), "getAdvancedSearchFormFields")); //@deprecated
add_action("wp_ajax_nopriv_ihf_area_autocomplete", array(iHomefinderAjaxHandler::getInstance(), "getAutocompleteMatches")); //@deprecated

add_action("wp_ajax_ihf_more_info_request", array(iHomefinderAjaxHandler::getInstance(), "requestMoreInfo"));
add_action("wp_ajax_ihf_schedule_showing", array(iHomefinderAjaxHandler::getInstance(), "scheduleShowing"));
add_action("wp_ajax_ihf_save_property", array(iHomefinderAjaxHandler::getInstance(), "saveProperty"));
add_action("wp_ajax_ihf_photo_tour", array(iHomefinderAjaxHandler::getInstance(), "photoTour"));
add_action("wp_ajax_ihf_save_search", array(iHomefinderAjaxHandler::getInstance(), "saveSearch"));
add_action("wp_ajax_ihf_lead_capture_login", array(iHomefinderAjaxHandler::getInstance(), "leadCaptureLogin"));
add_action("wp_ajax_ihf_saved_listing_comments", array(iHomefinderAjaxHandler::getInstance(), "addSavedListingComments"));
add_action("wp_ajax_ihf_saved_listing_rating", array(iHomefinderAjaxHandler::getInstance(), "addSavedListingRating"));
add_action("wp_ajax_ihf_save_listing_subscriber_session", array(iHomefinderAjaxHandler::getInstance(), "saveListingForSubscriberInSession"));
add_action("wp_ajax_ihf_save_search_subscriber_session", array(iHomefinderAjaxHandler::getInstance(), "saveSearchForSubscriberInSession"));
add_action("wp_ajax_ihf_contact_form_request", array(iHomefinderAjaxHandler::getInstance(), "contactFormRequest"));
add_action("wp_ajax_ihf_send_password", array(iHomefinderAjaxHandler::getInstance(), "sendPassword"));
add_action("wp_ajax_ihf_email_alert_popup", array(iHomefinderAjaxHandler::getInstance(), "emailAlertPopup"));
add_action("wp_ajax_ihf_email_listing", array(iHomefinderAjaxHandler::getInstance(), "emailListing"));
add_action("wp_ajax_ihf_tiny_mce_shortcode_dialog", array(iHomefinderShortcodeSelector::getInstance(), "getShortcodeSelectorContent"));
add_action("wp_ajax_ihf_advanced_search_multi_selects", array(iHomefinderAjaxHandler::getInstance(), "advancedSearchMultiSelects")); //@deprecated
add_action("wp_ajax_ihf_advanced_search_fields", array(iHomefinderAjaxHandler::getInstance(), "getAdvancedSearchFormFields")); //@deprecated
add_action("wp_ajax_ihf_area_autocomplete", array(iHomefinderAjaxHandler::getInstance(), "getAutocompleteMatches")); //@deprecated

//Disable canonical urls, because we use a single page to display all results
//and WordPress creates a single canonical url for all of the virtual urls
//like the detail page and featured results.
remove_action("wp_head", "rel_canonical");
//remove_action("wp_head", "genesis_canonical");
