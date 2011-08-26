<?php
if( !class_exists('IHomefinderUtility')) {
	/**
	 *
	 *
	 * @author iHomefinder
	 */
	class IHomefinderUtility {
		
		private static $instance ;

		private function __construct(){
		}

		public static function getInstance(){
			if( !isset(self::$instance)){
				self::$instance = new IHomefinderUtility();
			}
			return self::$instance;
		}	

		public function getQueryVar($name){
			global $wp;
			$result = $this->getVarFromArray( $name, $wp->query_vars ) ;
			return $result ;
		}				

		public function getRequestVar($name){
			$result = $this->getVarFromArray( $name, $_REQUEST ) ;
			return $result ;
		}				
		
		public function getVarFromArray($name, $arrayVar){
			$result=null ;
			if( array_key_exists($name, $arrayVar)){
				$result = $arrayVar[$name];
			}
			return $result ;
		}				
	}
}
?>