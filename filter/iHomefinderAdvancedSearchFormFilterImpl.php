<?php
if( !class_exists('IHomefinderAdvancedSearchFormFilterImpl')) {
	
	class IHomefinderAdvancedSearchFormFilterImpl implements IHomefinderFilter {
	
		public function __construct(){
			
		}
		public function getTitle(){
			return "Advanced Property Search";
		}			
		public function filter( $content, $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderAdvancedSearchFormFilterImpl');
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
			
			IHomefinderLogger::getInstance()->debug('End IHomefinderAdvancedSearchFormFilterImpl');
			IHomefinderLogger::getInstance()->debug('<br/><br/>' . $ihfUrl);
			return $content ;
		}
	}
}
?>