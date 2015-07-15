<?php

/**
 * This class is handle all iHomefinder Ajax Requests.
 * It proxies the requests and returns the proper results.
 *
 * @author ihomefinder
 */
class iHomefinderAjaxHandler {

	private static $instance;

	private function __construct() {
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function requestMoreInfo() {
		$this->basicAjaxSubmit("request-more-info");
	}

	public function contactFormRequest() {
		$this->basicAjaxSubmit("contact-form");
	}

	public function scheduleShowing() {
		$this->basicAjaxSubmit("schedule-showing");
	}

	public function photoTour() {
		$boardId = iHomefinderUtility::getInstance()->getRequestVar("boardID");
		$this->basicAjaxSubmit("photo-tour", array(
			"boardId" => $boardId
		));
	}

	public function saveProperty() {
		$this->basicAjaxSubmit("save-property");
	}

	public function saveSearch() {
		$name = iHomefinderUtility::getInstance()->getRequestVar("name");
		$remoteRequest = new iHomefinderRequestor();
		$remoteRequest
			->addParameters($_REQUEST)
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "save-search")
			->addParameter("subscriberName", $name)
			->addParameter("modal", true)
		;
		//we need to initialize here for Ajax requests, when trying to save a search
		iHomefinderStateManager::getInstance()->initialize();
		$lastSearchQuery = iHomefinderStateManager::getInstance()->getLastSearchQuery();
		$remoteRequest->addParameters($lastSearchQuery);
		$remoteResponse = $remoteRequest->remoteGetRequest();
		$content = $remoteResponse->getBody() . $remoteResponse->getHead();
		echo $content;
		die(); //don't remove
	}
	
	public function leadCaptureLogin() {
		$this->basicAjaxSubmit("lead-capture-login");
	}
	
	public function addSavedListingComments() {
		$this->basicAjaxSubmit("saved-listing-comments");
	}
	
	public function addSavedListingRating() {
		$this->basicAjaxSubmit("saved-listing-rating");
	}

	public function saveListingForSubscriberInSession() {
		$this->basicAjaxSubmit("save-listing-subscriber-session");
	}
	
	public function saveSearchForSubscriberInSession() {
		$this->basicAjaxSubmit("save-search-subscriber-session");
	}
	
	public function sendPassword() {
		$this->basicAjaxSubmit("send-password");
	}
	
	public function emailAlertPopup() {
		$this->basicAjaxSubmit("email-alert-popup");
	}
	
	public function emailListing() {
		$this->basicAjaxSubmit("email-listing");
	}
	
	/**
	 * @deprecated not implemented
	 */
	public function advancedSearchMultiSelects() {
		$this->basicAjaxSubmit("advanced-search-multi-select-values");
	}
	
	/**
	 * @deprecated
	 */
	public function getAdvancedSearchFormFields() {
		$this->basicAjaxSubmit("advanced-search-fields");
	}
	
	/**
	 * @deprecated
	 */
	public function getAutocompleteMatches() {
		$remoteRequest = new iHomefinderRequestor();
		$remoteRequest
		->addParameters($_REQUEST)
		->addParameter("method", "handleRequest")
		->addParameter("viewType", "json")
		->addParameter("requestType", "area-autocomplete")
		;
		$remoteResponse = $remoteRequest->remoteGetRequest();
		$content = $remoteResponse->getJson();
		echo $content;
		die(); //don't remove
	}
	
	/**
	 * @param string $requestType
	 * @param array $parameters
	 */
	private function basicAjaxSubmit($requestType, $parameters = array()) {
		$remoteRequest = new iHomefinderRequestor();
		$remoteRequest
			->addParameters($_REQUEST)
			->addParameters($parameters)
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", $requestType)
			->addParameter("phpStyle", true)
		;
		$remoteResponse = $remoteRequest->remoteGetRequest();
		$content = $remoteResponse->getBody() . $remoteResponse->getHead();
		echo $content;
		die(); //don't remove
	}
	
}