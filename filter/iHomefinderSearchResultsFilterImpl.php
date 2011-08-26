<?php
if( !class_exists('IHomefinderSearchResultsFilterImpl')) {
	
	class IHomefinderSearchResultsFilterImpl implements IHomefinderFilter {
	
		public function __construct(){
			
		}
		
		public function getTitle(){
			return "Property Search Results";
		}	
		public function filter( $content, $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderFilter.filterSearchResults');
			$cityName=IHomefinderUtility::getInstance()->getQueryVar('cityName');
			$cityIDValues=IHomefinderUtility::getInstance()->getRequestVar('cityID');
			$cityID=null;
			if( is_array($cityIDValues)){
				foreach( $cityIDValues as $value ){
					if( $cityID != null ){
						$cityID = $cityID . ",";
					}
					$cityID = $cityID . $value;
				}
			} else {
				$cityID=$cityIDValues;
			}
			
			$zipValues=IHomefinderUtility::getInstance()->getRequestVar('zip');
			$zip=null;
			if( is_array($zipValues)){
				foreach( $zipValues as $value ){
					if( $zip != null ){
						$zip = $zip . ",";
					}
					$zip = $zip . $value;
				}
			} else {
				$zip=$zipValues;
			}						
								
			$bedrooms=IHomefinderUtility::getInstance()->getQueryVar('bedrooms');
			$bathCount=IHomefinderUtility::getInstance()->getQueryVar('bathCount');
			$minListPrice=IHomefinderUtility::getInstance()->getQueryVar('minListPrice');
			$maxListPrice=IHomefinderUtility::getInstance()->getQueryVar('maxListPrice');
			$startRowNumber=IHomefinderUtility::getInstance()->getQueryVar('startRowNumber');			
			$listingNumber=IHomefinderUtility::getInstance()->getQueryVar('listingNumber');
			$propertyCategory=IHomefinderUtility::getInstance()->getQueryVar('propertyCategory');
			$propertyType=IHomefinderUtility::getInstance()->getQueryVar('propertyType');
			$squareFeet=IHomefinderUtility::getInstance()->getQueryVar('squareFeet');
			$lotAcres=IHomefinderUtility::getInstance()->getQueryVar('lotAcres');
			$sortby=IHomefinderUtility::getInstance()->getQueryVar('sortBy');
			$openHomesOnlyYN=IHomefinderUtility::getInstance()->getQueryVar('openHomesOnlyYN');
			$dateRange=IHomefinderUtility::getInstance()->getQueryVar('dateRange');

			$streetName=IHomefinderUtility::getInstance()->getQueryVar('streetName');
			$streetNumber=IHomefinderUtility::getInstance()->getQueryVar('streetNumber');
			
			$ihfUrl = IHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=listing-search-results' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "city", $cityName);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "cityID", $cityID);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "zip", $zip);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "listingNumber", $listingNumber);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "bedrooms", $bedrooms);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "bathcount", $bathCount);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "minListPrice", $minListPrice);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "maxListPrice", $maxListPrice);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "startRowNumber", $startRowNumber);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);						
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "propertyCategory", $propertyCategory);			
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "propertyType", $propertyType);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "squareFeet", $squareFeet);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "lotAcres", $lotAcres);						
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "sortby", $sortby);

			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "streetName", $streetName);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "streetNumber", $streetNumber);
			
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "openHomesOnlyYN", $openHomesOnlyYN);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "dateRange", $dateRange);
					
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );
	
			$content=$idxContent;
			
			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End IHomefinderFilter.filterSearchResults');
			
			return $content ;
		}
		
	}
}
?>