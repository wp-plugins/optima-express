<?php

/**
 *
 * This singleton class is used to handle short code
 * requests and retrieve the correct content from
 * a VirtualPage or other code
 *
 * @author ihomefinder
 */
class iHomefinderShortcodeDispatcher {

	private static $instance;
	private $ihfAdmin;

	private $content = null;
	private $footer = array();
	
	private $toppicksShortCode = "optima_express_toppicks";
	private $featuredShortCode = "optima_express_featured";
	private $searchResultsShortCode = "optima_express_search_results";
	private $quickSearchShortCode = "optima_express_quick_search";
	private $searchByAddressShortCode = "optima_express_address_search";
	private $searchByListingIdShortCode = "optima_express_listing_search";
	private $mapSearchShortCode = "optima_express_map_search";
	private $agentListingsShortCode = "optima_express_agent_listings";
	private $officeListingsShortCode = "optima_express_office_listings";
	private $listingGalleryShortCode = "optima_express_gallery_slider";
	private $basicSearchShortCode = "optima_express_basic_search";
	private $advancedSearchShortCode = "optima_express_advanced_search";
	private $organizerLoginShortCode = "optima_express_organizer_login";
	private $agentDetailShortCode = "optima_express_agent_detail";
	private $valuationFormShortCode = "optima_express_valuation_form";

