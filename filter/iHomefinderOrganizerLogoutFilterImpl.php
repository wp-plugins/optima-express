<?php
if( !class_exists('iHomefinderOrganizerLogoutFilterImpl')) {
	
	class IHomefinderOrganizerLogoutFilterImpl implements IHomefinderFilter {
	
		public function __construct(){
			
		}
		public function getTitle(){
			return "Organizer Logout";
		}			
		public function filter( $content, $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin iHomefinderOrganizerLogoutFilterImpl');
			IHomefinderStateManager::getInstance()->deleteSubscriberLogin();
			IHomefinderLogger::getInstance()->debug('End iHomefinderOrganizerLogoutFilterImpl');		
			$redirectUrl=IHomefinderUrlFactory::getInstance()->getListingsSearchFormUrl(true) ; 
			//redirect to the search page
			$content = '<meta http-equiv="refresh" content="0;url=' . $redirectUrl . '">';
			
			return $content ;
		}
	}//end class
}
?>