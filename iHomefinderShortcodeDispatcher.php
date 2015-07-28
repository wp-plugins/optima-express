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
	
	const TOPPICKS_SHORTCODE = "optima_express_toppicks";
	const FEATURED_SHORTCODE = "optima_express_featured";
	const SEARCH_RESULTS_SHORTCODE = "optima_express_search_results";
	const QUICK_SEARCH_SHORTCODE = "optima_express_quick_search";
	const SEARCH_BY_ADDRESS_SHORTCODE = "optima_express_address_search";
	const SEARCH_BY_LISTING_ID_SHORTCODE = "optima_express_listing_search";
	const MAP_SEARCH_SHORTCODE = "optima_express_map_search";
	const AGENT_LISTINGS_SHORTCODE = "optima_express_agent_listings";
	const OFFICE_LISTINGS_SHORTCODE = "optima_express_office_listings";
	const LISTING_GALLERY_SHORTCODE = "optima_express_gallery_slider";
	const BASIC_SEARCH_SHORTCODE = "optima_express_basic_search";
	const ADVANCED_SEARCH_SHORTCODE = "optima_express_advanced_search";
	const ORGANIZER_LOGIN_SHORTCODE = "optima_express_organizer_login";
	const AGENT_DETAIL_SHORTCODE = "optima_express_agent_detail";
	const VALUATION_FORM_SHORTCODE = "optima_express_valuation_form";
	const CONTACT_FORM_SHORTCODE = "optima_express_contact_form";
	const EMAIL_ALERTS_SHORTCODE = "optima_express_email_alerts";
	
	private static $instance;

	private function __construct() {
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function init() {
		add_shortcode(self::TOPPICKS_SHORTCODE, array($this, "getToppicks"));
		add_shortcode(self::FEATURED_SHORTCODE, array($this, "getFeaturedListings"));
		add_shortcode(self::SEARCH_RESULTS_SHORTCODE, array($this, "getSearchResults"));
		add_shortcode(self::QUICK_SEARCH_SHORTCODE, array($this, "getQuickSearch"));
		add_shortcode(self::SEARCH_BY_ADDRESS_SHORTCODE, array($this, "getSearchByAddress"));
		add_shortcode(self::SEARCH_BY_LISTING_ID_SHORTCODE, array($this, "getSearchByListingId"));
		add_shortcode(self::MAP_SEARCH_SHORTCODE, array($this, "getMapSearch"));
		add_shortcode(self::AGENT_LISTINGS_SHORTCODE, array($this, "getAgentListings"));
		add_shortcode(self::OFFICE_LISTINGS_SHORTCODE, array($this, "getOfficeListings"));
		add_shortcode(self::LISTING_GALLERY_SHORTCODE, array($this, "getListingGallery"));
		add_shortcode(self::BASIC_SEARCH_SHORTCODE, array($this, "getBasicSearch"));
		add_shortcode(self::ADVANCED_SEARCH_SHORTCODE, array($this, "getAdvancedSearch"));
		add_shortcode(self::ORGANIZER_LOGIN_SHORTCODE, array($this, "getOrganizerLogin"));
		add_shortcode(self::AGENT_DETAIL_SHORTCODE, array($this, "getAgentDetail"));
		add_shortcode(self::VALUATION_FORM_SHORTCODE, array($this, "getValuationForm"));
		add_shortcode(self::CONTACT_FORM_SHORTCODE, array($this, "getContactForm"));
		add_shortcode(self::EMAIL_ALERTS_SHORTCODE, array($this, "getEmailAlerts"));
	}
	
	/**
	 * @deprecated use constant
	 */
	public function getToppicksShortcode() {
		return self::TOPPICKS_SHORTCODE;
	}
	
	/**
	 * @deprecated use constant
	 */
	public function getFeaturedShortcode() {
		return self::FEATURED_SHORTCODE;
	}

	/**
	 * @deprecated use constant
	 */
	public function getSearchResultsShortcode() {
		return self::SEARCH_RESULTS_SHORTCODE;
	}

	/**
	 * @deprecated use constant
	 */
	public function getQuickSearchShortcode() {
		return self::QUICK_SEARCH_SHORTCODE;
	}

	/**
	 * @deprecated use constant
	 */
	public function getSearchByAddressShortcode() {
		return self::SEARCH_BY_ADDRESS_SHORTCODE;
	}

	/**
	 * @deprecated use constant
	 */
	public function getSearchByListingIdShortcode() {
		return self::SEARCH_BY_LISTING_ID_SHORTCODE;
	}

	/**
	 * @deprecated use constant
	 */
	public function getMapSearchShortcode() {
		return self::MAP_SEARCH_SHORTCODE;
	}		

	/**
	 * @deprecated use constant
	 */
	public function getAgentListingsShortcode() {
		return self::AGENT_LISTINGS_SHORTCODE;
	}

	/**
	 * @deprecated use constant
	 */
	public function getOfficeListingsShortcode() {
		return self::OFFICE_LISTINGS_SHORTCODE;
	}

	/**
	 * @deprecated use constant
	 */
	public function getListingGalleryShortcode() {
		return self::LISTING_GALLERY_SHORTCODE;
	}

	/**
	 * @deprecated use constant
	 */
	public function getBasicSearchShortcode() {
		return self::BASIC_SEARCH_SHORTCODE;
	}

	/**
	 * @deprecated use constant
	 */
	public function getAdvancedSearchShortcode() {
		return self::ADVANCED_SEARCH_SHORTCODE;
	}

	/**
	 * @deprecated use constant
	 */
	public function getOrganizerLoginShortcode() {
		return self::ORGANIZER_LOGIN_SHORTCODE;
	}

	/**
	 * @deprecated use constant
	 */
	public function getAgentDetailShortcode() {
		return self::AGENT_DETAIL_SHORTCODE;
	}
	
	/**
	 * @deprecated use constant
	 */
	public function getValuationFormShortcode() {
		return self::VALUATION_FORM_SHORTCODE;
	}		
	
	public function getBasicSearch($attributes) {
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::LISTING_SEARCH_FORM);
		$virtualPage->getContent();
		$content = $virtualPage->getBody();
		iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		return $content;
	}

	public function getAdvancedSearch($attributes) {
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::LISTING_ADVANCED_SEARCH_FORM);
		$virtualPage->getContent();
		$content = $virtualPage->getBody();
		iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		return $content;
	}

	public function getOrganizerLogin($attributes) {
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_LOGIN);
		$virtualPage->getContent();
		$content = $virtualPage->getBody();
		iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		return $content;
	}

	public function getAgentDetail($attributes) {
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::AGENT_DETAIL);
		$_REQUEST["agentId"] = $this->getAttribute($attributes, "agentId");
		$virtualPage->getContent();
		$content = $virtualPage->getBody();
		iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		return $content;
	}
	
	public function getValuationForm($attributes) {
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::VALUATION_FORM);
		$virtualPage->getContent();
		$content = $virtualPage->getBody();
		iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		return $content;
	}
	
	public function getContactForm($attributes) {
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::CONTACT_FORM);
		$virtualPage->getContent();
		$content = $virtualPage->getBody();
		iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		return $content;
	}
	
	public function getEmailAlerts($attributes) {
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::ORGANIZER_EDIT_SAVED_SEARCH);
		$virtualPage->getContent();
		$content = $virtualPage->getBody();
		iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		return $content;
	}
	
	public function getToppicks($attributes) {
		$content = null;
		if($this->getAttribute($attributes, "id") !== null) {
			$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::HOTSHEET_SEARCH_RESULTS);
			$_REQUEST["hotSheetId"] = $this->getAttribute($attributes, "id");
			$_REQUEST["includeMap"] = $this->getAttribute($attributes, "includeMap");
			$_REQUEST["sortBy"] = $this->getAttribute($attributes, "sortBy");
			if($this->getAttribute($attributes, "header") == "true") {
				$_REQUEST["gallery"] = false;
			} else {
				$_REQUEST["gallery"] = true;
			}
			$_REQUEST["displayType"] = $this->getAttribute($attributes, "displayType");
			$_REQUEST["resultsPerPage"] = $this->getAttribute($attributes, "resultsPerPage");
			$virtualPage->getContent();
			$content = $virtualPage->getBody();
			iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		}
		return $content;
	}
	
	public function getAgentListings($attributes) {
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::AGENT_OR_OFFICE_LISTINGS);
		$_REQUEST["agentId"] = $this->getAttribute($attributes, "agentId");
		$virtualPage->getContent();
		$content = $virtualPage->getBody();
		iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		return $content;
	}

	public function getOfficeListings($attributes) {
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::AGENT_OR_OFFICE_LISTINGS);
		$_REQUEST["officeId"] = $this->getAttribute($attributes, "officeId");
		$virtualPage->getContent();
		$content = $virtualPage->getBody();
		iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		return $content;
	}
	
	public function getFeaturedListings($attributes) {
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::FEATURED_SEARCH);
		$_REQUEST["includeMap"] = $this->getAttribute($attributes, "includeMap");
		$_REQUEST["sortBy"] = $this->getAttribute($attributes, "sortBy");
		if($this->getAttribute($attributes, "header") == "true") {
			$_REQUEST["gallery"] = false;
		} else {
			$_REQUEST["gallery"] = true;
		}
		$_REQUEST["displayType"] = $this->getAttribute($attributes, "displayType");
		$_REQUEST["resultsPerPage"] = $this->getAttribute($attributes, "resultsPerPage");
		$_REQUEST["propertyType"] = $this->getAttribute($attributes, "propertyType");
		$virtualPage->getContent();
		$content = $virtualPage->getBody();
		iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		return $content;
	}

	public function getSearchResults($attributes) {
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::LISTING_SEARCH_RESULTS);
		$bath = $this->getAttribute($attributes, "bath");
		$bed = $this->getAttribute($attributes, "bed");
		$cityId = $this->getAttribute($attributes, "cityId");
		$cityZip = $this->getAttribute($attributes, "cityZip");
		$minPrice = $this->getAttribute($attributes, "minPrice");
		$maxPrice = $this->getAttribute($attributes, "maxPrice");
		$propertyType = $this->getAttribute($attributes, "propertyType");
		if(is_numeric($cityId)) {
			$_REQUEST["cityId"] = $cityId;
		}
		if(!empty($cityZip)) {				
			//$_REQUEST["cityZip"] = $cityZip;
			$searchLinkInfo = new iHomefinderSearchLinkInfo(null, $cityZip, $propertyType, $minPrice, $maxPrice);
			if($searchLinkInfo->hasPostalCode()) {
				$_REQUEST["zip"] = $searchLinkInfo->getPostalCode();
			} else {
				$_REQUEST["city"] = $searchLinkInfo->getCity();
				if($searchLinkInfo->hasState()) {
					$_REQUEST["state"] = $searchLinkInfo->getState();
				}
			}
		}			
		if(!empty($propertyType)) {
			$_REQUEST["propertyType"] = $propertyType;
		}
		if(is_numeric($bed)) {
			$_REQUEST["bedrooms"] = $bed;
		}
		if(is_numeric($bath)) {
			$_REQUEST["bathcount"] = $bath;
		}
		if( is_numeric($minPrice)) {
			$_REQUEST["minListPrice"] = $minPrice;
		}
		if(is_numeric($maxPrice)) {
			$_REQUEST["maxListPrice"] = $maxPrice;
		}
		$_REQUEST["includeMap"] = $this->getAttribute($attributes, "includeMap");
		$_REQUEST["sortBy"] = $this->getAttribute($attributes, "sortBy");
		if($this->getAttribute($attributes, "header") == "true") {
			$_REQUEST["gallery"] = false;
		} else {
			$_REQUEST["gallery"] = true;
		}
		$_REQUEST["displayType"] = $this->getAttribute($attributes, "displayType");
		$_REQUEST["resultsPerPage"] = $this->getAttribute($attributes, "resultsPerPage");
		$virtualPage->getContent();
		$content = $virtualPage->getBody();
		iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		return $content;
	}
	
	public function getQuickSearch($attributes) {
		if(iHomefinderLayoutManager::getInstance()->supportsQuickSearchVirtualPage()) {
			$content = $this->getQuickSearchWithVirtualPage();
		} else {
			$content = $this->getQuickSearchContent($attributes);
		}
		return $content;
	}
	
	private function getQuickSearchWithVirtualPage() {
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage(iHomefinderVirtualPageFactory::LISTING_QUICK_SEARCH_FORM);
		$virtualPage->getContent();
		$content = $virtualPage->getBody();
		iHomefinderEnqueueResource::getInstance()->addToFooter($virtualPage->getHead());
		return $content;
	}

	private function getQuickSearchContent($attributes) {
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
		$remoteResponse = $remoteRequest->remoteGetRequest();
		$content = $remoteResponse->getBody();
		iHomefinderEnqueueResource::getInstance()->addToFooter($remoteResponse->getHead());
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
		$remoteResponse = $remoteRequest->remoteGetRequest();
		$content = $remoteResponse->getBody();
		iHomefinderEnqueueResource::getInstance()->addToFooter($remoteResponse->getHead());
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
		$remoteResponse = $remoteRequest->remoteGetRequest();
		$content = $remoteResponse->getBody();
		iHomefinderEnqueueResource::getInstance()->addToFooter($remoteResponse->getHead());
		return $content;
	}
	
	public function getMapSearch($attributes) {
		iHomefinderStateManager::getInstance()->setLastSearchUrl();
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
		$remoteResponse = $remoteRequest->remoteGetRequest();
		$content = $remoteResponse->getBody();
		iHomefinderEnqueueResource::getInstance()->addToFooter($remoteResponse->getHead());
		return $content;
	}

	public function getListingGallery($attributes) {
		iHomefinderStateManager::getInstance()->setLastSearchUrl();
		$hotsheetId = $this->getAttribute($attributes, "id");
		if(empty($hotsheetId)) {
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
		$remoteResponse = $remoteRequest->remoteGetRequest();
		$content = $remoteResponse->getBody();
		iHomefinderEnqueueResource::getInstance()->addToFooter($remoteResponse->getHead());
		return $content;
	}
	
	/**
	 * all values in the $attributes array are convered to lowercase
	 */
	private function getAttribute($attributes, $key) {
		return iHomefinderUtility::getInstance()->getVarFromArray($key, $attributes);
	}
	
	/**
	 * used by iHomefinderAdmin to generate shortcode string for community pages
	 */
	public function buildSearchResultsShortcode($cityZip, $propertyType, $bed, $bath, $minPrice, $maxPrice) {
		$result = $this->buildShortcode(self::SEARCH_RESULTS_SHORTCODE, array(
			"cityZip" => $cityZip,
			"propertyType" => $propertyType,
			"bed" => $bed,
			"bath" => $bath,
			"minPrice" => $minPrice,
			"maxPrice" => $maxPrice,
		));
		return $result;
	}
	
	/**
	 * @param string $slug
	 * @param array $attributes
	 * @return string
	 */
	private function buildShortcode($slug, array $attributes) {
		$result = "[";
		$result .= $slug;
		if(is_array($attributes)) {
			foreach($attributes as $name => $value) {
				$result .= " " . $name . "=\"" . $value . "\"";
			}
		}
		$result .= "]";
		return $result;
	}

}