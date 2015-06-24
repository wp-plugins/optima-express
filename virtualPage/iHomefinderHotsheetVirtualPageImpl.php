<?php

class iHomefinderHotsheetVirtualPageImpl extends iHomefinderAbstractVirtualPage {
	
	public function getTitle() {
		$default = null;
		if(iHomefinderLayoutManager::getInstance()->supportsSeoVariables()) {
			$default = "{savedSearchName}";
		} elseif(is_object($this->remoteResponse) && $this->remoteResponse->hasTitle()) {
			$default = $this->remoteResponse->getTitle();
		}
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_HOTSHEET, $default);
	}

	public function getPageTemplate() {
		return get_option(iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_HOTSHEET, null);	
	}
	
	public function getPermalink() {
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_HOTSHEET, "homes-for-sale-toppicks");
	}
	
	public function getMetaTags() {
		$default = "<meta name=\"description\" content=\"{savedSearchDescription}\" />";
		return $this->getText(iHomefinderConstants::OPTION_VIRTUAL_PAGE_META_TAGS_HOTSHEET, $default);
	}
	
	public function getAvailableVariables() {
		$variableUtility = iHomefinderVariableUtility::getInstance();
		return array(
			$variableUtility->getSavedSearchName(),
			$variableUtility->getSavedSearchDescription()
		);
	}
	
	public function getContent() {
		iHomefinderStateManager::getInstance()->saveLastSearch();
		$this->remoteRequest
			->addParameters($_REQUEST)
			->addParameter("method", "handleRequest")
			->addParameter("viewType", "json")
			->addParameter("requestType", "hotsheet-results")
			->addParameter("includeSearchSummary", true)
		;
		$hotSheetId = iHomefinderUtility::getInstance()->getQueryVar("savedSearchId");
		if(!empty($hotSheetId)) {
			$this->remoteRequest->addParameter("hotSheetId", $hotSheetId);
		}
		$title = $this->getTitle();
		if(empty($title)) {
			$this->remoteRequest->addParameter("includeDisplayName", false);
		}
		$this->remoteResponse = $this->remoteRequest->remoteGetRequest();
	}
	
}