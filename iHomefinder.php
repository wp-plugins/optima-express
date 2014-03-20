<?php
	/*
	Plugin Name: Optima Express IDX Plugin
	Plugin URI: http://wordpress.org/extend/plugins/optima-express/
	Description: Adds MLS / IDX property search and listings to your site. Includes search and listing pages, widgets and shortcodes. Requires an IDX account from iHomefinder. Get a free trial account with sample IDX data, or a paid account with data from your MLS.
	Version: 2.0.1
	Author: ihomefinder
	Author URI: http://www.ihomefinder.com
	License: GPL
	*/


/**
 * Load core files
 */
include_once 'iHomefinderAdmin.php';
include_once 'iHomefinderAdminEmailDisplay.php';
include_once 'iHomefinderAdminPageConfig.php';
include_once 'iHomefinderAjaxHandler.php';
include_once 'iHomefinderConstants.php';
include_once 'iHomefinderCustomization.php';
include_once 'iHomefinderInstaller.php';
include_once 'iHomefinderListingInfo.php';
include_once 'iHomefinderLogger.php';
include_once 'iHomefinderMenu.php';
include_once 'iHomefinderPermissions.php';
include_once 'iHomefinderRequestor.php';
include_once 'iHomefinderRewriteRules.php';
include_once 'iHomefinderSearchLinkInfo.php';
include_once 'iHomefinderSearchFormFieldsUtility.php';
include_once 'iHomefinderShortcodeDispatcher.php';
include_once 'iHomefinderStateManager.php';
include_once 'iHomefinderCleaner.php';
include_once 'iHomefinderSubscriber.php';
include_once 'iHomefinderTinyMceManager.php';
include_once 'iHomefinderUrlFactory.php';
include_once 'iHomefinderUtility.php';
include_once 'iHomefinderVirtualPageDispatcher.php';
include_once 'iHomefinderVirtualPageFactory.php';
include_once 'iHomefinderVirtualPageHelper.php';
include_once 'iHomefinderLayoutManager.php';


/**
 * Load  Widgets and Widget Context Utility
 */
include("widget/iHomefinderWidgetContextUtility.php");
include("widget/iHomefinderPropertiesGallery.php");
include("widget/iHomefinderQuickSearchWidget.php");
include("widget/iHomefinderSearchByAddressWidget.php");
include("widget/iHomefinderSearchByListingIdWidget.php");
include("widget/iHomefinderContactFormWidget.php");
include("widget/iHomefinderLinkWidget.php");
include("widget/iHomefinderMoreInfoWidget.php");
include("widget/iHomefinderAgentBioWidget.php");
include("widget/iHomefinderSocialWidget.php");

add_action('widgets_init', create_function('', 'return register_widget("iHomefinderPropertiesGallery");'));
add_action('widgets_init', create_function('', 'return register_widget("iHomefinderQuickSearchWidget");'));
add_action('widgets_init', create_function('', 'return register_widget("iHomefinderLinkWidget");'));
add_action('widgets_init', create_function('', 'return register_widget("iHomefinderAgentBioWidget");'));
add_action('widgets_init', create_function('', 'return register_widget("iHomefinderSocialWidget");'));
if( IHomefinderPermissions::getInstance()->isMoreInfoEnabled() == TRUE ) {
	add_action('widgets_init', create_function('', 'return register_widget("iHomefinderMoreInfoWidget");'));
}
if( IHomefinderPermissions::getInstance()->isSearchByAddressEnabled() == TRUE ) {
	add_action('widgets_init', create_function('', 'return register_widget("iHomefinderSearchByAddressWidget");'));
}
if( IHomefinderPermissions::getInstance()->isSearchByListingIdEnabled() == TRUE ) {
	add_action('widgets_init', create_function('', 'return register_widget("iHomefinderSearchByListingIdWidget");'));
}
if( IHomefinderPermissions::getInstance()->isContactFormWidgetEnabled() == TRUE ) {
	add_action('widgets_init', create_function('', 'return register_widget("iHomefinderContactFormWidget");'));
}

