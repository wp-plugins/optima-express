<?php
if( !class_exists('IHomefinderOfficeDetailVirtualPageImpl')) {
	
	class IHomefinderOfficeDetailVirtualPageImpl implements IHomefinderVirtualPage {
	
		private $path="office-detail";
		private $title="";
		private $defaultTitle="Office Detail";
	
		public function __construct(){
		}
		
		public function getTitle(){
			$customTitle = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_OFFICE_DETAIL);
			if( $customTitle != null && "" != $customTitle ){
				$this->title=$customTitle ;
			}
			else{
				$this->title = $this->defaultTitle ;
			}

			return $this->title ;
		}
	
		public function getPageTemplate(){
			$pageTemplate = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_OFFICE_DETAIL);
			//$pageTemplage = '';
			return $pageTemplate;			
		}
		
		public function getPath(){
			$customPath = get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_OFFICE_DETAIL );	
			if( $customPath != null && "" != $customPath ){
				$this->path = $customPath ;
			}
			return $this->path;
		}
		
				
		public function getContent( $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderOfficeDetailPageImpl');

			$officeID=IHomefinderUtility::getInstance()->getQueryVar('officeID');
			//used to remember search results
			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl()
				. '?method=handleRequest'
				. '&viewType=json'
				. '&requestType=office-detail'
				. '&authenticationToken=' . $authenticationToken
				. '&phpStyle=true';

			if( is_numeric($officeID)){
				$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "officeID", $officeID);		
			}
				
			$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST) ;
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );
			
			$content=$idxContent;
			
			if( property_exists($contentInfo, "title")){
				//success, display the view
				$this->defaultTitle = $contentInfo->title ;
			}					
			
			IHomefinderLogger::getInstance()->debug('End IHomefinderOfficeDetailPageImpl');
			IHomefinderLogger::getInstance()->debug('<br/><br/>' . $ihfUrl);
			return $content ;
		}
	}
		
}
?>