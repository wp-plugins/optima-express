<?php
if( !class_exists('IHomefinderBasicVirtualPageImpl')) {
	
	class IHomefinderBasicVirtualPageImpl implements IHomefinderVirtualPage {
	
		private $path=null;
		private $title=null;
		
		private $titleOption=null;
		private $templateOption=null;
		private $permalinkOption=null;
		
		//Value send to iHomefinder server to determine how to handle the request.
		private $requestType=null;
		
	
		public function __construct( $requestType, $titleOption, $defaultTitle, $permalinkOption, $defaultPath, $templateOption ){
			$this->requestType=$requestType ;
			
			$this->titleOption=$titleOption;
			$this->title=$defaultTitle;
			
			$this->permalinkOption=$permalinkOption ;
			$this->path=$defaultPath ;
			$this->templateOption=$templateOption;
		}
		
		public function getTitle(){
			$customTitle = get_option($this->titleOption);
			if( $customTitle != null && "" != $customTitle ){
				$this->title=$customTitle ;
			}			
			return $this->title;
		}
	
		public function getPageTemplate(){
			$pageTemplate = get_option($this->templateOption );
			//$pageTemplage = '';
			return $pageTemplate;			
		}
		
		public function getPath(){
			$customPath = get_option($this->permalinkOption );	
			if( $customPath != null && "" != $customPath ){
				$this->path = $customPath ;
			}
			return $this->path;
		}
		
				
		public function getContent( $authenticationToken ){
			IHomefinderLogger::getInstance()->debug('Begin IHomefinderBasicPageImpl');

			//used to remember search results
			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl()
				. '?method=handleRequest'
				. '&viewType=json'
				. '&requestType=' . $this->requestType
				. '&authenticationToken=' . $authenticationToken
				. '&phpStyle=true'
				. '&includeSearchSummary=true';


			$ihfUrl = iHomefinderRequestor::addVarsToUrl($ihfUrl, $_REQUEST) ;
			$contentInfo = IHomefinderRequestor::remoteRequest($ihfUrl);
			$idxContent = IHomefinderRequestor::getContent( $contentInfo );
			
			$content=$idxContent;
			
			IHomefinderLogger::getInstance()->debug('End IHomefinderBasicPageImpl');
			IHomefinderLogger::getInstance()->debug('<br/><br/>' . $ihfUrl);
			return $content ;
		}
	}
		
}
?>