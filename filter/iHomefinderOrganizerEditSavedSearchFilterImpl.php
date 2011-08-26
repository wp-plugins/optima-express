<?php
if( !class_exists('IHomefinderOrganizerEditSavedSearchFilterImpl')) {
	
	class IHomefinderOrganizerEditSavedSearchFilterImpl implements IHomefinderFilter {
	
		public function __construct(){
			
		}
		public function getTitle(){
			return "Email Alert";
		}		
		public function filter( $content, $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderOrganizerEditSavedSearchFilterImpl');
						
			//Get all the search params from the search form and pass in the remote request.
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
			
			$subscriberId=IHomefinderUtility::getInstance()->getQueryVar('subscriberID');
			$searchProfileId=IHomefinderUtility::getInstance()->getQueryVar('searchProfileID');
			$searchProfileName=IHomefinderUtility::getInstance()->getQueryVar('searchProfileName');	

			$subscriberName=IHomefinderUtility::getInstance()->getQueryVar('subscriberName');
			$email=IHomefinderUtility::getInstance()->getQueryVar('email');
			$phone=IHomefinderUtility::getInstance()->getQueryVar('phone');				
			$actionType=IHomefinderUtility::getInstance()->getQueryVar('actionType');
			$sendEmailYn=IHomefinderUtility::getInstance()->getQueryVar('sendEmailYN');
			
			$ihfUrl = IHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=property-organizer-edit-saved-search-submit' ;
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
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "subscriberId", $subscriberId);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "searchProfileId", $searchProfileId);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "name", $searchProfileName);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "subscriberName", $subscriberName);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "email", $email);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "phone", $phone);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "actionType", $actionType);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "sendEmailYn", $sendEmailYn);

			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$content = IHomefinderRequestor::getContent( $contentInfo );	

			if(IHomefinderStateManager::getInstance()->isLoggedIn()){	
				$redirectUrl=IHomefinderUrlFactory::getInstance()->getOrganizerViewSavedSearchListUrl(true) ; 
				//redirect to the list of saved searches to avoid double posting the request
				$content = '<meta http-equiv="refresh" content="0;url=' . $redirectUrl . '">';
			} else {
				$redirectUrl=IHomefinderUrlFactory::getInstance()->getOrganizerEmailUpdatesConfirmationUrl(true) ; 
				//redirect to the list of saved searches to avoid double posting the request
				$content = '<meta http-equiv="refresh" content="0;url=' . $redirectUrl . '">';
			}
				
			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End IHomefinderOrganizerEditSavedSearchFilterImpl');
			
			return $content ;
		}
	}//end class
}
?>