<?php
if( !class_exists('IHomefinderContactFormVirtualPageImpl')) {
	
	class IHomefinderContactFormVirtualPageImpl implements IHomefinderVirtualPage {
	
		private $path="contact-form";
		private $title="Contact Form";
	
		public function __construct(){
			
		}
		public function getTitle(){
			$customTitle = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_CONTACT_FORM );
			if( $customTitle != null && "" != $customTitle ){
				$this->title=$customTitle ;
			}
			
			return $this->title;
		}
	
		public function getPageTemplate(){
			$pageTemplate = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_CONTACT_FORM);
			//$pageTemplage = '';
			return $pageTemplate;			
		}
		
		public function getPath(){
			$customPath = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_CONTACT_FORM );	
			if( $customPath != null && "" != $customPath ){
				$this->path = $customPath ;
			}
			return $this->path;
		}
		
				
		public function getContent( $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderContactFormVirtualPageImpl');
			$ihfUrl = IHomefinderConstants::EXTERNAL_URL 
				. '?method=handleRequest'
				. '&viewType=json'
				. '&smallView=false'
				. '&requestType=FeatureContactForm'
				. '&authenticationToken=' . $authenticationToken
				. '&phpStyle=true';


			$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST) ;
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );
				
			$content=$idxContent;
			
			IHomefinderLogger::getInstance()->debug('End IHomefinderContactFormVirtualPageImpl');
			IHomefinderLogger::getInstance()->debug('<br/><br/>' . $ihfUrl);
			return $content ;
		}
	}
		
}
?>