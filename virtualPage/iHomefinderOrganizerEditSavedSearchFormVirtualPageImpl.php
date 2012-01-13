<?php
if( !class_exists('IHomefinderOrganizerEditSavedSearchFormVirtualPageImpl')) {
	
	class IHomefinderOrganizerEditSavedSearchFormVirtualPageImpl implements IHomefinderVirtualPage {
	
		private $path="email-alerts";
		private $title="Email Alerts";
		
		public function __construct(){
			
		}
		
		public function getTitle(){
			$customTitle = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_EMAIL_UPDATES);
			if( $customTitle != null && "" != $customTitle ){
				$this->title=$customTitle ;
			}
			
			return $this->title;
		}
	
		public function getPageTemplate(){
			$pageTemplate = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_EMAIL_UPDATES);
			//$pageTemplage = '';
			return $pageTemplate;			
		}
		
		public function getPath(){
			$customPath = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_EMAIL_UPDATES );	
			if( $customPath != null && "" != $customPath ){
				$this->path = $customPath ;
			}
			return $this->path;
		}
		
			
		public function getContent( $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderOrganizerViewSavedSearchListVirtualPageImpl');
			
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
			IHomefinderLogger::getInstance()->debug('End IHomefinderOrganizerEditSavedSearchFormVirtualPageImpl');
			
			return $content ;
		}		
	}
}
?>