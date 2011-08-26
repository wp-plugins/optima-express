<?php
if( !class_exists('IHomefinderOrganizerEditSavedSearchFormFilterImpl')) {
	
	class IHomefinderOrganizerEditSavedSearchFormFilterImpl implements IHomefinderFilter {
	
		public function __construct(){
			
		}
		public function getTitle(){
			return "Name This Search";
		}		
		public function filter( $content, $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderOrganizerViewSavedSearchListFilterImpl');
			
			$subscriberId=IHomefinderUtility::getInstance()->getQueryVar('subscriberID');
			$searchProfileID=IHomefinderUtility::getInstance()->getQueryVar('searchProfileID');
						
			$ihfUrl = IHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=property-organizer-edit-saved-search-form' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "subscriberId", $subscriberId);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "searchProfileID", $searchProfileID);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "phpStyle", "true");

			$searchQueryArray=IHomefinderStateManager::getInstance()->getLastSearchQueryArray();
			if( count($searchQueryArray) > 0 ){
				$cityID = trim(IHomefinderUtility::getInstance()->getVarFromArray("cityID", $searchQueryArray ));
				$zip = trim(IHomefinderUtility::getInstance()->getVarFromArray("zip", $searchQueryArray ));
				$bedrooms = trim(IHomefinderUtility::getInstance()->getVarFromArray("bedrooms", $searchQueryArray ));
				$bathCount = trim(IHomefinderUtility::getInstance()->getVarFromArray("bathCount", $searchQueryArray ) );
				$minListPrice = trim(IHomefinderUtility::getInstance()->getVarFromArray("minListPrice", $searchQueryArray ) );
				$maxListPrice = trim(IHomefinderUtility::getInstance()->getVarFromArray("maxListPrice", $searchQueryArray ) );
				$squareFeet = trim($searchQueryArray["squareFeet"]);
				
				$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "cityID", $cityID);
				$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "zip", $zip);
				$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "bedrooms", $bedrooms);
				$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "bathcount", $bathCount);
				$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "minListPrice", $minListPrice);
				$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "maxListPrice", $maxListPrice);
				$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "squareFeet", $squareFeet);
			}
			
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );
	
			$content=$idxContent;

			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End IHomefinderOrganizerEditSavedSearchFormFilterImpl');
			
			return $content ;
		}		
	}
}
?>