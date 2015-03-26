<?php

class iHomefinderTinyMceManager {
	
	private static $instance;
	
	private function __construct() {
		
	}
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new iHomefinderTinyMceManager();
		}
		return self::$instance;		
	}		
	
	function addButtons() {			
		if (!current_user_can('edit_posts') && !current_user_can('edit_pages'))
			return;
			
			
		if(!iHomefinderPermissions::getInstance()->isGalleryShortCodesEnabled())
			return;

		if (get_user_option('rich_editing') == 'true') {
			add_filter("mce_external_plugins", array($this,"addTinymcePlugins"));
			add_filter('mce_buttons',          array($this,"registerButtons"));
			add_action('in_admin_footer', array($this,'addTinymceVariables'));
		}
	}
	
	/**
	 * This function inserts JavaScript variables that are accessible by the
	 * tinymce init JavaScript and passed to the resulting dialog
	 * via top.tinymce.activeEditor.windowManager.getParams()
	 * 
	 * Used for dynamically loading JavaScript and AJAX calls
	 */
	function addTinyMceVariables() {
		?>
		<script type="text/javascript">
			var optimaExpressGalleryVars={
				'wpIncludes': '<?php echo(includes_url())?>',
				'jQueryUrl': '<?php echo(includes_url('/js/jquery/jquery.js'))?>',
				'optimaExpressBaseUrl': '<?php echo(plugins_url('/', __FILE__))?>',
				'ihfAdminAjaxUrl': '<?php echo(admin_url('admin-ajax.php')) ?>'
			};
		
		</script>
		
		<?php 
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
		$baseUrl = iHomefinderUrlFactory::getInstance()->getBaseUrl();
		$optimaExpressGalleryPluginUrl=plugins_url('/tinymce/optimaExpressGallery/editor_plugin.js', __FILE__);
		$plugin_array['optimaExpressGallery'] = $optimaExpressGalleryPluginUrl;						
		return $plugin_array;
	}

}