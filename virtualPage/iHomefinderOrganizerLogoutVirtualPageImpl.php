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
		public function getContent($authenticationToken){
			
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderOrganizerLogoutImpl');
			$logOutOfJavaServers = IHomefinderLayoutManager::getInstance()->isSubscriberSessionOnJavaServers();
			/**
			 * For responsive layout we need to kill the session for subscriber 
			 * on lava servers
			 * Where as for legacy layout we need to kill session stored locally on wordpress
			 * servers
			 */
			if($logOutOfJavaServers){
				$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=property-organizer-logout' ;
				$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
				$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "phpStyle", "true");
		
				$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
				$idxContent = IHomefinderRequestor::getContent( $contentInfo );
				IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
				$content=$idxContent;
	
			}
			else{
				IHomefinderStateManager::getInstance()->deleteSubscriberLogin();
				IHomefinderLogger::getInstance()->debug('End iHomefinderOrganizerLogoutFilterImpl');		
				$redirectUrl=IHomefinderUrlFactory::getInstance()->getListingsSearchFormUrl(true) ; 
				//redirect to the search page
				$content = '<meta http-equiv="refresh" content="0;url=' . $redirectUrl . '">';
			
			}
			IHomefinderLogger::getInstance()->debug('End iHomefinderOrganizerLogoutFilterImpl');
			return $content;
		}

	}//end class
}
?>