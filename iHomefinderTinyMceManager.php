<?php
if( !class_exists('IHomefinderTinyMceManager')) {
	class IHomefinderTinyMceManager {
		
		private static $instance ;
		
		private function __construct(){
			
		}
		
		public static function getInstance(){
			if( !isset(self::$instance)){
				self::$instance = new IHomefinderTinyMceManager();
			}
			return self::$instance;		
		}		
		
		function addButtons(){
			if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') )
				return;
				
			if( !IHomefinderPermissions::getInstance()->isGalleryShortCodesEnabled())
				return ;

			if ( get_user_option('rich_editing') == 'true') {
				add_filter("mce_external_plugins", array($this,"addTinymcePlugins"));
				add_filter('mce_buttons',          array($this,"registerButtons"));
			}
		}

		/**
		 * Used for TinyMCE to register buttons
		 */
		function registerButtons($buttons) {
			array_push($buttons, "|", "optimaExpressGallery");
			return $buttons;
		}

		/**
		 * Load the TinyMCE plugin : editor_plugin.js (wp2.5)
		 * Note the url variable is configured in WordPress
		 */
		function addTinymcePlugins($plugin_array) {
			$baseUrl = IHomefinderUrlFactory::getInstance()->getBaseUrl() ;
            $optimaExpressGalleryPluginUrl= $baseUrl . '/wp-content/plugins/' . plugin_basename( dirname(__FILE__) ) . '/tinymce/optimaExpressGallery/editor_plugin.js';
			$plugin_array['optimaExpressGallery'] = $optimaExpressGalleryPluginUrl ;
			return $plugin_array;
		}

	}//end class
}// end if class_exists

?>