/* Runs when plugin is activated */
register_activation_hook(__FILE__,array(IHomefinderInstaller::getInstance(), 'install'));
/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, array(IHomefinderInstaller::getInstance(), 'remove') );


/* Runs just before the auto upgrader installs the plugin*/
add_filter('upgrader_post_install', array(IHomefinderInstaller::getInstance(), 'upgrade'), 10, 2);

/* Rewrite Rules */
add_action('init', array(IHomefinderRewriteRules::getInstance(), "initialize"), 1 );

//uncomment during development, so rule changes can be viewed.
//in production this should not run, because it is a slow operation.
//add_action('init',array(IHomefinderRewriteRules::getInstance(), "flushRules"));

if( is_admin()){
	add_action( 'admin_enqueue_scripts', array(IHomefinderAdmin::getInstance(), "addScripts") );
	add_action('admin_menu', array(IHomefinderAdmin::getInstance(), "createAdminMenu"));
	add_action('admin_init', array(IHomefinderInstaller::getInstance(), 'upgrade'));
	add_action('admin_init', array(IHomefinderAdmin::getInstance(), "registerSettings") );

	add_action('admin_init', array(IHomefinderWidgetContextUtility::getInstance(), "loadWidgetJavascript") );
	//Adds functionality to the text editor for pages and posts
	//Add buttons to text editor and initialize short codes
	add_action('admin_init', array(IHomefinderTinyMceManager::getInstance(), "addButtons") );

	//Remember the users state in the application (subscriber info and last search)
	add_action('admin_init',array(IHomefinderStateManager::getInstance(), "initialize"), 5);

	//add error check
	add_action('admin_notices', array(IHomefinderAdmin::getInstance(), "checkError"));
} else {
	add_action('init',array(IHomefinderVirtualPageDispatcher::getInstance(), "loadJavaScript")) ;
	add_action( 'wp_enqueue_scripts', array(IHomefinderWidgetContextUtility::getInstance(), "loadWidgetStyle") );

	//Remember the users state in the application (subscriber info and last search)
	add_action('plugins_loaded',array(IHomefinderStateManager::getInstance(), "initialize"), 5);

	add_filter( 'page_template', array(IHomefinderVirtualPageDispatcher::getInstance(), "getPageTemplate") );
	add_filter( 'the_content', array(IHomefinderVirtualPageDispatcher::getInstance(), "getContent"), 20 );
	add_filter( 'the_excerpt', array(IHomefinderVirtualPageDispatcher::getInstance(), "getExcerpt"), 20 );
	add_filter( 'the_posts', array(IHomefinderVirtualPageDispatcher::getInstance(), "postCleanUp") );
	//add_filter( 'the_title', array(IHomefinderVirtualPageDispatcher::getInstance(), "getTitle") );

	add_action('wp_footer', array(IHomefinderCustomization::getInstance(), "addCustomCSS"));

	add_action('ihf_expired_transients_cleanup', array(IHomefinderCleaner::getInstance(), "removeExpiredIhfTransients"));
	add_filter('comments_array', array(IHomefinderVirtualPageDispatcher::getInstance(), "clearComments"));
}



add_action('init', array(IHomefinderShortcodeDispatcher::getInstance(), "init"));

