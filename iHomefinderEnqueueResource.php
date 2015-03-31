<?php

class iHomefinderEnqueueResource {
	
	private $header = array();
	private $footer = array();
	private $metaTags = array();
	private static $instance;

	private function __construct() {		
	}
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new iHomefinderEnqueueResource();
		}
		return self::$instance;
	}
	
	public function loadStandardJavaScript() {
		wp_enqueue_script("jquery");			
		wp_enqueue_script("jquery-ui-core");
		wp_enqueue_script("jquery-ui-tabs");
		wp_enqueue_script("jquery-ui-dialog");
		wp_enqueue_script("jquery-ui-datepicker");
		wp_enqueue_script("jquery-ui-autocomplete", "", array("jquery-ui-widget", "jquery-ui-position"), "1.8.6"); 				
	}
	
	
	public function loadJavaScript() {
		wp_register_script("ihf-areaPicker-js", plugins_url("js/areaPicker.js" , __FILE__), "jquery");
		wp_register_script("chosen-js", plugins_url("js/chosen/chosen.jquery.js" , __FILE__), "jquery");
		wp_register_script("ihf-chosen-js", plugins_url("js/chosen/ihf-chosen.js" , __FILE__), "chosen-js");
		wp_register_script("ihf-bootstrap", plugins_url("js/bootstrap-libs/bootstrap.min.js" , __FILE__), "jquery");
		wp_register_script("jquery-validation", plugins_url("js/jquery-libs/jquery.validate.min.js" , __FILE__), "jquery");
		wp_register_script("jquery-validation-additional-methods", plugins_url("js/jquery-libs/additional-methods.min.js" , __FILE__), "jquery");
		wp_register_script("jquery-touchSwipe", plugins_url("js/jquery-libs/jquery.touchSwipe.min-1.6.4.js" , __FILE__), "jquery");
		wp_register_script("jquery-cycle2", plugins_url("js/jquery-libs/jquery.cycle2.min.js" , __FILE__), "jquery");
		wp_register_script("leaflet", plugins_url("js/leaflet-0.7.3/leaflet.js", __FILE__), "jquery");
		wp_register_script("leaflet-markercluster", plugins_url("js/maps/leaflet.markercluster.js", __FILE__), "leaflet");
		wp_register_script("ihf-map-manager", plugins_url("js/maps/mapManager.js", __FILE__), "leaflet");
		wp_enqueue_script("ihf-areaPicker-js");
		wp_enqueue_script("chosen-js");
		wp_enqueue_script("ihf-chosen-js");
		wp_enqueue_script("ihf-bootstrap");
		wp_enqueue_script("jquery-validation");
		wp_enqueue_script("jquery-validation-additional-methods");
		wp_enqueue_script("jquery-touchSwipe");
		wp_enqueue_script("jquery-cycle2");
		wp_enqueue_script("leaflet");
		wp_enqueue_script("leaflet-markercluster");
		wp_enqueue_script("ihf-map-manager");
	}
	
	public function loadCSS() {
		wp_register_style("ihf-bootstrap", plugins_url("css/bootstrap/ihf-bootstrap-3.css" , __FILE__));
		wp_register_style("ihf-areaPicker", plugins_url("css/areaPicker.css" , __FILE__));
		wp_register_style("ihf-chosen", plugins_url("css/chosen.css" , __FILE__));
		wp_register_style("ihf-layout", plugins_url("css/ihlayout.css" , __FILE__));
		wp_register_style("ihf-lib-override", plugins_url("css/ih-lib-override.css" , __FILE__));
		wp_register_style("leaflet", plugins_url("js/leaflet-0.7.3/leaflet.css", __FILE__));
		wp_register_style("ihf-map", plugins_url("css/ihf-map.css", __FILE__));
		wp_register_style("ihf-layout-red", plugins_url("css/ihlayout-red.css" , __FILE__));
		wp_register_style("ihf-layout-green", plugins_url("css/ihlayout-green.css" , __FILE__));
		wp_register_style("ihf-layout-orange", plugins_url("css/ihlayout-orange.css" , __FILE__));
		wp_register_style("ihf-layout-blue", plugins_url("css/ihlayout-blue.css" , __FILE__));
		wp_register_style("ihf-layout-light-blue", plugins_url("css/ihlayout-lightblue.css" , __FILE__));
		wp_register_style("ihf-layout-blue-gradient", plugins_url("css/ihlayout-blue-gradient.css" , __FILE__));
		wp_enqueue_style("ihf-bootstrap");
		wp_enqueue_style("ihf-areaPicker");
		wp_enqueue_style("ihf-chosen");
		wp_enqueue_style("ihf-layout");
		wp_enqueue_style("ihf-lib-override");
		wp_enqueue_style("leaflet");
		wp_enqueue_style("ihf-map");
		$colorScheme = iHomefinderLayoutManager::getInstance()->getColorScheme();
		switch($colorScheme) {
			case "red":
				wp_enqueue_style("ihf-layout-red");
				break;
			case "green":
				wp_enqueue_style("ihf-layout-green");
				break;
			case "orange":
				wp_enqueue_style("ihf-layout-orange");
				break;
			case "blue":
				wp_enqueue_style("ihf-layout-blue");
				break;
			case "light_blue":
				wp_enqueue_style("ihf-layout-light-blue");
				break;
			case "blue_gradient":
				wp_enqueue_style("ihf-layout-blue-gradient");
				break;
		}
		wp_register_style("jquery-ui", plugins_url("css/jquery-ui-1.10.3.custom.min.css" , __FILE__));		
		wp_enqueue_style("jquery-ui");	
	}
	
	public function addCustomCSS() {
		$cssOverride = get_option(iHomefinderConstants::CSS_OVERRIDE_OPTION);
		if(isset($cssOverride) && !empty($cssOverride)) {
			echo "<style type='text/css'>" . $cssOverride . "</style>";
		}
	}
	
	public function addToHeader($value) {
		array_push($this->header, $value);
	}
	
	public function getHeader() {
		foreach($this->header as $value) {
			echo $value;
		}
	}
	
	public function addToFooter($value) {
		array_push($this->footer, $value);
	}
	
	public function getFooter() {
		foreach($this->footer as $value) {
			echo $value;
		}
	}
	
	public function addToMetaTags($value) {
		array_push($this->metaTags, $value);
	}
	
	public function getMetaTags() {
		foreach($this->metaTags as $value) {
			echo $value;
		}
	}
	
}