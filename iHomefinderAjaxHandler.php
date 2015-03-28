<?php

/**
 * This class is handle all iHomefinder Ajax Requests.
 * It proxies the requests and returns the proper results.
 *
 * @author ihomefinder
 */
class iHomefinderAjaxHandler {

	private static $instance;
	private $ihfAdmin;

	private function __construct() {
		$this->ihfAdmin = iHomefinderAdmin::getInstance();
	}
	
	private function isSpam() {
		$isSpam=true;			
		$spamChecker = iHomefinderUtility::getInstance()->getRequestVar('JKGH00920');
		if(iHomefinderUtility::getInstance()->isStringEmpty($spamChecker)) {
			//if spamChecker is NOT empty, then we know this is SPAM
			$isSpam=false;
		}
		return $isSpam;
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new iHomefinderAjaxHandler();
		}
		return self::$instance;
	}

	public function requestMoreInfo() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderAjaxHandler.requestMoreInfo');
		$isSpam=$this->isSpam();			
		if($isSpam == false) {
			$requestData = 'method=handleRequest&viewType=json&requestType=request-more-info';
			$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);
			$contentInfo = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
			$content = iHomefinderRequestor::getInstance()->getContent($contentInfo);
			if(property_exists($contentInfo, "head")) {
				$content .= $contentInfo->head;
			}
			iHomefinderLogger::getInstance()->debug($requestData);
			iHomefinderLogger::getInstance()->debug('End iHomefinderAjaxHandler.requestMoreInfo');
			echo $content;
		}
		die(); //don't remove
	}

	public function contactFormRequest() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderAjaxHandler.contactFormRequest');
		$isSpam=$this->isSpam();			
		if($isSpam == false) {
			$requestData = 'method=handleRequest&viewType=json&requestType=contact-form';
			$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);
			$contentInfo = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
			$content = iHomefinderRequestor::getInstance()->getContent($contentInfo);
			if(property_exists($contentInfo, "head")) {
				$content .= $contentInfo->head;
			}
			iHomefinderLogger::getInstance()->debug($requestData);
			iHomefinderLogger::getInstance()->debug('End iHomefinderAjaxHandler.contactFormRequest');

			echo $content;
		}
		die(); //don't remove
	}

	public function scheduleShowing() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderAjaxHandler.scheduleShowing');
		$isSpam=$this->isSpam();
		if($isSpam == false) {
			$requestData = 'method=handleRequest&viewType=json&requestType=schedule-showing';
			$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);	
			$contentInfo = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);				
			$content = iHomefinderRequestor::getInstance()->getContent($contentInfo);
			if(property_exists($contentInfo, "head")) {
				$content .= $contentInfo->head;
			}
			iHomefinderLogger::getInstance()->debug($requestData);
			iHomefinderLogger::getInstance()->debug('End iHomefinderAjaxHandler.scheduleShowing');
			echo $content;
		}
		die(); //don't remove
	}

	public function photoTour() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderAjaxHandler.photoTour');
		$action = iHomefinderUtility::getInstance()->getRequestVar('action');
		$listingNumber = iHomefinderUtility::getInstance()->getRequestVar('listingNumber');
		$boardID = iHomefinderUtility::getInstance()->getRequestVar('boardID');
		$requestData = 'method=handleRequest&viewType=json&requestType=photo-tour';
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "listingNumber", $listingNumber);
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "boardId", $boardID);
		$contentInfo = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$content = iHomefinderRequestor::getInstance()->getContent($contentInfo);
		if(property_exists($contentInfo, "head")) {
			$content .= $contentInfo->head;
		}
		iHomefinderLogger::getInstance()->debug($requestData);
		iHomefinderLogger::getInstance()->debug('End iHomefinderAjaxHandler.photoTour');
		echo $content;
		die(); //don't remove
	}

	public function saveProperty() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderAjaxHandler.saveProperty');
		$requestData = 'method=handleRequest&viewType=json&requestType=save-property';
		$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);
		$requestData = str_replace("&method=saveProperty", "", $requestData);
		$contentInfo = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		iHomefinderLogger::getInstance()->debugDumpVar($contentInfo);
		$content = iHomefinderRequestor::getInstance()->getContent($contentInfo);
		if(property_exists($contentInfo, "head")) {
			$content .= $contentInfo->head;
		}
		echo $content;
		die(); //don't remove
	}

	public function saveSearch() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderAjaxHandler.saveSearch');
		$name = iHomefinderUtility::getInstance()->getRequestVar('name');
		$requestData = 'method=handleRequest&viewType=json&requestType=save-search';
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "subscriberName", $name);
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "modal", "true");
		$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);
		//we need to initialize here for Ajax requests, when trying to save a search
		iHomefinderStateManager::getInstance()->initialize();
		$lastSearchQueryString = iHomefinderStateManager::getInstance()->getLastSearchQueryString();
		$lastSearchQueryString = str_replace('[]', '', $lastSearchQueryString);
		$lastSearchQueryString = str_replace('%5B%5D', '', $lastSearchQueryString);
		$requestData .= '&' . $lastSearchQueryString;
		$contentInfo = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$content = iHomefinderRequestor::getInstance()->getContent($contentInfo);
		if(property_exists($contentInfo, "head")) {
			$content .= $contentInfo->head;
		}
		iHomefinderLogger::getInstance()->debugDumpVar($contentInfo);
		iHomefinderLogger::getInstance()->debug($requestData);
		iHomefinderLogger::getInstance()->debug('End iHomefinderAjaxHandler.saveSearch');
		echo $content;
		die(); //don't remove
	}
	
	public function advancedSearchMultiSelects() {
		iHomefinderLogger::getInstance()->debug('Begin advancedSearchMultiSelects');
		$requestData = 'method=handleRequest&viewType=json&requestType=advanced-search-multi-select-values';
		$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "phpStyle", "true");
		$contentInfo = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$content = iHomefinderRequestor::getInstance()->getContent($contentInfo);
		if(property_exists($contentInfo, "head")) {
			$content .= $contentInfo->head;
		}
		echo $content;
		iHomefinderLogger::getInstance()->debug('End advancedSearchMultiSelects');
		die(); //don't remove
	}
	
	public function getAdvancedSearchFormFields() {
		iHomefinderLogger::getInstance()->debug('Begin getAdvancedSearchFormFields');
		$boardID = iHomefinderUtility::getInstance()->getRequestVar('boardID');
		$requestData = 'method=handleRequest&viewType=json&requestType=advanced-search-fields';
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "boardID", $boardID);
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "phpStyle", "true");
		$contentInfo = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$content = iHomefinderRequestor::getInstance()->getContent($contentInfo);
		if(property_exists($contentInfo, "head")) {
			$content .= $contentInfo->head;
		}
		echo $content;
		iHomefinderLogger::getInstance()->debug('End getAdvancedSearchFormFields');
		die(); //don't remove
	}		
	
	public function leadCaptureLogin() {
		iHomefinderLogger::getInstance()->debug('Begin leadCaptureLogin');
		$requestData = 'method=handleRequest&viewType=json&requestType=lead-capture-login';
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "phpStyle", "true");
		$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);	
		if($_REQUEST['leadCaptureId'] == null) {
			$leadCaptureId=iHomefinderStateManager::getInstance()->getLeadCaptureId();
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "leadCaptureId", $leadCaptureId);
		}
		$contentInfo = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$content = iHomefinderRequestor::getInstance()->getContent($contentInfo);
		if(property_exists($contentInfo, "head")) {
			$content .= $contentInfo->head;
		}
		echo $content;
		iHomefinderLogger::getInstance()->debug('End leadCaptureLogin');
		die(); //don't remove
	}
	
	public function addSavedListingComments() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderAjaxHandler.addSavedListingComments');
		$requestData = 'method=handleRequest&viewType=json&requestType=saved-listing-comments';
		$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);	
		$contentInfo = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		$content = iHomefinderRequestor::getInstance()->getContent($contentInfo);
		if(property_exists($contentInfo, "head")) {
			$content .= $contentInfo->head;
		}
		//We do not need to get any content back
		//echo $content;
		iHomefinderLogger::getInstance()->debug('End addSavedListingComments');
		die(); //don't remove
	}
	
	public function addSavedListingRating() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderAjaxHandler.addSavedListingRating');
		$requestData = 'method=handleRequest&viewType=json&requestType=saved-listing-rating';
		$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);	
		$contentInfo = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		iHomefinderLogger::getInstance()->debugDumpVar($contentInfo);
		$content = iHomefinderRequestor::getInstance()->getContent($contentInfo);
		if(property_exists($contentInfo, "head")) {
			$content .= $contentInfo->head;
		}
		//We do not need to get any content back
		//echo $content;
		iHomefinderLogger::getInstance()->debug('End addSavedListingRating');
		die(); //don't remove
	}

	public function saveListingForSubscriberInSession() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderAjaxHandler.saveListingForSubscriberInsession');
		$requestData = 'method=handleRequest&viewType=json&requestType=save-listing-subscriber-session';
		$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);
		$contentInfo = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		iHomefinderLogger::getInstance()->debugDumpVar($contentInfo);
		$content = iHomefinderRequestor::getInstance()->getContent($contentInfo);
		if(property_exists($contentInfo, "head")) {
			$content .= $contentInfo->head;
		}
		echo $content;
		die(); //don't remove
	}
	
	public function saveSearchForSubscriberInSession() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderAjaxHandler.saveSearchForSubscriberInSession');
		$requestData = 'method=handleRequest&viewType=json&requestType=save-search-subscriber-session';
		$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);
		$contentInfo = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		iHomefinderLogger::getInstance()->debugDumpVar($contentInfo);
		$content = iHomefinderRequestor::getInstance()->getContent($contentInfo);
		if(property_exists($contentInfo, "head")) {
			$content .= $contentInfo->head;
		}
		echo $content;
		die(); //don't remove
	}
	
	public function getAutocompleteMatches() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderAjaxHandler.getAutocompleteMatches');
		$requestData = 'method=handleRequest&viewType=json&requestType=area-autocomplete';
		$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);
		$contentInfo = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
		iHomefinderLogger::getInstance()->debugDumpVar($contentInfo);
		$json = iHomefinderRequestor::getInstance()->getJson($contentInfo);
		echo $json;
		die(); //don't remove	
	}
	
	public function sendPassword() {
		iHomefinderLogger::getInstance()->debug('Begin iHomefinderAjaxHandler.sendPassword');
		$isSpam=$this->isSpam();
		if($isSpam == false) {
			$requestData = 'method=handleRequest&viewType=json&requestType=send-password';
			$requestData = iHomefinderRequestor::getInstance()->addVarsToUrl($requestData, $_REQUEST);
			$contentInfo = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
			$content = iHomefinderRequestor::getInstance()->getContent($contentInfo);
			if(property_exists($contentInfo, "head")) {
				$content .= $contentInfo->head;
			}
			iHomefinderLogger::getInstance()->debug($requestData);
			iHomefinderLogger::getInstance()->debug('End iHomefinderAjaxHandler.sendPassword');
			echo $content;
		}
		die(); //don't remove
	}
	
}