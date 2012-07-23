<?php
if( !class_exists('IHomefinderSoldFeaturedListingVirtualPageImpl')) {
	
	class IHomefinderSoldFeaturedListingVirtualPageImpl implements IHomefinderVirtualPage {
	
		private $path="sold-featured-listing";
		private $title="Sold Properties";
	
		public function __construct(){
		}
		
		public function getTitle(){
			$customTitle = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_SOLD_FEATURED );
			if( $customTitle != null && "" != $customTitle ){
				$this->title=$customTitle ;
			}			
			return $this->title;
		}
	
		public function getPageTemplate(){
			$pageTemplate = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_SOLD_FEATURED);
			//$pageTemplage = '';
			return $pageTemplate;			
		}
		
		public function getPath(){
			$customPath = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SOLD_FEATURED );	
			if( $customPath != null && "" != $customPath ){
				$this->path = $customPath ;
			}
			return $this->path;
		}
		
				
		public function getContent( $authenticationToken ){
			IHomefinderStateManager::getInstance()->saveLastSearch() ;
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderSoldFeaturedPageImpl');
			$ihfUrl = IHomefinderConstants::EXTERNAL_URL 
				. '?method=handleRequest'
				. '&viewType=json'
				. '&requestType=sold-featured-listing'
				. '&authenticationToken=' . $authenticationToken
				. '&phpStyle=true'
				. '&includeSearchSummary=true';


			$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST) ;
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );
			
			
			$content=$idxContent;
			
			IHomefinderLogger::getInstance()->debug('End IHomefinderSoldFeaturedPageImpl');
			IHomefinderLogger::getInstance()->debug('<br/><br/>' . $ihfUrl);
			return $content ;
		}
	}
		
}
?>