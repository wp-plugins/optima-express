<?php
if( !class_exists('IHomefinderOrganizerViewSavedListingListVirtualPageImpl')) {
	
	class IHomefinderOrganizerViewSavedListingListVirtualPageImpl implements IHomefinderVirtualPage {
	
		private $path="property-organizer-saved-listings";	
		public function __construct(){
			
		}
		public function getTitle(){
			return "Saved Listing List";
		}	

	
		public function getPageTemplate(){
			
		}
		
		public function getPath(){
			return $this->path ;
		}
				
		public function getContent( $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderOrganizerViewSavedListingListFilterImpl');
			
			$isLoggedIn = IHomefinderStateManager::getInstance()->isLoggedIn();
			if($isLoggedIn){
				$subscriberInfo=IHomefinderStateManager::getInstance()->getCurrentSubscriber();
				$subscriberId=$subscriberInfo->getId();
			}
			
			$ihfUrl = IHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=property-organizer-view-saved-listing-list' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "subscriberId", $subscriberId );
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "phpStyle", "true");
			
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );
	
			$content=$idxContent;
			
			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End IHomefinderOrganizerViewSavedListingListFilterImpl');
			
			return $content ;
		}
	}
}
?>