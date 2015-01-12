<?php
if( !class_exists('IHomefinderFeaturedSearchVirtualPageImpl')) {
	
	class IHomefinderFeaturedSearchVirtualPageImpl implements IHomefinderVirtualPage{
	
		private $path ="homes-for-sale-featured";
		private $title="Featured Properties";
		
		public function __construct(){
			
		}
			
		public function getTitle(){
			$customTitle = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_FEATURED);
			if( $customTitle != null && "" != $customTitle ){
				$this->title=$customTitle ;
			}
			
			return $this->title;
		}
	
		public function getPageTemplate(){
			$pageTemplate = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_FEATURED);
			return $pageTemplate;			
		}
		
		public function getPath(){
			$customPath = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_FEATURED );	
			if( $customPath != null && "" != $customPath ){
				$this->path = $customPath ;
			}
			return $this->path;
		}
				
		public function getContent( $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderFeaturedSearchVirtualPageImpl');
			IHomefinderStateManager::getInstance()->saveLastSearch() ;
									
			if( !is_numeric($startRowNumber)){
				$startRowNumber=1;
			}
			
			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=featured-search' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
      		$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST);
			
      		//used to remember search results
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "includeSearchSummary", "true");				
			
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);			
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );
			$content=$idxContent;
			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End IHomefinderFeaturedSearchVirtualPageImpl');
			return $content ;
		}
	}
}
?>