<?php
if( !class_exists('IHomefinderOrganizerDeleteSavedListingVirtualPageImpl')) {
	
	class IHomefinderOrganizerDeleteSavedListingVirtualPageImpl implements IHomefinderVirtualPage {
	
		private $path="property-organizer-delete-saved-listing-submit";
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
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderOrganizerDeleteSavedListingVirtualPageImpl');

			$savedListingId=IHomefinderUtility::getInstance()->getQueryVar('savedListingID');		
	
			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=property-organizer-delete-saved-listing-submit' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "savedListingId", $savedListingId);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
			//IHomefinderRequestor will append the subscriber id to this request.			
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$content = IHomefinderRequestor::getContent( $contentInfo );
						
			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End IHomefinderOrganizerDeleteSavedListingVirtualPageImpl');
			
			
			return $content ;
		}
	}//end class
}
?>