<?php
if( !class_exists('IHomefinderCustomization')) {

	/**
	 *
	 * This singleton class is used to add UI customization
	 *
	 * @author ihomefinder
	 */
	class IHomefinderCustomization {

		private static $instance ;

		private function __construct(){
		}

		public static function getInstance(){
			if( !isset(self::$instance)){
				self::$instance = new IHomefinderCustomization();
			}
			return self::$instance;
		}
		
		function addCustomCSS(){
			$cssOverride=get_option(IHomefinderConstants::CSS_OVERRIDE_OPTION);
			if( isset( $cssOverride )){
				echo("<style type='text/css'>" . $cssOverride . "</style>");
			}
		}
	}
}
?>