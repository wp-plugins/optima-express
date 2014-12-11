<?php
if( !class_exists('IHomefinderHotsheetListVirtualPageImpl')) {
	
	class IHomefinderHotsheetListVirtualPageImpl implements IHomefinderVirtualPage {
	
		private $path="homes-for-sale-toppicks";
		
		public function __construct(){
			
		}
		public function getTitle(){
			return "Saved Search Pages";
		}
				
		public function getPageTemplate(){
			
		}
		
		public function getPath(){
			return  $this->path;
		}
				
		public function getContent( $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderHotsheetListVirtualPageImpl');
			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl()
				. '?method=handleRequest'
				. '&viewType=json'
				. '&requestType=hotsheet-list'
				. '&authenticationToken=' . $authenticationToken;
											
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );
			
			$content=$idxContent;
			
			IHomefinderLogger::getInstance()->debug('End IHomefinderHotsheetListVirtualPageImpl');
			IHomefinderLogger::getInstance()->debug('<br/><br/>' . $ihfUrl);
			return $content ;
		}
	}
}
?>