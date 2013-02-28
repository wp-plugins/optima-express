<?php
if( !class_exists('IHomefinderSearchFormFieldsUtility')) {
	/**
	 * IHomefinderSearchFormFieldsUtility Class
	 * 
	 * This singleton utility class is used to search form fields.
	 */
	class IHomefinderSearchFormFieldsUtility {
		
		private static $instance ;
		
		private function __construct(){
		}

		public static function getInstance(){
			if( !isset(self::$instance)){
				self::$instance = new IHomefinderSearchFormFieldsUtility();
			}
			return self::$instance;
		}
		
		public function getFormData(){
            $authenticationToken=IHomefinderAdmin::getInstance()->getAuthenticationToken();
            $ihfUrl = iHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=search-form-lists&authenticationToken=' .  $authenticationToken ;
            $galleryFormData = iHomefinderRequestor::remoteRequest($ihfUrl);
            return $galleryFormData;
         }		
		

	} // class IHomefinderFormFieldsUtility
}//end if( !class_exists('IHomefinderFormFieldsUtility'))
?>
