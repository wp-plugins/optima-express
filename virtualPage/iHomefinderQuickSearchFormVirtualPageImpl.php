<?php
if( !class_exists('IHomefinderQuickSearchFormVirtualPageImpl')) {
	
	class IHomefinderQuickSearchFormVirtualPageImpl implements IHomefinderVirtualPage {
	
		private $path="";
		
		public function __construct(){
			
		}
		public function getTitle(){
			return "";
		}			
			
		public function getPageTemplate(){
			
		}
		
		public function getPath(){
			return $this->path;
		}
				
		public function getContent( $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderQuickSearchFormFilterImpl.filter');
			$ihfUrl = IHomefinderConstants::EXTERNAL_URL 
				. '?method=handleRequest'
				. '&viewType=json'
				. '&requestType=listing-quick-search-form'
				. '&authenticationToken=' . $authenticationToken
				. '&phpStyle=true'
				. '&includeJQuery=false'
				. '&includeJQueryUI=false';

				
			IHomefinderLogger::getInstance()->debug('ihfUrl: ' . $ihfUrl);	

			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );
			
			$content=$idxContent;
			
			IHomefinderLogger::getInstance()->debug('End IHomefinderQuickSearchFormFilterImpl.filter');
			IHomefinderLogger::getInstance()->debug('<br/><br/>' . $ihfUrl);
			return $content ;
		}
	}
}
?>