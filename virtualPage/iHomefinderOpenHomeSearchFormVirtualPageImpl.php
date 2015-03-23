<?php
if( !class_exists('IHomefinderOpenHomeSearchFormVirtualPageImpl')) {
	
	class IHomefinderOpenHomeSearchFormVirtualPageImpl implements IHomefinderVirtualPage {
	
		private $path="open-home-search";
		private $title="Open Home Search";
	
		public function __construct(){
		}
		
		public function getTitle(){
			$customTitle = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_OPEN_HOME_SEARCH_FORM );
			if( $customTitle != null && "" != $customTitle ){
				$this->title=$customTitle ;
			}			
			return $this->title;
		}
	
		public function getPageTemplate(){
			$pageTemplate = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_OPEN_HOME_SEARCH_FORM);
			//$pageTemplage = '';
			return $pageTemplate;			
		}
		
		public function getPath(){
			$customPath = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_OPEN_HOME_SEARCH_FORM );	
			if( $customPath != null && "" != $customPath ){
				$this->path = $customPath ;
			}
			return $this->path;
		}
		
				
		public function getContent( $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderOpenHomeSearchFormPageImpl');
			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl()
				. '?method=handleRequest'
				. '&viewType=json'
				. '&requestType=open-home-search-form'
				. '&authenticationToken=' . $authenticationToken
				. '&phpStyle=true';


			$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST) ;
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );
			
			$content=$idxContent;
			
			IHomefinderLogger::getInstance()->debug('End IHomefinderOpenHomeSearchFormPageImpl');
			IHomefinderLogger::getInstance()->debug('<br/><br/>' . $ihfUrl);
			return $content ;
		}
	}
		
}
?>