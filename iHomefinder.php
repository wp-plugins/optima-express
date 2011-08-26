<?php
/*
Plugin Name: Optima Express IDX Plugin
Plugin URI: http://wordpress.org/extend/plugins/optima-express/
Description: This plugin integrates your Wordpress site with IDX search functionality.  This plugin requires an activation key.
Version: 1.0.0
Author: iHomefinder
Author URI: http://www.ihomefinder.com
License: GPL
*/

/**
 * Load core files
 */
include_once 'iHomefinderAdmin.php';
include_once 'iHomefinderAjaxHandler.php';
include_once 'iHomefinderConstants.php';
include_once 'iHomefinderFilterDispatcher.php';
include_once 'iHomefinderFilterFactory.php';
include_once 'iHomefinderInstaller.php';
include_once 'iHomefinderLogger.php';
include_once 'iHomefinderPermissions.php';
include_once 'iHomefinderRequestor.php';
include_once 'iHomefinderRewriteRules.php';
include_once 'iHomefinderStateManager.php';
include_once 'iHomefinderSubscriber.php';
include_once 'iHomefinderUrlFactory.php';
include_once 'iHomefinderUtility.php';

/**
 * Load  Widgets
 */
include("widget/iHomefinderPropertiesGallery.php");
include("widget/iHomefinderQuickSearchWidget.php");
add_action('widgets_init', create_function('', 'return register_widget("iHomefinderPropertiesGallery");'));
add_action('widgets_init', create_function('', 'return register_widget("iHomefinderQuickSearchWidget");'));

/* Runs when plugin is activated */
register_activation_hook(__FILE__,array(IHomefinderInstaller::getInstance(), 'install'));
/* Runs on plugin deactivation*/
register_deactivation_hook( __FILE__, array(IHomefinderInstaller::getInstance(), 'remove') );

/* Rewrite Rules */
add_action('init',array(IHomefinderRewriteRules::getInstance(), "initialize"));
//uncomment during development, so rule changes can be viewed.
//in production this should not run, because it is a slow operation.
//add_action('init',array(IHomefinderRewriteRules::getInstance(), "flushRules"));

if( is_admin()){
	add_action('admin_menu', array(IHomefinderAdmin::getInstance(), "createAdminMenu"));
	add_action( 'admin_init', array(IHomefinderAdmin::getInstance(), "registerSettings") );
} else {
	//Remember the users state in the application (subscriber info and last search)
	add_action('plugins_loaded',array(IHomefinderStateManager::getInstance(), "initialize"), 5);
	add_action('plugins_loaded', array(IHomefinderStateManager::getInstance(), "saveLastSearch"), 8);

	add_filter( 'the_content', array(IHomefinderFilterDispatcher::getInstance(), "filter") );
	add_filter( 'the_posts', array(IHomefinderFilterDispatcher::getInstance(), "postCleanUp") );
}


//AJAX Request handling.
add_action("wp_ajax_nopriv_ihf_more_info_request", array(IHomefinderAjaxHandler::getInstance(), "requestMoreInfo")) ;
add_action("wp_ajax_nopriv_ihf_schedule_showing",  array(IHomefinderAjaxHandler::getInstance(), "scheduleShowing"));
add_action("wp_ajax_nopriv_ihf_save_property",     array(IHomefinderAjaxHandler::getInstance(), "saveProperty")) ;
add_action("wp_ajax_nopriv_ihf_photo_tour",        array(IHomefinderAjaxHandler::getInstance(), "photoTour")) ;
add_action("wp_ajax_nopriv_ihf_save_search",        array(IHomefinderAjaxHandler::getInstance(), "saveSearch")) ;

add_action("wp_ajax_ihf_more_info_request",        array(IHomefinderAjaxHandler::getInstance(), "requestMoreInfo")) ;
add_action("wp_ajax_ihf_schedule_showing",         array(IHomefinderAjaxHandler::getInstance(), "scheduleShowing"));
add_action("wp_ajax_ihf_save_property",            array(IHomefinderAjaxHandler::getInstance(), "saveProperty")) ;
add_action("wp_ajax_ihf_photo_tour",               array(IHomefinderAjaxHandler::getInstance(), "photoTour")) ;
add_action("wp_ajax_ihf_save_search",              array(IHomefinderAjaxHandler::getInstance(), "saveSearch")) ;

//Disable canonical urls, because we use a single page to display all results
//and Wordpress creates a single canonical url for all of the virtual urls
//like the detail page and featured results.
remove_action('wp_head', 'rel_canonical');
//remove_action('wp_head', 'genesis_canonical');
?>