	private function __construct() {
		$this->ihfAdmin = iHomefinderAdmin::getInstance();
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new iHomefinderShortcodeDispatcher();
		}
		return self::$instance;
	}

	public function init() {
		add_shortcode($this->getToppicksShortcode(), array($this, "getToppicks"));
		add_shortcode($this->getFeaturedShortcode(), array($this, "getFeaturedListings"));
		add_shortcode($this->getSearchResultsShortcode(), array($this, "getSearchResults"));
		add_shortcode($this->getQuickSearchShortcode(), array($this, "getQuickSearch"));
		add_shortcode($this->getSearchByAddressShortcode(), array($this, "getSearchByAddress"));
		add_shortcode($this->getSearchByListingIdShortcode(), array($this, "getSearchByListingId"));
		add_shortcode($this->getMapSearchShortcode(), array($this, "getMapSearch"));
		add_shortcode($this->getAgentListingsShortcode(), array($this, "getAgentListings"));
		add_shortcode($this->getOfficeListingsShortcode(), array($this, "getOfficeListings"));
		add_shortcode($this->getListingGalleryShortcode(), array($this, "getListingGallery"));
		add_shortcode($this->getBasicSearchShortcode(), array($this, "getBasicSearch"));
		add_shortcode($this->getAdvancedSearchShortcode(), array($this, "getAdvancedSearch"));
		add_shortcode($this->getOrganizerLoginShortcode(), array($this, "getOrganizerLogin"));
		add_shortcode($this->getAgentDetailShortcode(), array($this, "getAgentDetail"));
		add_shortcode($this->getValuationFormShortcode(), array($this, "getValuationForm"));
	}

	public function getToppicksShortcode() {
		return $this->toppicksShortCode;
	}

	public function getFeaturedShortcode() {
		return $this->featuredShortCode;
	}

	public function getSearchResultsShortcode() {
		return $this->searchResultsShortCode;
	}

	public function getQuickSearchShortcode() {
		return $this->quickSearchShortCode;
	}

	public function getSearchByAddressShortcode() {
		return $this->searchByAddressShortCode;
	}

	public function getSearchByListingIdShortcode() {
		return $this->searchByListingIdShortCode;
	}
	
	public function getMapSearchShortcode() {
		return $this->mapSearchShortCode;
	}		
	
	public function getAgentListingsShortcode() {
		return $this->agentListingsShortCode;
	}
	
	public function getOfficeListingsShortcode() {
		return $this->officeListingsShortCode;
	}
	public function getListingGalleryShortcode() {
		return $this->listingGalleryShortCode;
	}
	
	public function getBasicSearchShortcode() {
		return $this->basicSearchShortCode;
	}

	public function getAdvancedSearchShortcode() {
		return $this->advancedSearchShortCode;
	}

	public function getOrganizerLoginShortcode() {
		return $this->organizerLoginShortCode;
	}

	public function getAgentDetailShortcode() {
		return $this->agentDetailShortCode;
	}		
	
	public function getValuationFormShortcode() {
		return $this->valuationFormShortCode;
	}		
	
	public function getBasicSearch($attributes) {
		$content='';
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::LISTING_SEARCH_FORM);
		$content = $virtualPage->getContent();
		iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		return $content;
	}

	public function getAdvancedSearch($attributes) {
		$content='';
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::LISTING_ADVANCED_SEARCH_FORM);
		$content = $virtualPage->getContent();
		iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		return $content;
	}

	public function getOrganizerLogin($attributes) {
		$content='';
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_LOGIN);
		$content = $virtualPage->getContent();
		iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		return $content;
	}

	public function getAgentDetail($attributes) {
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::AGENT_DETAIL);
		$content='';
		//All values in the $attributes array are convered to lowercase.
		if($attributes['agentid'] !=  null) {
			$_REQUEST['agentID'] = $attributes['agentid'];
		}
		$content = $virtualPage->getContent();
		iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		return $content;
	}
	
	public function getValuationForm($attributes) {
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::VALUATION_FORM);
		$content='';
		$content = $virtualPage->getContent();
		iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		return $content;
	}
	
	/**
	 * Get the content to replace the short code
	 *
	 * @param $content
	 */
	public function getToppicks($attributes) {
		$content='';
		if(isset($attributes['id'])) {
			$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::HOTSHEET_SEARCH_RESULTS);
			$_REQUEST['hotSheetId'] = $attributes['id'];
			$this->includeMap($attributes);
			$_REQUEST['sortBy'] = $attributes['sortby'];
			if(array_key_exists("header", $attributes) && 'true' ==  $attributes['header']) {
				$_REQUEST['gallery']='false';
			} else {
				$_REQUEST['gallery']='true';
			}
			if(array_key_exists("includeDisplayName", $attributes) && 'false' ==  $attributes['includeDisplayName']) {
				$_REQUEST['includeDisplayName']='false';
			} else {
				$_REQUEST['includeDisplayName']='true';
			}
			$content = $virtualPage->getContent();
			iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		}
		return $content;
	}
	
	public function includeMap($attributes) {
		if($attributes !=  null && array_key_exists("includemap", $attributes) && 'true' ==  $attributes['includemap']) {
			$_REQUEST['includeMap'] = "true";
		} else{
			$_REQUEST['includeMap'] = "false";
		}			
	}
	
	public function getAgentListings($attributes) {
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::AGENT_OR_OFFICE_LISTINGS);
		$content='';
		//All values in the $attributes array are convered to lowercase.
		if($attributes['agentid'] !=  null) {
			$_REQUEST['agentId'] = $attributes['agentid'];
		}
		$content = $virtualPage->getContent();
		iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		return $content;
	}

	public function getOfficeListings($attributes) {
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::AGENT_OR_OFFICE_LISTINGS);
		$content='';
		//All values in the $attributes array are convered to lowercase.
		if($attributes['officeid'] !=  null) {
			$_REQUEST['officeId'] = $attributes['officeid'];
		}
		$content = $virtualPage->getContent();
		iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		return $content;
	}
	
	public function getFeaturedListings($attributes) {
		$content='';
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::FEATURED_SEARCH);
		$this->includeMap($attributes);
		$_REQUEST['sortBy'] = $attributes['sortby'];
		if(array_key_exists("header", $attributes) && 'true' ==  $attributes['header']) {
			$_REQUEST['gallery']='false';
		} else {
			$_REQUEST['gallery']='true';
		}
		$content = $virtualPage->getContent();
		iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		return $content;
	}
	
	public function buildSearchResultsShortCode($cityZip, $propertyType, $bed, $bath, $minPrice, $maxPrice) {
		$searchResultsShortcode = "[";
		$searchResultsShortcode .=  $this->searchResultsShortCode;
		if($cityZip !=  null && strlen($cityZip) > 0) {
			$searchResultsShortcode .= " cityZip='" . $cityZip ."'";
		}
		if($propertyType !=  null && strlen($propertyType) > 0) {
			$searchResultsShortcode .= " propertyType = " . $propertyType;
		}
		if($bed !=  null && strlen($bed) > 0) {
			$searchResultsShortcode .= " bed = " . $bed;
		}
		if($bath !=  null && strlen($bath) > 0) {
			$searchResultsShortcode .= " bath = " . $bath;
		}
		if($minPrice !=  null && strlen($minPrice) > 0) {
			$searchResultsShortcode .= " minPrice = " . $minPrice;
		}
		if($maxPrice !=  null && strlen($maxPrice) > 0) {
			$searchResultsShortcode .= " maxPrice = " . $maxPrice;
		}
		$searchResultsShortcode .= "]";
		return $searchResultsShortcode;
	}

	public function getSearchResults($attributes) {
		$content='';
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::LISTING_SEARCH_RESULTS);
		$bath = $attributes['bath'];
		$bed = $attributes['bed'];
		$cityId = $attributes['cityid'];
		$cityZip = $attributes['cityzip'];
		$minPrice = $attributes['minprice'];
		$maxPrice = $attributes['maxprice'];
		$propertyType = $attributes['propertytype'];
		//All values in the $attributes array are convered to lowercase.
		if($cityId !=  null && strlen($cityId) > 0 && is_numeric($cityId)) {
			$_REQUEST['cityId'] = $cityId;
		}
		if($cityZip !=  null && strlen($cityZip) > 0) {				
			//$_REQUEST['cityZip'] = $cityZip;
			$searchLinkInfo = new iHomefinderSearchLinkInfo('', $cityZip, $propertyType, $minPrice, $maxPrice);
			if($searchLinkInfo->hasPostalCode()) {
				$_REQUEST['zip'] = $searchLinkInfo->getPostalCode();
			} else {
				$_REQUEST['city'] = $searchLinkInfo->getCity();
				if($searchLinkInfo->hasState()) {
					$_REQUEST['state'] = $searchLinkInfo->getState();
				}
			}
		}			
		if($propertyType !=  null && strlen($propertyType) > 0) {
			$_REQUEST['propertyType'] = $propertyType;
		}
		if($bed !=  null && strlen($bed) > 0&& is_numeric($bed)) {
			$_REQUEST['bedrooms'] = $bed;
		}
		if($bath !=  null && strlen($bath) > 0 && is_numeric($bath)) {
			$_REQUEST['bathcount'] = $bath;
		}
		if($minPrice !=  null && strlen($minPrice) > 0 && is_numeric($minPrice)) {
			$_REQUEST['minListPrice'] = $minPrice;
		}
		if($maxPrice !=  null && strlen($maxPrice && is_numeric($maxPrice)) > 0) {
			$_REQUEST['maxListPrice'] = $maxPrice;
		}
		$this->includeMap($attributes);
		$_REQUEST['sortBy'] = $attributes['sortby'];
		if(array_key_exists("header", $attributes) && 'true' ==  $attributes['header']) {
			$_REQUEST['gallery']='false';
		} else {
			$_REQUEST['gallery']='true';
		}
		$content = $virtualPage->getContent();
		iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		return $content;
	}
	
	public function getQuickSearch($attributes) {
		$content = "";
		if(iHomefinderLayoutManager::getInstance()->supportsQuickSearchVirtualPage()) {
			$content = $this->getQuickSearchWithVirtualPage();
		} else {
			$content = $this->getQuickSearchContent($attributes);
		}
		return $content;
	}
	
	public function getQuickSearchWithVirtualPage() {
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::LISTING_QUICK_SEARCH_FORM);
		$content = $virtualPage->getContent();
		iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		return $content;
	}

	public function getQuickSearchContent($attributes) {
		$requestData='method=handleRequest&viewType=json&requestType=listing-search-form';
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "smallView", "true");
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "includeJQuery", "false");
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "includeJQueryUI", "false");
		if(isset($attributes['style'])) {
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "style", $attributes['style']);
		}
		if(isset($attributes['showpropertytype'])) {
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "showPropertyType", $attributes['showpropertytype']);
		}
		$contentInfo = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$content = iHomefinderRequestor::getInstance()->getContent($contentInfo);
		iHomefinderEnqueueResource::getInstance()->addToFooter($contentInfo->head);
		return $content;
	}

	public function getSearchByAddress($attributes) {
		$requestData='method=handleRequest&viewType=json&requestType=search-by-address-form';
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "smallView", "true");
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "includeJQuery", "false");
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "includeJQueryUI", "false");
		if(isset($attributes['style'])) {
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "style", $attributes['style']);
		}
		$contentInfo = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$content = iHomefinderRequestor::getInstance()->getContent($contentInfo);
		iHomefinderEnqueueResource::getInstance()->addToFooter($contentInfo->head);
		return $content;
	}

	public function getSearchByListingId($attributes) {
		$requestData='method=handleRequest&viewType=json&requestType=search-by-listing-id-form';
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "smallView", "true");
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "includeJQuery", "false");
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "includeJQueryUI", "false");
		$contentInfo = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$content = iHomefinderRequestor::getInstance()->getContent($contentInfo);
		iHomefinderEnqueueResource::getInstance()->addToFooter($contentInfo->head);
		return $content;
	}
	
	public function getMapSearch($attributes) {
		iHomefinderStateManager::getInstance()->saveLastSearch();
		$requestData='method=handleRequest&viewType=json&requestType=map-search-widget';
		if(isset($attributes['width'])) {
			$requestData = $requestData . '&width=' . $attributes['width'];
		}
		if(isset($attributes['height'])) {
			$requestData = $requestData . '&height=' . $attributes['height'];
		}
		if(isset($attributes['centerlat'])) {
			$requestData = $requestData . '&centerlat='. $attributes['centerlat'];
		}
		if(isset($attributes['centerlong'])) {
			$requestData = $requestData . '&centerlong='. $attributes['centerlong'];
		}
		if(isset($attributes['address'])) {
			$requestData = $requestData . '&address=' . urlencode($attributes['address']);
		}
		if(isset($attributes['zoom'])) {
			$requestData = $requestData . '&zoom='. $attributes['zoom'];
		}
		$contentInfo = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$content = iHomefinderRequestor::getInstance()->getContent($contentInfo);
		iHomefinderEnqueueResource::getInstance()->addToFooter($contentInfo->head);
		return $content;
	}

	public function getListingGallery($attributes) {
		iHomefinderStateManager::getInstance()->saveLastSearch();
		$requestData='method=handleRequest&viewType=json&requestType=listing-gallery-slider';
		if(isset($attributes['width'])) {
			$requestData = $requestData . '&width=' . $attributes['width'];
		}
		if(isset($attributes['height'])) {
			$requestData = $requestData . '&height=' . $attributes['height'];
		}
		if(isset($attributes['rows'])) {
			$requestData = $requestData . '&rows=' . $attributes['rows'];
		}
		if(isset($attributes['columns'])) {
			$requestData = $requestData . '&columns=' . $attributes['columns'];
		}
		if(isset($attributes['effect'])) {
			$requestData = $requestData . '&effect=' . $attributes['effect'];
		}
		if(isset($attributes['auto'])) {
			$requestData = $requestData . '&auto=' . $attributes['auto'];
		}
		if(isset($attributes['maxresults'])) {
			$requestData = $requestData . '&maxResults=' . $attributes['maxresults'];
		}
		if(isset($attributes['id'])) {
			$requestData = $requestData . '&hid=' . $attributes['id'];
		} else if(isset($attributes['hotsheetid'])) {
			$requestData = $requestData . '&hid=' . $attributes['hotsheetid'];
		}
		$contentInfo = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$content = iHomefinderRequestor::getInstance()->getContent($contentInfo);
		iHomefinderEnqueueResource::getInstance()->addToFooter($contentInfo->head);
		return $content;
	}

}