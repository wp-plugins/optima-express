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
	
	private function isSpam() {
		$spam = true;			
		$spamChecker = iHomefinderUtility::getInstance()->getRequestVar("JKGH00920");
		if(empty($spamChecker)) {
			//if spamChecker is NOT empty, then we know this is SPAM
			$spam = false;
		}
		return $spam;
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	public function requestMoreInfo() {
		if(!$this->isSpam()) {
			$remoteRequest = new iHomefinderRequestor();
			$remoteRequest
				->addParameter("method", "handleRequest")
				->addParameter("viewType", "json")
				->addParameter("requestType", "request-more-info")
			;
			$remoteRequest->addParameters($_REQUEST);
			$contentInfo = $remoteRequest->remoteGetRequest();
			$content = $remoteRequest->getContent($contentInfo);
			if(property_exists($contentInfo, "head")) {
				$content .= $contentInfo->head;
			}
			echo $content;
		}
		die(); //don't remove
	}

	public function contactFormRequest() {
		if(!$this->isSpam()) {
			$remoteRequest = new iHomefinderRequestor();
			$remoteRequest
				->addParameter("method", "handleRequest")
				->addParameter("viewType", "json")
				->addParameter("requestType", "contact-form")
			;
			$remoteRequest->addParameters($_REQUEST);
			$contentInfo = $remoteRequest->remoteGetRequest();
			$content = $remoteRequest->getContent($contentInfo);
			if(property_exists($contentInfo, "head")) {
				$content .= $contentInfo->head;
			}
			echo $content;
		}
		die(); //don't remove
	}

	public function scheduleShowing() {
		if(!$this->isSpam()) {
			$remoteRequest = new iHomefinderRequestor();
			$remoteRequest
				->addParameter("method", "handleRequest")
				->addParameter("viewType", "json")
				->addParameter("requestType", "schedule-showing")
			;
			$remoteRequest->addParameters($_REQUEST);	
			$contentInfo = $remoteRequest->remoteGetRequest();				
			$content = $remoteRequest->getContent($contentInfo);
			if(property_exists($contentInfo, "head")) {
				$content .= $contentInfo->head;
			}
			echo $content;
		}
		die(); //don't remove
	}

	public function photoTour() {
		$action = iHomefinderUtility::getInstance()->getRequestVar("action");
		$listingNumber = iHomefinderUtility::getInstance()->getRequestVar("listingNumber");
		$boardId = iHomefinderUtility::getInstance()->getRequestVar("boardID");
		$remoteRequest = new iHomefinderRequestor();
		$remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "photo-tour")
			->addParameter("listingNumber", $listingNumber)
			->addParameter("boardId", $boardId)
		;
		$contentInfo = $remoteRequest->remoteGetRequest();
		$content = $remoteRequest->getContent($contentInfo);
		if(property_exists($contentInfo, "head")) {
			$content .= $contentInfo->head;
		}
		echo $content;
		die(); //don't remove
	}

	public function saveProperty() {
		$remoteRequest = new iHomefinderRequestor();
		$remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "save-property")
		;
		$remoteRequest->addParameters($_REQUEST);
		$contentInfo = $remoteRequest->remoteGetRequest();
		$content = $remoteRequest->getContent($contentInfo);
		if(property_exists($contentInfo, "head")) {
			$content .= $contentInfo->head;
		}
		echo $content;
		die(); //don't remove
	}

	public function saveSearch() {
		$name = iHomefinderUtility::getInstance()->getRequestVar("name");
		$remoteRequest = new iHomefinderRequestor();
		$remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "save-search")
			->addParameter("subscriberName", $name)
			->addParameter("modal", true)
		;
		$remoteRequest->addParameters($_REQUEST);
		//we need to initialize here for Ajax requests, when trying to save a search
		iHomefinderStateManager::getInstance()->initialize();
		$lastSearchQuery = iHomefinderStateManager::getInstance()->getLastSearchQuery();
		$remoteRequest->addParameters($lastSearchQuery);
		$contentInfo = $remoteRequest->remoteGetRequest();
		$content = $remoteRequest->getContent($contentInfo);
		if(property_exists($contentInfo, "head")) {
			$content .= $contentInfo->head;
		}
		echo $content;
		die(); //don't remove
	}
	
	public function advancedSearchMultiSelects() {
		$remoteRequest = new iHomefinderRequestor();
		$remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "advanced-search-multi-select-values")
			->addParameter("phpStyle", true)
		;
		$remoteRequest->addParameters($_REQUEST);
		$contentInfo = $remoteRequest->remoteGetRequest();
		$content = $remoteRequest->getContent($contentInfo);
		if(property_exists($contentInfo, "head")) {
			$content .= $contentInfo->head;
		}
		echo $content;
		die(); //don't remove
	}
	
	public function getAdvancedSearchFormFields() {
		$boardId = iHomefinderUtility::getInstance()->getRequestVar("boardID");
		$remoteRequest = new iHomefinderRequestor();
		$remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "advanced-search-fields")
			->addParameter("boardID", $boardId)
			->addParameter("phpStyle", true)
		;
		$contentInfo = $remoteRequest->remoteGetRequest();
		$content = $remoteRequest->getContent($contentInfo);
		if(property_exists($contentInfo, "head")) {
			$content .= $contentInfo->head;
		}
		echo $content;
		die(); //don't remove
	}		
	
	public function leadCaptureLogin() {
		$remoteRequest = new iHomefinderRequestor();
		$remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "lead-capture-login")
			->addParameter("phpStyle", true)
		;
		$remoteRequest->addParameters($_REQUEST);	
		$contentInfo = $remoteRequest->remoteGetRequest();
		$content = $remoteRequest->getContent($contentInfo);
		if(property_exists($contentInfo, "head")) {
			$content .= $contentInfo->head;
		}
		echo $content;
		die(); //don't remove
	}
	
	public function addSavedListingComments() {
		$remoteRequest = new iHomefinderRequestor();
		$remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "saved-listing-comments")
		;
		$remoteRequest->addParameters($_REQUEST);	
		$contentInfo = $remoteRequest->remoteGetRequest();
		$content = $remoteRequest->getContent($contentInfo);
		if(property_exists($contentInfo, "head")) {
			$content .= $contentInfo->head;
		}
		die(); //don't remove
	}
	
	public function addSavedListingRating() {
		$remoteRequest = new iHomefinderRequestor();
		$remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "saved-listing-rating")
		;
		$remoteRequest->addParameters($_REQUEST);	
		$contentInfo = $remoteRequest->remoteGetRequest();
		
		$content = $remoteRequest->getContent($contentInfo);
		if(property_exists($contentInfo, "head")) {
			$content .= $contentInfo->head;
		}
		die(); //don't remove
	}

	public function saveListingForSubscriberInSession() {
		$remoteRequest = new iHomefinderRequestor();
		$remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "save-listing-subscriber-session")
		;
		$remoteRequest->addParameters($_REQUEST);
		$contentInfo = $remoteRequest->remoteGetRequest();
		
		$content = $remoteRequest->getContent($contentInfo);
		if(property_exists($contentInfo, "head")) {
			$content .= $contentInfo->head;
		}
		echo $content;
		die(); //don't remove
	}
	
	public function saveSearchForSubscriberInSession() {
		$remoteRequest = new iHomefinderRequestor();
		$remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "save-search-subscriber-session")
		;
		$remoteRequest->addParameters($_REQUEST);
		$contentInfo = $remoteRequest->remoteGetRequest();
		
		$content = $remoteRequest->getContent($contentInfo);
		if(property_exists($contentInfo, "head")) {
			$content .= $contentInfo->head;
		}
		echo $content;
		die(); //don't remove
	}
	
	public function getAutocompleteMatches() {
		$remoteRequest = new iHomefinderRequestor();
		$remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "area-autocomplete")
		;
		$remoteRequest->addParameters($_REQUEST);
		$contentInfo = $remoteRequest->remoteGetRequest();
		$json = $remoteRequest->getJson($contentInfo);
		echo $json;
		die(); //don't remove	
	}
	
	public function sendPassword() {
		if(!$this->isSpam()) {
			$remoteRequest = new iHomefinderRequestor();
			$remoteRequest
				->addParameter("method", "handleRequest")
				->addParameter("viewType", "json")
				->addParameter("requestType", "send-password")
			;
			$remoteRequest->addParameters($_REQUEST);
			$contentInfo = $remoteRequest->remoteGetRequest();
			$content = $remoteRequest->getContent($contentInfo);
			if(property_exists($contentInfo, "head")) {
				$content .= $contentInfo->head;
			}
			echo $content;
		}
		die(); //don't remove
	}
	
}