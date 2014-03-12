<?php
if( !class_exists('IHomefinderOrganizerEditSubscriberVirtualPageImpl')) {
	
	class IHomefinderOrganizerEditSubscriberVirtualPageImpl implements IHomefinderVirtualPage {
	
		private $path="property-organizer-edit-subscriber";	
		public function __construct(){
			
		}
		public function getTitle(){
			return "Organizer Help";
		}	
	
		public function getPageTemplate(){
			
		}
		
		public function getPath(){
			return $this->path ;
		}
				
		public function getContent( $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderOrganizerEditSubscriberVirtualPageImpl');
			
			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=property-organizer-edit-subscriber' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "phpStyle", "true");
			$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST) ;
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );
	
			$content=$idxContent;
			
			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End IHomefinderOrganizerEditSubscriberVirtualPageImpl');
			
			return $content ;
		}
	}
}
?>