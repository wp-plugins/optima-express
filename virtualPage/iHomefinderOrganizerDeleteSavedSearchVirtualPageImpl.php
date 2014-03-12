<?php
if( !class_exists('IHomefinderOrganizerDeleteSavedSearchVirtualPageImpl')) {

	class IHomefinderOrganizerDeleteSavedSearchVirtualPageImpl implements IHomefinderVirtualPage {

		private $path="property-organizer-delete-saved-search-submit";
		public function __construct(){

		}

		public function getTitle(){
			return "Delete Saved Search";
		}

		public function getPageTemplate(){

		}

		public function getPath(){
			return $this->path ;
		}

		public function getContent( $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderOrganizerDeleteSavedSearchVirtualPageImpl');
			$subscriberId=IHomefinderUtility::getInstance()->getQueryVar('subscriberID');
			if(empty($subscriberId)){
				$subscriberId=IHomefinderUtility::getInstance()->getQueryVar('subscriberId');
			}
			$searchProfileId=IHomefinderUtility::getInstance()->getQueryVar('searchProfileID');
			if(empty($searchProfileId)){
				$searchProfileId=IHomefinderUtility::getInstance()->getQueryVar('searchProfileId');
			}

			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=property-organizer-delete-saved-search-submit' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "subscriberId", $subscriberId);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "searchProfileId", $searchProfileId);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);

			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			//$content = IHomefinderRequestor::getContent( $contentInfo );
			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End IHomefinderOrganizerDeleteSavedSearchVirtualPageImpl');

			$redirectUrl=IHomefinderUrlFactory::getInstance()->getOrganizerViewSavedSearchListUrl(true) ;
			//redirect to the list of saved searches to avoid double posting the request
			$content = '<meta http-equiv="refresh" content="0;url=' . $redirectUrl . '">';

			return $content ;
		}
	}//end class
}
?>