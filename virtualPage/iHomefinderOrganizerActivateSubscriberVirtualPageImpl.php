<?php
if( !class_exists('iHomefinderOrganizerActivateSubscriberVirtualPageImpl')) {
	
	class IHomefinderOrganizerActivateSubscriberVirtualPageImpl implements IHomefinderVirtualPage {
	
		private $path= "property-organizer-activate";
		
		public function __construct(){
			
		}
		public function getTitle(){
			return "Subscriber Activation";
		}		
				
		public function getPageTemplate(){
			
		}
		
		public function getPath(){
			return $this->path ;			
		}
		
		public function getContent( $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin iHomefinderOrganizerActivateSubscriberVirtualPageImpl');
			
			$email=IHomefinderUtility::getInstance()->getQueryVar('email');
			
			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=property-organizer-activate-subscriber' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
			$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST) ;
			
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );
	
			$content=$idxContent;
			
			IHomefinderLogger::getInstance()->debug( '<br/><br/>' . $ihfUrl ) ;
			IHomefinderLogger::getInstance()->debug('End iHomefinderOrganizerActivateSubscriberVirtualPageImpl');
			
			return $content ;
		}
	}//end class
}
?>