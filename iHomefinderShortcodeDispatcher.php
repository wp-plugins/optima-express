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
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
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
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::LISTING_SEARCH_FORM);
		$content = $virtualPage->getContent();
		iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		return $content;
	}

	public function getAdvancedSearch($attributes) {
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::LISTING_ADVANCED_SEARCH_FORM);
		$content = $virtualPage->getContent();
		iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		return $content;
	}

	public function getOrganizerLogin($attributes) {
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_LOGIN);
		$content = $virtualPage->getContent();
		iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		return $content;
	}

	public function getAgentDetail($attributes) {
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::AGENT_DETAIL);
		$_REQUEST["agentID"] = $this->getAttribute($attributes, "agentID");
		$content = $virtualPage->getContent();
		iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		return $content;
	}
	
	public function getValuationForm($attributes) {
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::VALUATION_FORM);
		$content = $virtualPage->getContent();
		iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		return $content;
	}
	
	public function getToppicks($attributes) {
		$content = "";
		if($this->getAttribute($attributes, "id") != null) {
			$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::HOTSHEET_SEARCH_RESULTS);
			$_REQUEST["hotSheetId"] = $this->getAttribute($attributes, "id");
			$_REQUEST["includeMap"] = $this->getAttribute($attributes, "includeMap");
			$_REQUEST["sortBy"] = $this->getAttribute($attributes, "sortBy");
			if($this->getAttribute($attributes, "header") == "true") {
				$_REQUEST["gallery"] = "false";
			} else {
				$_REQUEST["gallery"] = "true";
			}
			$_REQUEST["displayType"] = $this->getAttribute($attributes, "displayType");
			$_REQUEST["resultsPerPage"] = $this->getAttribute($attributes, "resultsPerPage");
			$content = $virtualPage->getContent();
			iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		}
		return $content;
	}
	
	public function getAgentListings($attributes) {
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::AGENT_OR_OFFICE_LISTINGS);
		$_REQUEST["agentId"] = $this->getAttribute($attributes, "agentID");
		$content = $virtualPage->getContent();
		iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		return $content;
	}

	public function getOfficeListings($attributes) {
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::AGENT_OR_OFFICE_LISTINGS);
		$_REQUEST["officeId"] = $this->getAttribute($attributes, "officeId");
		$content = $virtualPage->getContent();
		iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		return $content;
	}
	
	public function getFeaturedListings($attributes) {
		$content = "";
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::FEATURED_SEARCH);
		$_REQUEST["includeMap"] = $this->getAttribute($attributes, "includeMap");
		$_REQUEST["sortBy"] = $this->getAttribute($attributes, "sortBy");
		if($this->getAttribute($attributes, "header") == "true") {
			$_REQUEST["gallery"] = "false";
		} else {
			$_REQUEST["gallery"] = "true";
		}
		$_REQUEST["displayType"] = $this->getAttribute($attributes, "displayType");
		$_REQUEST["resultsPerPage"] = $this->getAttribute($attributes, "resultsPerPage");
		$content = $virtualPage->getContent();
		iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		return $content;
	}

	public function getSearchResults($attributes) {
		$content = "";
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::LISTING_SEARCH_RESULTS);
		$bath = $this->getAttribute($attributes, "bath");
		$bed = $this->getAttribute($attributes, "bed");
		$cityId = $this->getAttribute($attributes, "cityId");
		$cityZip = $this->getAttribute($attributes, "cityZip");
		$minPrice = $this->getAttribute($attributes, "minPrice");
		$maxPrice = $this->getAttribute($attributes, "maxPrice");
		$propertyType = $this->getAttribute($attributes, "propertyType");
		if($cityId != null && strlen($cityId) > 0 && is_numeric($cityId)) {
			$_REQUEST["cityId"] = $cityId;
		}
		if($cityZip != null && strlen($cityZip) > 0) {				
			//$_REQUEST["cityZip"] = $cityZip;
			$searchLinkInfo = new iHomefinderSearchLinkInfo("", $cityZip, $propertyType, $minPrice, $maxPrice);
			if($searchLinkInfo->hasPostalCode()) {
				$_REQUEST["zip"] = $searchLinkInfo->getPostalCode();
			} else {
				$_REQUEST["city"] = $searchLinkInfo->getCity();
				if($searchLinkInfo->hasState()) {
					$_REQUEST["state"] = $searchLinkInfo->getState();
				}
			}
		}			
		if($propertyType != null && strlen($propertyType) > 0) {
			$_REQUEST["propertyType"] = $propertyType;
		}
		if($bed != null && strlen($bed) > 0 && is_numeric($bed)) {
			$_REQUEST["bedrooms"] = $bed;
		}
		if($bath != null && strlen($bath) > 0 && is_numeric($bath)) {
			$_REQUEST["bathcount"] = $bath;
		}
		if($minPrice != null && strlen($minPrice) > 0 && is_numeric($minPrice)) {
			$_REQUEST["minListPrice"] = $minPrice;
		}
		if($maxPrice != null && strlen($maxPrice && is_numeric($maxPrice)) > 0) {
			$_REQUEST["maxListPrice"] = $maxPrice;
		}
		$_REQUEST["includeMap"] = $this->getAttribute($attributes, "includeMap");
		$_REQUEST["sortBy"] = $this->getAttribute($attributes, "sortBy");
		if($this->getAttribute($attributes, "header") == "true") {
			$_REQUEST["gallery"] = "false";
		} else {
			$_REQUEST["gallery"] = "true";
		}
		$_REQUEST["displayType"] = $this->getAttribute($attributes, "displayType");
		$_REQUEST["resultsPerPage"] = $this->getAttribute($attributes, "resultsPerPage");
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
		$remoteRequest = new iHomefinderRequestor();
		$remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "listing-search-form")
			->addParameter("smallView", true)
			->addParameter("includeJQuery", false)
			->addParameter("includeJQueryUI", false)
			->addParameter("style", $this->getAttribute($attributes, "style"))
			->addParameter("showPropertyType", $this->getAttribute($attributes, "showPropertyType"))
		;
		$remoteRequest->setCacheExpiration(60*60);
		$contentInfo = $remoteRequest->remoteGetRequest();
		$content = $remoteRequest->getContent($contentInfo);
		iHomefinderEnqueueResource::getInstance()->addToFooter($contentInfo->head);
		return $content;
	}

	public function getSearchByAddress($attributes) {
		$remoteRequest = new iHomefinderRequestor();
		$remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "search-by-address-form")
			->addParameter("smallView", true)
			->addParameter("includeJQuery", false)
			->addParameter("includeJQueryUI", false)
			->addParameter("style", $this->getAttribute($attributes, "style"))
		;
		$remoteRequest->setCacheExpiration(60*60);
		$contentInfo = $remoteRequest->remoteGetRequest();
		$content = $remoteRequest->getContent($contentInfo);
		iHomefinderEnqueueResource::getInstance()->addToFooter($contentInfo->head);
		return $content;
	}

	public function getSearchByListingId($attributes) {
		$remoteRequest = new iHomefinderRequestor();
		$remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "search-by-listing-id-form")
			->addParameter("smallView", true)
			->addParameter("includeJQuery", false)
			->addParameter("includeJQueryUI", false)
		;
		$remoteRequest->setCacheExpiration(60*60);
		$contentInfo = $remoteRequest->remoteGetRequest();
		$content = $remoteRequest->getContent($contentInfo);
		iHomefinderEnqueueResource::getInstance()->addToFooter($contentInfo->head);
		return $content;
	}
	
	public function getMapSearch($attributes) {
		iHomefinderStateManager::getInstance()->saveLastSearch();
		$remoteRequest = new iHomefinderRequestor();
		$remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "map-search-widget")
			->addParameter("width", $this->getAttribute($attributes, "width"))
			->addParameter("height", $this->getAttribute($attributes, "height"))
			->addParameter("centerlat", $this->getAttribute($attributes, "centerlat"))
			->addParameter("centerlong", $this->getAttribute($attributes, "centerlong"))
			->addParameter("address", $this->getAttribute($attributes, "address"))
			->addParameter("zoom", $this->getAttribute($attributes, "zoom"))
		;
		$remoteRequest->setCacheExpiration(60*60);
		$contentInfo = $remoteRequest->remoteGetRequest();
		$content = $remoteRequest->getContent($contentInfo);
		iHomefinderEnqueueResource::getInstance()->addToFooter($contentInfo->head);
		return $content;
	}

	public function getListingGallery($attributes) {
		iHomefinderStateManager::getInstance()->saveLastSearch();
		if($this->getAttribute($attributes, "id") != null) {
			$hotsheetId = $this->getAttribute($attributes, "id");
		} else if($this->getAttribute($attributes, "hotsheetId") != null) {
			$hotsheetId = $this->getAttribute($attributes, "hotsheetId");
		}
		$remoteRequest = new iHomefinderRequestor();
		$remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "listing-gallery-slider")
			->addParameter("hid", $hotsheetId)
			->addParameter("width", $this->getAttribute($attributes, "width"))
			->addParameter("height", $this->getAttribute($attributes, "height"))
			->addParameter("rows", $this->getAttribute($attributes, "rows"))
			->addParameter("columns", $this->getAttribute($attributes, "columns"))
			->addParameter("effect", $this->getAttribute($attributes, "effect"))
			->addParameter("auto", $this->getAttribute($attributes, "auto"))
			->addParameter("maxResults", $this->getAttribute($attributes, "maxResults"))
		;
		$remoteRequest->setCacheExpiration(60*60);
		$contentInfo = $remoteRequest->remoteGetRequest();
		$content = $remoteRequest->getContent($contentInfo);
		iHomefinderEnqueueResource::getInstance()->addToFooter($contentInfo->head);
		return $content;
	}
	
	/**
	 * all values in the $attributes array are convered to lowercase
	 */
	public function getAttribute($attributes, $key) {
		$result = null;
		$lowerKey = strtolower($key);
		if(is_array($attributes) && array_key_exists($lowerKey, $attributes)) {
			$result = $attributes[$lowerKey];
		}
		return $result;
	}
	
	/**
	 * used by iHomefinderAdmin to generate shortcode string for community pages
	 */
	public function buildSearchResultsShortCode($cityZip, $propertyType, $bed, $bath, $minPrice, $maxPrice) {
		$searchResultsShortcode = "[";
		$searchResultsShortcode .= $this->searchResultsShortCode;
		if($cityZip != null && strlen($cityZip) > 0) {
			$searchResultsShortcode .= " cityZip=\"" . $cityZip . "\"";
		}
		if($propertyType != null && strlen($propertyType) > 0) {
			$searchResultsShortcode .= " propertyType = " . $propertyType;
		}
		if($bed != null && strlen($bed) > 0) {
			$searchResultsShortcode .= " bed = " . $bed;
		}
		if($bath != null && strlen($bath) > 0) {
			$searchResultsShortcode .= " bath = " . $bath;
		}
		if($minPrice != null && strlen($minPrice) > 0) {
			$searchResultsShortcode .= " minPrice = " . $minPrice;
		}
		if($maxPrice != null && strlen($maxPrice) > 0) {
			$searchResultsShortcode .= " maxPrice = " . $maxPrice;
		}
		$searchResultsShortcode .= "]";
		return $searchResultsShortcode;
	}

}