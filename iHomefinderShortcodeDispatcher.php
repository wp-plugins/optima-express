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
		private $mapSearchShortCode = "optima_express_map_search";
		private $agentListingsShortCode = "optima_express_agent_listings";
		private $officeListingsShortCode = "optima_express_office_listings";
		
		private $galleryFormData ;
		private $mapSearchContent ;

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
			add_shortcode($this->getToppicksShortcode(),              array($this, "getToppicks"));
			add_shortcode($this->getFeaturedShortcode(),              array($this, "getFeaturedListings"));
			add_shortcode($this->getSearchResultsShortcode(),         array($this, "getSearchResults"));
			add_shortcode($this->getQuickSearchShortcode(),           array($this, "getQuickSearch"));
			add_shortcode($this->getMapSearchShortcode(),             array($this, "getMapSearch"));
			add_shortcode($this->getAgentListingsShortcode(),         array($this, "getAgentListings"));
			add_shortcode($this->getOfficeListingsShortcode(),        array($this, "getOfficeListings"));
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
		
		public function getMapSearchShortcode(){
			return $this->mapSearchShortCode ;
		}		
		
		public function getAgentListingsShortcode(){
			return $this->agentListingsShortCode ;
		}
		
		public function getOfficeListingsShortcode(){
			return $this->officeListingsShortCode ;
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
				$this->includeMap( $attr );

				if( array_key_exists("includeDisplayName", $attr) && 'false' == $attr['includeDisplayName']){
					$_REQUEST['includeDisplayName']='false';
				}
				else{
					$_REQUEST['includeDisplayName']='true';
				}

				
				$_REQUEST['gallery']='true';
				$content=$topPicksVirtualPage->getContent( $authenticationToken);
			}
			return $content;
		}
		
		function includeMap( $attr ){
			if( $attr != null && array_key_exists("includemap", $attr) && 'true' == $attr['includemap']){
				$_REQUEST['includeMap']="true";
			}
			else{
				$_REQUEST['includeMap']="false";
			}			
		}
		
		function getAgentListings( $attr ){
			$virtualPage=IHomefinderVirtualPageFactory::getInstance()->getVirtualPage( IHomefinderVirtualPageFactory::AGENT_OR_OFFICE_LISTINGS );
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();
			$content='';
			//All values in the $attr array are convered to lowercase.
			if( $attr['agentid'] != null ){
				$_REQUEST['agentId']=$attr['agentid'];
			}
			$content=$virtualPage->getContent($authenticationToken);
			return $content;
		}

		function getOfficeListings( $attr ){
			$virtualPage=IHomefinderVirtualPageFactory::getInstance()->getVirtualPage( IHomefinderVirtualPageFactory::AGENT_OR_OFFICE_LISTINGS );
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();
			$content='';

			//All values in the $attr array are convered to lowercase.
			if( $attr['officeid'] != null ){
				$_REQUEST['officeId']=$attr['officeid'];
			}
			$content=$virtualPage->getContent($authenticationToken);
			return $content;
		}
		
		function getFeaturedListings( $attr ) {
			$content='';
			$featuredSearchVirtualPage=IHomefinderVirtualPageFactory::getInstance()->getVirtualPage( IHomefinderVirtualPageFactory::FEATURED_SEARCH );
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();
			$this->includeMap( $attr );
			
			$_REQUEST['gallery']='true';
			$content=$featuredSearchVirtualPage->getContent($authenticationToken);
			return $content;
		}

		function getSearchResults( $attr ){
			$content='';
			$searchResultsVirtualPage=IHomefinderVirtualPageFactory::getInstance()->getVirtualPage( IHomefinderVirtualPageFactory::LISTING_SEARCH_RESULTS);
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();
			
			//All values in the $attr array are convered to lowercase.
			if( $attr['cityid'] != null && strlen($attr['cityid']) > 0){
				$_REQUEST['cityId']=$attr['cityid'];
			}
			if( $attr['propertytype'] != null && strlen($attr['propertytype']) > 0){
				$_REQUEST['propertyType']=$attr['propertytype'];
			}
			if( $attr['bed'] != null && strlen($attr['bed']) > 0){
				$_REQUEST['bedrooms']=$attr['bed'];
			}
			if( $attr['bath'] != null && strlen($attr['bath']) > 0){
				$_REQUEST['bathcount']=$attr['bath'];
			}
			if( $attr['minprice'] != null && strlen($attr['minprice']) > 0){
				$_REQUEST['minListPrice']=$attr['minprice'];
			}
			if( $attr['maxprice'] != null && strlen($attr['maxprice']) > 0){
				$_REQUEST['maxListPrice']=$attr['maxprice'];
			}
			
			$this->includeMap( $attr );
						
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
		
		function getMapSearch($attr){
			IHomefinderStateManager::getInstance()->saveLastSearch() ;
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();
	        $ihfUrl = iHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=map-search-widget&authenticationToken=' . $authenticationToken
	                                                     .'&width=' .$attr['width']
	                                                     .'&height=' .$attr['height'];
            $this->mapSearchContent = iHomefinderRequestor::remoteRequest($ihfUrl);
            $content = IHomefinderRequestor::getContent( $this->mapSearchContent );
            IHomefinderLogger::getInstance()->debug( $ihfUrl);
			
			return $content;
		}				

		function getGalleryFormData(){
			if( !isset( $this->toppicksFormData )){
				$authenticationToken=IHomefinderAdmin::getInstance()->getAuthenticationToken();
				$ihfUrl = iHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=search-form-lists&authenticationToken=' .  $authenticationToken ;
				$this->galleryFormData = iHomefinderRequestor::remoteRequest($ihfUrl);
			}
			return $this->galleryFormData;
		}
	}
}//end if( !class_exists('IHomefinderShortcodeDispatcher'))
?>