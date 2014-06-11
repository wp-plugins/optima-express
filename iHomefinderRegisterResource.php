<?php
if( !class_exists('IHomefinderRegisterResource')) {
	class IHomefinderRegisterResource {
		
		private static $instance ;

		private function __construct(){		
		}
		
		public static function getInstance(){
			if( !isset(self::$instance)){
				self::$instance = new IHomefinderRegisterResource();
			}
			return self::$instance;
		}
		
		public function loadStandardJavaScript(){
			wp_enqueue_script('jquery');			
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-tabs');
			wp_enqueue_script('jquery-ui-dialog');
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_script('jquery-ui-autocomplete', '', array('jquery-ui-widget', 'jquery-ui-position'), '1.8.6'); 			
		}
		
		
		public function registerJavaScript(){
			wp_register_script('ihf-areaPicker-js', plugins_url( 'js/areaPicker.js' , __FILE__ ), 'jquery');
			wp_register_script('chosen-js', plugins_url( 'js/chosen/chosen.jquery.js' , __FILE__ ), 'jquery');
			wp_register_script('ihf-chosen-js', plugins_url( 'js/chosen/ihf-chosen.js' , __FILE__ ), 'chosen-js');
			wp_register_script('ihf-bootstrap', plugins_url( 'js/bootstrap-libs/bootstrap.min.js' , __FILE__ ), 'jquery');
			wp_register_script('jquery-validation', plugins_url( 'js/jquery-libs/jquery.validate.min.js' , __FILE__ ), 'jquery');
			wp_register_script('jquery-validation-additional-methods', plugins_url( 'js/jquery-libs/additional-methods.min.js' , __FILE__ ), 'jquery');
			wp_register_script('jquery-touchSwipe', plugins_url( 'js/jquery-libs/jquery.touchSwipe.min-1.6.4.js' , __FILE__ ), 'jquery');
			wp_register_script('marker-clusterer', plugins_url( 'js/maps/markerclusterer.js' , __FILE__ ), 'jquery');
			wp_register_script('jquery-cycle2', plugins_url( 'js/jquery-libs/jquery.cycle2.min.js' , __FILE__ ), 'jquery');
		}
		
		public function registerCSS(){
			
			 wp_register_style( 'ihf-bootstrap', plugins_url( 'css/bootstrap/ihf-bootstrap-3.css' , __FILE__ ) );
			 wp_register_style( 'ihf-areaPicker', plugins_url( 'css/areaPicker.css' , __FILE__ ) );
			 wp_register_style( 'ihf-chosen', plugins_url( 'css/chosen.css' , __FILE__ ) );
			 wp_register_style( 'ihf-layout', plugins_url( 'css/ihlayout.css' , __FILE__ ) );
			 wp_register_style( 'ihf-lib-override', plugins_url( 'css/ih-lib-override.css' , __FILE__ ) );
			 wp_register_style( 'ihf-layout-light-blue', plugins_url( 'css/ihlayout-lightblue.css' , __FILE__ ) );
			 wp_register_style( 'ihf-layout-blue-gradient', plugins_url( 'css/ihlayout-blue-gradient.css' , __FILE__ ) );
			 wp_register_style( 'ihf-layout-red', plugins_url( 'css/ihlayout-red.css' , __FILE__ ) );
			 wp_register_style( 'ihf-layout-green', plugins_url( 'css/ihlayout-green.css' , __FILE__ ) );
			 wp_register_style( 'ihf-layout-orange', plugins_url( 'css/ihlayout-orange.css' , __FILE__ ) );
			 wp_register_style( 'ihf-layout-lightblue', plugins_url( 'css/ihlayout-blue.css' , __FILE__ ) );
			 wp_register_style( 'jquery-ui', plugins_url( 'css/jquery-ui-1.10.3.custom.min.css' , __FILE__ ) );			 
		}
	}
}
?>