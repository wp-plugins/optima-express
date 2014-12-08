<?php
if( !class_exists('IHomefinderListingSoldDetailVirtualPageImpl')) {

	class IHomefinderListingSoldDetailVirtualPageImpl implements IHomefinderVirtualPage {

		private $defaultTitle="";
		private $title = "";
		private $pageTitle=null;
		private $path ="homes-for-sale-sold-details";
		public function __construct(){

		}

		public function getTitle(){
			$customTitle = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_SOLD_DETAIL);				
			if( $customTitle != null && "" != $customTitle ){
				$this->title=$customTitle ;
			}
			else{
				$this->title = $this->defaultTitle ;
			}

			return $this->title ;
		}
		
		public function getPath(){
			$customPath = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SOLD_DETAIL );	
			if( $customPath != null && "" != $customPath ){
				$this->path = $customPath ;
			}
			return $this->path;
		}
		
		function getPageTemplate(){
			$pageTemplate = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_SOLD_DETAIL);
			//$pageTemplate = get_theme_root() . '/twentyeleven/sidebar-page.php';
			//$pageTemplage = '';
			return $pageTemplate;
		}

		public function getContent( $authenticationToken ){
			global $post;
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderListingSoldDetailVirtualPageImpl');

			$listingNumber=IHomefinderUtility::getInstance()->getQueryVar('ln');
			$boardId=IHomefinderUtility::getInstance()->getQueryVar('bid');
			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl()
				. '?ln=' . $listingNumber
				. '&bid=' . $boardId
				. '&method=handleRequest'
				. '&viewType=json'
				. '&requestType=listing-sold-detail'
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
			IHomefinderLogger::getInstance()->debug('End IHomefinderListingSoldDetailVirtualPageImpl');
			IHomefinderLogger::getInstance()->debug('<br/><br/>' . $ihfUrl);
			
			if( property_exists($contentInfo, "title")){
				//success, display the view
				$this->defaultTitle = $contentInfo->title ;
			}

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