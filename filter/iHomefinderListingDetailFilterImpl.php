<?php
if( !class_exists('IHomefinderListingDetailFilterImpl')) {

	class IHomefinderListingDetailFilterImpl implements IHomefinderFilter {

		private $title = "Property Details";
		private $pageTitle=null;
		public function __construct(){

		}

		public function getTitle(){
			return $this->title ;
		}

		public function filter( $content, $authenticationToken ){
			global $post;
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderFilter.filterListingDetail');

			$listingNumber=IHomefinderUtility::getInstance()->getQueryVar('ln');
			$boardId=IHomefinderUtility::getInstance()->getQueryVar('bid');
			$ihfUrl = IHomefinderConstants::EXTERNAL_URL
				. '?ln=' . $listingNumber
				. '&bid=' . $boardId
				. '&method=handleRequest'
				. '&viewType=json'
				. '&requestType=listing-detail'
				. '&authenticationToken=' . $authenticationToken;

			$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST) ;
			
			$ihfUrl = IHomefinderUtility::getInstance()->setPreviousAndNextInformation($ihfUrl,$boardId, $listingNumber ) ;

			IHomefinderLogger::getInstance()->debug('before logged in check');
			if( IHomefinderStateManager::getInstance()->isLoggedIn() ){
				IHomefinderLogger::getInstance()->debug('is logged in');
				$subscriberInfo=IHomefinderStateManager::getInstance()->getCurrentSubscriber() ;
				$ihfUrl = IHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "subscriberId", $subscriberInfo->getId());
			}

			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );
			$content=$idxContent;
			IHomefinderLogger::getInstance()->debug('End IHomefinderFilter.filterListingDetail');
			IHomefinderLogger::getInstance()->debug('<br/><br/>' . $ihfUrl);
			
//			if( property_exists($contentInfo, "title")){
//				//success, display the view
//				$this->title = $contentInfo->title ;
//			}

			$previousSearchLink = $this->getPreviousSearchLink();
			$content = $previousSearchLink . '<br/><br/>' . $content ;

			return $content ;
		}
		


		/**
		 *
		 * @param unknown_type $content
		 */
		private function getPreviousSearchLink(){

			$previousSearchUrl=IHomefinderStateManager::getInstance()->getLastSearch();

			//If previous search does not exist, then use an empty search form
			if( $previousSearchUrl == null || trim( $previousSearchUrl) == ''){
				$previousSearchUrl= IHomefinderUrlFactory::getInstance()->getListingsSearchFormUrl(true);
				$previousSearchUrl="<a href='" . $previousSearchUrl . "'>&lt;&nbsp;New Search</a>";
			}
			else{
				$previousSearchUrl="<a href='" . $previousSearchUrl . "'>&lt;&nbsp;Return To Results</a>";
			}

			return $previousSearchUrl;
		}
	}
}
?>