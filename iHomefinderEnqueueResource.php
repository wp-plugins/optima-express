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
			self::$instance = new self();
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
		$this->enqueueScript("ihf-areaPicker-js", "js/areaPicker.js");
		$this->enqueueScript("chosen-js", "js/chosen/chosen.jquery.js");
		$this->enqueueScript("ihf-chosen-js", "js/chosen/ihf-chosen.js");
		$this->enqueueScript("ihf-bootstrap", "js/bootstrap-libs/bootstrap.min.js");
		$this->enqueueScript("jquery-validation", "js/jquery-libs/jquery.validate.min.js");
		$this->enqueueScript("jquery-validation-additional-methods", "js/jquery-libs/additional-methods.min.js");
		$this->enqueueScript("jquery-touchSwipe", "js/jquery-libs/jquery.touchSwipe.min-1.6.4.js");
		$this->enqueueScript("jquery-cycle2", "js/jquery-libs/jquery.cycle2.min.js");
		$this->enqueueScript("leaflet", "js/leaflet-0.7.3/leaflet.js");
		$this->enqueueScript("leaflet-markercluster", "js/maps/leaflet.markercluster.js");
		$this->enqueueScript("ihf-map-manager", "js/maps/mapManager.js");
		$this->enqueueScript("ihf-event-manager", "js/IhfEventManager.js");
	}
	
	private function enqueueScript($handle, $src) {
		wp_enqueue_script($handle, plugins_url($src, __FILE__), array("jquery"), iHomefinderConstants::VERSION);
	}
	
	public function loadCSS() {
		$this->enqueueStyle("ihf-bootstrap", "css/bootstrap/ihf-bootstrap-3.css");
		$this->enqueueStyle("ihf-areaPicker", "css/areaPicker.css");
		$this->enqueueStyle("ihf-chosen", "css/chosen.css");
		$this->enqueueStyle("ihf-layout", "css/ihlayout.css");
		$this->enqueueStyle("ihf-lib-override", "css/ih-lib-override.css");
		$this->enqueueStyle("leaflet", "js/leaflet-0.7.3/leaflet.css");
		$this->enqueueStyle("ihf-map", "css/ihf-map.css");
		$colorScheme = iHomefinderLayoutManager::getInstance()->getColorScheme();
		switch($colorScheme) {
			case "red":
				$this->enqueueStyle("ihf-layout-red", "css/ihlayout-red.css");
				break;
			case "green":
				$this->enqueueStyle("ihf-layout-green", "css/ihlayout-green.css");
				break;
			case "orange":
					$this->enqueueStyle("ihf-layout-orange", "css/ihlayout-orange.css");
				break;
			case "blue":
				$this->enqueueStyle("ihf-layout-blue", "css/ihlayout-blue.css");
				break;
			case "light_blue":
				$this->enqueueStyle("ihf-layout-light-blue", "css/ihlayout-lightblue.css");
				break;
			case "blue_gradient":
				$this->enqueueStyle("ihf-layout-blue-gradient", "css/ihlayout-blue-gradient.css");
				break;
		}
		$this->enqueueStyle("jquery-ui", "css/jquery-ui-1.10.3.custom.min.css");
		$this->enqueueStyle("ihf-widget-style", "css/widget-style.css");
	}
	
	private function enqueueStyle($handle, $src) {
		wp_enqueue_style($handle, plugins_url($src, __FILE__), null, iHomefinderConstants::VERSION);
	}
	
	public function addToHeader($value) {
		$this->header[] = $value;
	}
	
	public function getHeader() {
		echo get_option(iHomefinderConstants::CSS_OVERRIDE_OPTION, null);
		foreach($this->header as $value) {
			echo $value;
		}
	}
	
	public function addToFooter($value) {
		$this->footer[] = $value;
	}
	
	public function getFooter() {
		foreach($this->footer as $value) {
			echo $value;
		}
	}
	
	public function addToMetaTags($value) {
		$this->metaTags[] = $value;
	}
	
	public function getMetaTags() {
		foreach($this->metaTags as $value) {
			echo $value;
		}
	}
	
}