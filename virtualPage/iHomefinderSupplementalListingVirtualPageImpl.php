<?php
if( !class_exists('IHomefinderSupplementalListingVirtualPageImpl')) {
	
	class IHomefinderSupplementalListingVirtualPageImpl implements IHomefinderVirtualPage {
	
		private $path="supplemental-listing";
		private $title="Supplemental Listings";
	
		public function __construct(){
		}
		
		public function getTitle(){
			$customTitle = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_SUPPLEMENTAL_LISTING);
			if( $customTitle != null && "" != $customTitle ){
				$this->title=$customTitle ;
			}			
			return $this->title;
		}
	
		public function getPageTemplate(){
			$pageTemplate = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_SUPPLEMENTAL_LISTING);
			//$pageTemplage = '';
			return $pageTemplate;			
		}
		
		public function getPath(){
			$customPath = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SUPPLEMENTAL_LISTING );	
			if( $customPath != null && "" != $customPath ){
				$this->path = $customPath ;
			}
			return $this->path;
		}
		
				
		public function getContent( $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderSupplementalListingPageImpl');
			IHomefinderStateManager::getInstance()->saveLastSearch() ;
			//used to remember search results
			$ihfUrl = IHomefinderConstants::EXTERNAL_URL 
				. '?method=handleRequest'
				. '&viewType=json'
				. '&requestType=supplemental-listing'
				. '&authenticationToken=' . $authenticationToken
				. '&phpStyle=true'
				. '&includeSearchSummary=true';


			$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST) ;
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );
			
			$content=$idxContent;
			
			IHomefinderLogger::getInstance()->debug('End IHomefinderSupplementalListingPageImpl');
			IHomefinderLogger::getInstance()->debug('<br/><br/>' . $ihfUrl);
			return $content ;
		}
	}
		
}
?>