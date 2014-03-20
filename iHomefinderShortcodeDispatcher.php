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

		private $toppicksShortCode          = "optima_express_toppicks";
		private $featuredShortCode          = "optima_express_featured";
		private $searchResultsShortCode     = "optima_express_search_results";
		private $quickSearchShortCode       = "optima_express_quick_search";
    private $searchByAddressShortCode   = "optima_express_address_search";
    private $searchByListingIdShortCode = "optima_express_listing_search";
		private $mapSearchShortCode         = "optima_express_map_search";
		private $agentListingsShortCode     = "optima_express_agent_listings";
		private $officeListingsShortCode    = "optima_express_office_listings";
		private $listingGalleryShortCode    = "optima_express_gallery_slider";
		private $basicSearchShortCode       = "optima_express_basic_search";
		private $advancedSearchShortCode    = "optima_express_advanced_search";
		private $organizerLoginShortCode    = "optima_express_organizer_login";
		private $agentDetailShortCode       = "optima_express_agent_detail";		
		
		private $galleryFormData ;
		private $mapSearchContent ;
		private $listingGalleryContent;

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
      add_shortcode($this->getSearchByAddressShortcode(),       array($this, "getSearchByAddress"));
      add_shortcode($this->getSearchByListingIdShortcode(),     array($this, "getSearchByListingId"));
			add_shortcode($this->getMapSearchShortcode(),             array($this, "getMapSearch"));
			add_shortcode($this->getAgentListingsShortcode(),         array($this, "getAgentListings"));
			add_shortcode($this->getOfficeListingsShortcode(),        array($this, "getOfficeListings"));
			add_shortcode($this->getListingGalleryShortcode(),        array($this, "getListingGallery"));
			add_shortcode($this->getBasicSearchShortcode(),           array($this, "getBasicSearch"));
			add_shortcode($this->getAdvancedSearchShortcode(),        array($this, "getAdvancedSearch"));
			add_shortcode($this->getOrganizerLoginShortcode(),        array($this, "getOrganizerLogin"));
			add_shortcode($this->getAgentDetailShortcode(),           array($this, "getAgentDetail"));
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
			return $this->quickSearchShortCode;
		}
    
    public function getSearchByAddressShortcode(){
			return $this->searchByAddressShortCode;
		}
    
    public function getSearchByListingIdShortcode(){
			return $this->searchByListingIdShortCode;
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
		public function getListingGalleryShortcode(){
			return $this->listingGalleryShortCode ;
		}
		
		public function getBasicSearchShortcode(){
			return $this->basicSearchShortCode ;
		}

		public function getAdvancedSearchShortcode(){
			return $this->advancedSearchShortCode ;
		}

		public function getOrganizerLoginShortcode(){
			return $this->organizerLoginShortCode ;
		}

		public function getAgentDetailShortcode(){
			return $this->agentDetailShortCode ;
		}		
		
		function getBasicSearch( $attr ) {
			$content='';
			$basicSearchVirtualPage=IHomefinderVirtualPageFactory::getInstance()->getVirtualPage( IHomefinderVirtualPageFactory::LISTING_SEARCH_FORM );
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();

			$content=$basicSearchVirtualPage->getContent($authenticationToken);
			return $content;
		}

		function getAdvancedSearch( $attr ) {
			$content='';
			$advancedSearchVirtualPage=IHomefinderVirtualPageFactory::getInstance()->getVirtualPage( IHomefinderVirtualPageFactory::LISTING_ADVANCED_SEARCH_FORM );
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();

			$content=$advancedSearchVirtualPage->getContent($authenticationToken);
			return $content;
		}

		function getOrganizerLogin( $attr ) {
			$content='';
			$organizerLoginVirtualPage=IHomefinderVirtualPageFactory::getInstance()->getVirtualPage( IHomefinderVirtualPageFactory::ORGANIZER_LOGIN );
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();

			$content=$organizerLoginVirtualPage->getContent($authenticationToken);
			return $content;
		}

		function getAgentDetail( $attr ){
			$virtualPage=IHomefinderVirtualPageFactory::getInstance()->getVirtualPage( IHomefinderVirtualPageFactory::AGENT_DETAIL );
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();
			$content='';
			//All values in the $attr array are convered to lowercase.
			if( $attr['agentid'] != null ){
				$_REQUEST['agentID']=$attr['agentid'];
			}
			$content=$virtualPage->getContent($authenticationToken);
			return $content;
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
				
				$_REQUEST['sortBy']=$attr['sortby'];
				
				if( array_key_exists("header", $attr) && 'true' == $attr['header']){
					$_REQUEST['gallery']='false';
				}
				else{
					$_REQUEST['gallery']='true';
				}
				
				if( array_key_exists("includeDisplayName", $attr) && 'false' == $attr['includeDisplayName']){
					$_REQUEST['includeDisplayName']='false';
				}
				else{
					$_REQUEST['includeDisplayName']='true';
				}
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
			
			$_REQUEST['sortBy']=$attr['sortby'];
			
			if( array_key_exists("header", $attr) && 'true' == $attr['header']){
				$_REQUEST['gallery']='false';
			} else {
				$_REQUEST['gallery']='true';
			}


			$content=$featuredSearchVirtualPage->getContent($authenticationToken);
			return $content;
		}
		
		function buildSearchResultsShortCode( $cityZip, $propertyType, $bed, $bath, $minPrice, $maxPrice){
			$searchResultsShortcode = "[";
			$searchResultsShortcode .= $this->searchResultsShortCode ;
			if($cityZip != null && strlen($cityZip) > 0){
				
				$searchResultsShortcode .=" cityZip='" . $cityZip ."'" ;
			}
			if($propertyType != null && strlen($propertyType) > 0){
				$searchResultsShortcode .=" propertyType=" . $propertyType ;
			}
			if($bed != null && strlen($bed) > 0){
				$searchResultsShortcode .=" bed=" . $bed ;
			}
			if($bath != null && strlen($bath) > 0){
				$searchResultsShortcode .=" bath=" . $bath ;
			}
			if($minPrice != null && strlen($minPrice) > 0){
				$searchResultsShortcode .=" minPrice=" . $minPrice ;
			}
			if($maxPrice != null && strlen($maxPrice) > 0){
				$searchResultsShortcode .=" maxPrice=" . $maxPrice ;
			}
			$searchResultsShortcode .="]" ;
			return $searchResultsShortcode ;
		}

		function getSearchResults( $attr ){
			$content='';
			$searchResultsVirtualPage=IHomefinderVirtualPageFactory::getInstance()->getVirtualPage( IHomefinderVirtualPageFactory::LISTING_SEARCH_RESULTS);
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();
			
			$bath=$attr['bath'];
			$bed=$attr['bed'];
			$cityId=$attr['cityid'];
			$cityZip=$attr['cityzip'];
			$minPrice=$attr['minprice'] ;
			$maxPrice=$attr['maxprice'] ;
			$propertyType=$attr['propertytype'];
			
			
						
			//All values in the $attr array are convered to lowercase.
			if( $cityId != null && strlen($cityId) > 0 && is_numeric($cityId)){
				$_REQUEST['cityId']=$cityId;
			}
			if( $cityZip != null && strlen($cityZip) > 0){				
				//$_REQUEST['cityZip']=$cityZip;
				$searchLinkInfo=new iHomefinderSearchLinkInfo('', $cityZip, $propertyType, $minPrice, $maxPrice);
				if( $searchLinkInfo->hasPostalCode( ) ){
					$_REQUEST['zip']=$searchLinkInfo->getPostalCode();
				}
				else{
					$_REQUEST['city']=$searchLinkInfo->getCity();
					if( $searchLinkInfo->hasState()){
						$_REQUEST['state']=$searchLinkInfo->getState();
					}
				}
			}			
			if( $propertyType != null && strlen($propertyType) > 0){
				$_REQUEST['propertyType']=$propertyType;
			}
			if( $bed != null && strlen($bed) > 0&& is_numeric($bed)){
				$_REQUEST['bedrooms']=$bed;
			}
			if( $bath != null && strlen($bath) > 0 && is_numeric($bath)){
				$_REQUEST['bathcount']=$bath;
			}
			if( $minPrice != null && strlen($minPrice) > 0 && is_numeric($minPrice)){
				$_REQUEST['minListPrice']=$minPrice;
			}
			if( $maxPrice != null && strlen($maxPrice && is_numeric($maxPrice)) > 0){
				$_REQUEST['maxListPrice']=$maxPrice;
			}
			
			$this->includeMap( $attr );
			
			$_REQUEST['sortBy']=$attr['sortby'];
			
			if( array_key_exists("header", $attr) && 'true' == $attr['header']){
				$_REQUEST['gallery']='false';
			} else {
				$_REQUEST['gallery']='true';
			}
			
			$content=$searchResultsVirtualPage->getContent( $authenticationToken);
			return $content;
		}
		
		function getQuickSearch($attr){
			$content="";
			if( IHomefinderLayoutManager::getInstance()->supportsQuickSearchVirtualPage()){
				$content=$this->getQuickSearchWithVirtualPage();
			}
			else{
				$content=$this->getQuickSearchContent($attr);
			}
			return $content ;
		}
		
		function getQuickSearchWithVirtualPage(){
			$quickSearchVirtualPage=IHomefinderVirtualPageFactory::getInstance()->getVirtualPage( IHomefinderVirtualPageFactory::LISTING_QUICK_SEARCH_FORM);
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();
			$content=$quickSearchVirtualPage->getContent( $authenticationToken);
			return $content ;
		}

		function getQuickSearchContent($attr){
			$authenticationToken=IHomefinderAdmin::getInstance()->getAuthenticationToken();
	    	$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=listing-search-form' ;
	    	$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
	    	$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "smallView", "true" );
	    	$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "includeJQuery", "false" );
	    	$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "includeJQueryUI", "false" );
	    		    	
	    	if(isset($attr['style'])){
	    		$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "style", $attr['style'] );
	    	}

	    	$contentInfo = iHomefinderRequestor::remoteRequest($ihfUrl);
	    	$quickSearchContent = $contentInfo->view;

	    	return $quickSearchContent;
		}
    
    function getSearchByAddress($attr) {
			$authenticationToken=IHomefinderAdmin::getInstance()->getAuthenticationToken();
	    	$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=search-by-address-form';
	    	$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
	    	$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "smallView", "true" );
	    	$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "includeJQuery", "false" );
	    	$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "includeJQueryUI", "false" );
	    		    	
	    	if(isset($attr['style'])){
	    		$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "style", $attr['style'] );
	    	}

	    	$contentInfo = iHomefinderRequestor::remoteRequest($ihfUrl);
	    	$searchByAddressContent = $contentInfo->view;

	    	return $searchByAddressContent;
		}
    
    	function getSearchByListingId($attr) {
			$authenticationToken=IHomefinderAdmin::getInstance()->getAuthenticationToken();
	    	$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=search-by-listing-id-form';
	    	$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
	    	$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "smallView", "true" );
	    	$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "includeJQuery", "false" );
	    	$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "includeJQueryUI", "false" );
	    	$contentInfo = iHomefinderRequestor::remoteRequest($ihfUrl);
	    	$searchByListingIdContent = $contentInfo->view;

	    	return $searchByListingIdContent;
		}
		
		function getMapSearch($attr){
			IHomefinderStateManager::getInstance()->saveLastSearch() ;
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();
	        $ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=map-search-widget&authenticationToken=' . $authenticationToken;
	        if(isset($attr['width'])){
	        	$ihfUrl = $ihfUrl .'&width=' .$attr['width'];
	        }
	        if(isset($attr['height'])){
	        	$ihfUrl = $ihfUrl .'&height=' .$attr['height'];
	        }
	        if(isset($attr['centerlat'])){
	        	$ihfUrl = $ihfUrl  .'&centerlat='.$attr['centerlat'];
	        }
	        if(isset($attr['centerlong'])){
	        	$ihfUrl = $ihfUrl .'&centerlong='.$attr['centerlong'];
	        }
	        if(isset($attr['address'])){
	        	$ihfUrl = $ihfUrl .'&address=' .urlencode($attr['address']);
	        }
	        if(isset($attr['zoom'])){
	        	$ihfUrl = $ihfUrl .'&zoom='.$attr['zoom'];
	        }
            $this->mapSearchContent = iHomefinderRequestor::remoteRequest($ihfUrl);
            $content = IHomefinderRequestor::getContent( $this->mapSearchContent );
            IHomefinderLogger::getInstance()->debug( $ihfUrl);
			
			return $content;
		}				

		function getGalleryFormData(){
			if( !isset( $this->toppicksFormData )){				
				$this->galleryFormData = IHomefinderSearchFormFieldsUtility::getInstance()->getFormData() ;
			}
			return $this->galleryFormData;
		}

		function getListingGallery($attr){
			$authenticationToken=$this->ihfAdmin->getAuthenticationToken();
			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=listing-gallery-slider&authenticationToken=' . $authenticationToken;
			if(isset($attr['width'])){
				$ihfUrl = $ihfUrl .'&width=' .$attr['width'];
			}
			if(isset($attr['height'])){
				$ihfUrl = $ihfUrl .'&height=' .$attr['height'];
			}
			if(isset($attr['rows'])){
				$ihfUrl = $ihfUrl  .'&rows=' .$attr['rows'];
			}
			if(isset($attr['columns'])){
				$ihfUrl = $ihfUrl .'&columns=' .$attr['columns'];
			}
			if(isset($attr['effect'])){
				$ihfUrl = $ihfUrl .'&effect=' .$attr['effect'];
			}
			if(isset($attr['auto'])){
				$ihfUrl = $ihfUrl .'&auto='   .$attr['auto'];
			}
			if(isset($attr['hotsheetid'])){
				$ihfUrl = $ihfUrl  .'&hid='    .$attr['hotsheetid'];
			}
			
			
			$this->listingGalleryContent = iHomefinderRequestor::remoteRequest($ihfUrl);
			$content = IHomefinderRequestor::getContent( $this->listingGalleryContent );
			IHomefinderLogger::getInstance()->debug( $ihfUrl);
			
			return $content;
		}

	}
}//end if( !class_exists('IHomefinderShortcodeDispatcher'))
?>