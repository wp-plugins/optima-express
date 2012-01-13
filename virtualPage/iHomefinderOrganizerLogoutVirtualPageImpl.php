<?php
if( !class_exists('iHomefinderOrganizerLogoutVirtualPageImpl')) {
	
	class IHomefinderOrganizerLogoutVirtualPageImpl implements IHomefinderVirtualPage {
	
		private $path="property-organizer-logout";
		
		public function __construct(){
			
		}
		public function getTitle(){
			return "Organizer Logout";
		}
			
		public function getPageTemplate(){
			
		}
		
		public function getPath(){
			return $this->path;
		}
		
							
		public function getContent( $authenticationToken ){
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