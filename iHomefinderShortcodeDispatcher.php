<?php
if( !class_exists('IHomefinderShortcodeDispatcher')) {

	/**
	 *
	 * This singleton class is used to handle short code
	 * requests and retrieve the correct content from 
	 * a VirtualPage or other code
	 *
	 * @author ihomefinder
	 */
	class IHomefinderShortcodeDispatcher {

		private static $instance ;
		private $ihfAdmin ;

		private $content = null;
		
		private $toppicksShortCode = "optima_express_toppicks";
		private $featuredShortCode = "optima_express_featured";
		private $searchResultsShortCode = "optima_express_search_results";
		private $quickSearchShortCode = "optima_express_quick_search";
		
		private $searchFormData ;
		private $toppicksFormData ;
		
		private function __construct(){
			$this->ihfAdmin = IHomefinderAdmin::getInstance();
		}
		
		public static function getInstance(){
			if( !isset(self::$instance)){
				self::$instance = new IHomefinderShortcodeDispatcher();
			}
			return self::$instance;
		}
		
		public function init(){
			//
			add_shortcode($this->getToppicksShortcode(),      array($this, "getToppicks"));
			add_shortcode($this->getFeaturedShortcode(),      array($this, "getFeaturedListings"));
			add_shortcode($this->getSearchResultsShortcode(), array($this, "getSearchResults"));
			add_shortcode($this->getQuickSearchShortcode(),   array($this, "getQuickSearch"));			
		}
		
		public function getToppicksShortcode(){
			return $this->toppicksShortCode;
		}
		
		public function getFeaturedShortcode(){
			return $this->featuredShortCode ;
		}
		
		public function getSearchResultsShortcode(){
			return $this->searchResultsShortCode ;
		}
		
		public function getQuickSearchShortcode(){
			return $this->quickSearchShortCode ;
		}
		
		/**
		 * Get the content to replace the short code
		 *
		 * @param $content
		 */
		function getToppicks( $attr ) {
			$content='';
			if( isset($attr['id'])){
				$topPicksVirtualPage=IHomefinderVirtualPageFactory::getInstance()->getVirtualPage( IHomefinderVirtualPageFactory::HOTSHEET_SEARCH_RESULTS );
				$authenticationToken=$this->ihfAdmin->getAuthenticationToken();
				$_REQUEST['hotSheetId']=$attr['id'];
				
				if( array_key_exists("includeDisplayName", $attr) && 'false' == $attr['includeDisplayName']){
					$_REQUEST['includeDisplayName']='false';
				}
				else{
					$_REQUEST['includeDisplayName']='true';
				}
				
				$_REQUEST['includeMap']='false';
				$_REQUEST['gallery']='true';
				$content=$topPicksVirtualPage->getContent( $authenticationToken);
			}
			return $content;
		}
		
		function getFeaturedListings( $attr ) {
			$content='';
			$featuredSearchVirtualPage=IHomefinderVirtualPageFactory::getInstance()->getVirtualPage( IHomefinderVirtualPageFactory::FEATURED_SEARCH );
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();
			$_REQUEST['includeMap']='false';
			$_REQUEST['gallery']='true';
			$content=$featuredSearchVirtualPage->getContent($authenticationToken);
			return $content;
		}
		
		function getSearchResults( $attr ){
			$content='';
			$searchResultsVirtualPage=IHomefinderVirtualPageFactory::getInstance()->getVirtualPage( IHomefinderVirtualPageFactory::LISTING_SEARCH_RESULTS);
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();
			//All values in the $attr array are convered to lowercase.
			$_REQUEST['cityId']=$attr['cityid'];
			$_REQUEST['propertyType']=$attr['propertytype'];
			$_REQUEST['bedrooms']=$attr['bed'];
			$_REQUEST['bathcount']=$attr['bath'];
			$_REQUEST['minListPrice']=$attr['minprice'];
			$_REQUEST['maxListPrice']=$attr['maxprice'];
			$_REQUEST['includeMap']='false';
			$_REQUEST['gallery']='true';				
			$content=$searchResultsVirtualPage->getContent( $authenticationToken);
			return $content;
		}
		
		function getQuickSearch(){
			$quickSearchVirtualPage=IHomefinderVirtualPageFactory::getInstance()->getVirtualPage( IHomefinderVirtualPageFactory::LISTING_QUICK_SEARCH_FORM);
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();
			$content=$quickSearchVirtualPage->getContent( $authenticationToken);
			return $content ;			
		}

		function getTopPicksFormData(){
			if( !isset( $this->toppicksFormData )){
				$authenticationToken=IHomefinderAdmin::getInstance()->getAuthenticationToken();
				$ihfUrl = iHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=search-form-lists&authenticationToken=' .  $authenticationToken ;
				$this->toppicksFormData = iHomefinderRequestor::remoteRequest($ihfUrl);
			}
			return $this->toppicksFormData;
		}

		function getSearchFormData(){
			if( !isset( $this->searchFormData )){
				$authenticationToken=IHomefinderAdmin::getInstance()->getAuthenticationToken();
				$ihfUrl = iHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=search-form-lists&authenticationToken=' .  $authenticationToken ;
				$this->searchFormData = iHomefinderRequestor::remoteRequest($ihfUrl);
			}
			return $this->searchFormData;
		}
	}
}//end if( !class_exists('IHomefinderShortcodeDispatcher'))
?>