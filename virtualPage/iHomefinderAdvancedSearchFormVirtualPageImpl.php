<?php
if( !class_exists('IHomefinderAdvancedSearchFormVirtualPageImpl')) {
	
	class IHomefinderAdvancedSearchFormVirtualPageImpl implements IHomefinderVirtualPage {
	
		private $path="homes-for-sale-search-advanced";
		private $title="Advanced Property Search";
		
		public function __construct(){
			
		}
			public function getTitle(){
			$customTitle = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_ADV_SEARCH);
			if( $customTitle != null && "" != $customTitle ){
				$this->title=$customTitle ;
			}
			
			return $this->title;
		}
	
		public function getPageTemplate(){
			$pageTemplate = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_ADV_SEARCH);
			//$pageTemplage = '';
			return $pageTemplate;			
		}
		
		public function getPath(){
			$customPath = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_ADV_SEARCH );	
			if( $customPath != null && "" != $customPath ){
				$this->path = $customPath ;
			}
			return $this->path;
		}
		
		public function getContent( $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderAdvancedSearchFormVirtualPageImpl');
			$boardId=IHomefinderUtility::getInstance()->getQueryVar('bid');
			$ihfUrl = IHomefinderConstants::EXTERNAL_URL 
				. '?method=handleRequest'
				. '&viewType=json'
				. '&requestType=listing-advanced-search-form'
				. '&authenticationToken=' . $authenticationToken
				. '&phpStyle=true';
				
			if( is_numeric($boardId)){
				$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "boardId", $boardId);		
			}

			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );
			
			$content=$idxContent;
			
			IHomefinderLogger::getInstance()->debug('End IHomefinderAdvancedSearchFormVirtualPageImpl');
			IHomefinderLogger::getInstance()->debug('<br/><br/>' . $ihfUrl);
			return $content ;
		}
	}
}
?>