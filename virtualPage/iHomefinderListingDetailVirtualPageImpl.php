<?php

class iHomefinderListingDetailVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function getTitle() {
		$default = null;
		if(iHomefinderLayoutManager::getInstance()->supportsSeoVariables()) {
			$default = "{listingAddress}";
		} elseif(is_object($this->remoteResponse) && $this->remoteResponse->hasTitle()) {
			$default = $this->remoteResponse->getTitle();
		}
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_DETAIL, $default);
	}
	
	public function getPermalink() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_DETAIL, "homes-for-sale-details");
	}
	
	public function getPageTemplate() {
		return get_option(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_DETAIL, null);
	}
	
	public function getMetaTags() {
		$default = "<meta property=\"og:image\" content=\"{listingPhotoUrl}\" />\n<meta name=\"description\" content=\"Photos and Property Details for {listingAddress}. Get complete property information, maps, street view, schools, walk score and more. Request additional information, schedule a showing, save to your property organizer.\" />\n<meta name=\"keywords\" content=\"{listingAddress}, {listingCity} Real Estate,  {listingCity} Property for Sale\" />";
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_META_TAGS_DETAIL, $default);
	}
	
	public function getAvailableVariables() {
		$variableUtility = iHomefinderVariableUtility::getInstance();
		return array(
			$variableUtility->getListingAddress(),
			$variableUtility->getListingCity(),
			$variableUtility->getListingPostalCode(),
			$variableUtility->getListingPhotoUrl(),
			$variableUtility->getListingPrice(),
			$variableUtility->getListingSquareFeet(),
			$variableUtility->getListingBedrooms(),
			$variableUtility->getListingBathrooms(),
			$variableUtility->getListingNumber(),
			$variableUtility->getListingDescription()
		);
	}
	
	public function getContent() {
		$listingNumber = iHomefinderUtility::getInstance()->getQueryVar("listingNumber");
		$boardId = iHomefinderUtility::getInstance()->getQueryVar("boardId");
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("ln", $listingNumber)
			->addParameter("bid", $boardId)
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "listing-detail")
		;
		$previousAndNextInformation = $this->getPreviousAndNextInformation($boardId, $listingNumber);
		$this->remoteRequest->addParameters($previousAndNextInformation);
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}
	
	/**
	 * same code in sold detail
	 */
	public function getBody() {
		$body = $this->remoteResponse->getBody();
		$previousSearchLink = $this->getPreviousSearchLink();
		if(strpos($body, "<!-- INSERT RETURN TO RESULTS LINK HERE -->") !== false) {
			$body = str_replace("<!-- INSERT RETURN TO RESULTS LINK HERE -->", $previousSearchLink, $body);
		} else {
			$body = $previousSearchLink . "<br /><br />" . $body;
		}
		return $body;
	}
	
}