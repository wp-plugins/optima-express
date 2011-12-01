<?php
if( !class_exists('IHomefinderQuickSearchFormFilterImpl')) {
	
	class IHomefinderQuickSearchFormFilterImpl implements IHomefinderFilter {
	
		public function __construct(){
			
		}
		public function getTitle(){
			return "";
		}			
		public function filter( $content, $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderQuickSearchFormFilterImpl.filter');
			$ihfUrl = IHomefinderConstants::EXTERNAL_URL 
				. '?method=handleRequest'
				. '&viewType=json'
				. '&requestType=listing-quick-search-form'
				. '&authenticationToken=' . $authenticationToken
				. '&phpStyle=true'
				. '&includeJQuery=false';

				
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