//AJAX Request handling.
add_action("wp_ajax_nopriv_ihf_more_info_request", array(IHomefinderAjaxHandler::getInstance(), "requestMoreInfo")) ;
add_action("wp_ajax_nopriv_ihf_schedule_showing",  array(IHomefinderAjaxHandler::getInstance(), "scheduleShowing"));
add_action("wp_ajax_nopriv_ihf_save_property",     array(IHomefinderAjaxHandler::getInstance(), "saveProperty")) ;
add_action("wp_ajax_nopriv_ihf_photo_tour",        array(IHomefinderAjaxHandler::getInstance(), "photoTour")) ;
add_action("wp_ajax_nopriv_ihf_save_search",        array(IHomefinderAjaxHandler::getInstance(), "saveSearch")) ;
add_action("wp_ajax_nopriv_ihf_advanced_search_multi_selects", array(IHomefinderAjaxHandler::getInstance(), "advancedSearchMultiSelects")) ;
add_action("wp_ajax_nopriv_ihf_advanced_search_fields",        array(IHomefinderAjaxHandler::getInstance(), "getAdvancedSearchFormFields")) ;
add_action("wp_ajax_nopriv_ihf_lead_capture_login",            array(IHomefinderAjaxHandler::getInstance(), "leadCaptureLogin")) ;
add_action("wp_ajax_nopriv_ihf_saved_listing_comments",        array(IHomefinderAjaxHandler::getInstance(), "addSavedListingComments")) ;
add_action("wp_ajax_nopriv_ihf_saved_listing_rating",          array(IHomefinderAjaxHandler::getInstance(), "addSavedListingRating")) ;
add_action("wp_ajax_nopriv_ihf_save_listing_subscriber_session", array(IHomefinderAjaxHandler::getInstance(), "saveListingForSubscriberInSession")) ;
add_action("wp_ajax_nopriv_ihf_save_search_subscriber_session",  array(IHomefinderAjaxHandler::getInstance(), "saveSearchForSubscriberInSession"));
add_action("wp_ajax_nopriv_ihf_area_autocomplete",               array(IHomefinderAjaxHandler::getInstance(), "getAutocompleteMatches"));
add_action("wp_ajax_nopriv_ihf_contact_form_request", array(IHomefinderAjaxHandler::getInstance(), "contactFormRequest"));

add_action("wp_ajax_ihf_more_info_request",        array(IHomefinderAjaxHandler::getInstance(), "requestMoreInfo")) ;
add_action("wp_ajax_ihf_schedule_showing",         array(IHomefinderAjaxHandler::getInstance(), "scheduleShowing"));
add_action("wp_ajax_ihf_save_property",            array(IHomefinderAjaxHandler::getInstance(), "saveProperty")) ;
add_action("wp_ajax_ihf_photo_tour",               array(IHomefinderAjaxHandler::getInstance(), "photoTour")) ;
add_action("wp_ajax_ihf_save_search",              array(IHomefinderAjaxHandler::getInstance(), "saveSearch")) ;
add_action("wp_ajax_ihf_advanced_search_multi_selects",       array(IHomefinderAjaxHandler::getInstance(), "advancedSearchMultiSelects")) ;
add_action("wp_ajax_ihf_advanced_search_fields",              array(IHomefinderAjaxHandler::getInstance(), "getAdvancedSearchFormFields")) ;
add_action("wp_ajax_ihf_lead_capture_login",                  array(IHomefinderAjaxHandler::getInstance(), "leadCaptureLogin")) ;
add_action("wp_ajax_ihf_saved_listing_comments",              array(IHomefinderAjaxHandler::getInstance(), "addSavedListingComments")) ;
add_action("wp_ajax_ihf_saved_listing_rating",                array(IHomefinderAjaxHandler::getInstance(), "addSavedListingRating")) ;
add_action("wp_ajax_ihf_save_listing_subscriber_session",     array(IHomefinderAjaxHandler::getInstance(), "saveListingForSubscriberInSession")) ;
add_action("wp_ajax_ihf_save_search_subscriber_session",      array(IHomefinderAjaxHandler::getInstance(), "saveSearchForSubscriberInSession"));
add_action("wp_ajax_ihf_area_autocomplete",                   array(IHomefinderAjaxHandler::getInstance(), "getAutocompleteMatches"));
add_action("wp_ajax_ihf_contact_form_request", array(IHomefinderAjaxHandler::getInstance(), "contactFormRequest"));

//Disable canonical urls, because we use a single page to display all results
//and Wordpress creates a single canonical url for all of the virtual urls
//like the detail page and featured results.
remove_action('wp_head', 'rel_canonical');
//remove_action('wp_head', 'genesis_canonical');
?>