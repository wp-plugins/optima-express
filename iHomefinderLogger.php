<?php

/**
 * 
 * This class is handle all iHomefinder Ajax Requests.
 * It proxies the requests and returns the proper results.
 * 
 * @author ihomefinder
 */
class iHomefinderLogger {

	private static $instance;

	
	private function __construct() {
	}
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new iHomefinderLogger();
		}
		return self::$instance;		
	}
	
	private function isDebug() {
		$debug=false;
		if(array_key_exists('debug', $_REQUEST)) {
			$debugValue = $_REQUEST['debug'];
			if($debugValue && $debugValue === 'true') {
				$debug=true;
			}						
		}
		else if(iHomefinderConstants::DEBUG) {
			$debug=true;
		}

		return $debug;				
	}
	
	/**
	 * Echo messages to the screen if debugging on
	 * @param unknown_type $message
	 */
	public function debug($message) {	
		if($this->isDebug()) {
			echo "\n\r";
			echo microtime(true) . ": ";
			echo $message;
		}				
	}				
	public function debugDumpVar($message) {	
		if($this->isDebug()) {	
			var_dump($message);
		}				
	}				
}