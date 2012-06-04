<?php
if( !class_exists('IHomefinderOrganizerHelpVirtualPageImpl')) {
	
	class IHomefinderOrganizerHelpVirtualPageImpl implements IHomefinderVirtualPage {
	
		private $path="property-organizer-help";	
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
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderOrganizerHelpFilterImpl');
			
			$ihfUrl = IHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=property-organizer-help' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "phpStyle", "true");
			
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );
	
			$content=$idxContent;
			
			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End IHomefinderOrganizerHelpFilterImpl');
			
			return $content ;
		}
	}
}
?>