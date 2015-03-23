<?php
if( !class_exists('IHomefinderOrganizerViewSavedSearchListVirtualPageImpl')) {
	
	class IHomefinderOrganizerViewSavedSearchListVirtualPageImpl implements IHomefinderVirtualPage {
		private $path="property-organizer-view-saved-search-list";
		//private $organizerSavedSearchesPath="property-organizer-saved-searches";
		public function __construct(){
			
		}
		public function getTitle(){
			return "Saved Search List";
		}			
			
		public function getPageTemplate(){
			
		}
		
		public function getPath(){
			return $this->path;
		}
				
		public function getContent( $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderOrganizerViewSavedSearchListFilterImpl');
			
			$isLoggedIn = IHomefinderStateManager::getInstance()->isLoggedIn();
			if($isLoggedIn){
				$subscriberInfo=IHomefinderStateManager::getInstance()->getCurrentSubscriber();
				$subscriberId=$subscriberInfo->getId();
			}	
			
			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=property-organizer-view-saved-search-list' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "subscriberId", $subscriberId);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "phpStyle", "true");
			
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );
	
			$content=$idxContent;
			
			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End IHomefinderOrganizerViewSavedSearchListFilterImpl');
			
			return $content ;
		}
	}
}
?>