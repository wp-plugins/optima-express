<?php

class iHomefinderLayoutManager {

	private static $instance;
	
	private function __construct() {
		
	}
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function getLayoutType() {
		$result = get_option(iHomefinderConstants::OPTION_LAYOUT_TYPE, null);
		return $result;
	}
	
	public function getColorScheme() {
		$result = get_option(iHomefinderConstants::COLOR_SCHEME_OPTION, null);
		return $result;
	}
	
	public function getExternalUrl() {
		$result = null;
		if($this->isResponsive()) {
			$result = iHomefinderConstants::RESPONSIVE_EXTERNAL_URL;
		} else {
			$result = iHomefinderConstants::LEGACY_EXTERNAL_URL;
		}
		return $result;
	}
	
	public function isResponsive() {
		$result = false;
		if($this->getLayoutType() == iHomefinderConstants::OPTION_LAYOUT_TYPE_RESPONSIVE) {
			$result = true;
		}
		return $result;
	}
	
	public function supportsMultipleQuickSearchLayouts() {
		$result = false;
		if($this->isResponsive()) {
			$result = true;
		}
		return $result;
	}
	
	public function supportsQuickSearchPropertyType() {
		$result = false;
		if($this->isResponsive()) {
			$result = true;
		}
		return $result;
	}
	
	public function supportsFeaturedPropertyType() {
		$result = false;
		if($this->isResponsive()) {
			$result = true;
		}
		return $result;
	}
	
	public function supportsMapSearchCenterLatLong() {
		$result = false;
		if($this->isResponsive()) {
			$result = false;
		} else {
			$result = true;
		}
		return $result;
	}
	
	public function supportsMapSearchCenterAddress() {
		$result = false;
		if($this->isResponsive()) {
			$result = true;
		}
		return $result;
	}
	
	public function supportsMapSearchResponsiveness() {
		$result = false;
		if($this->isResponsive()) {
			$result = true;
		}
		return $result;
	}
	
	public function supportsColorScheme() {
		$result = false;
		if($this->isResponsive()) {
			$result = true;
		}
		return $result;
	}
	
	public function supportsListingGallery() {
		$result = true;
		return $result;
	}
	
	public function supportsListingGalleryResponsiveness() {
		$result = false;
		if($this->isResponsive()) {
			$result = true;
		}
		return $result;
	}
	
	public function supportsResultsDisplayType() {
		$result = false;
		if($this->isResponsive()) {
			$result = true;
		}
		return $result;
	}
	
	public function supportsResultsResultsPerPage() {
		$result = false;
		if($this->isResponsive()) {
			$result = true;
		}
		return $result;
	}
	
	/**
	 * Legacy widgets were surrounded by <br> tags.
	 * We want to remove these for the new responsive
	 * version for layout reasons.
	 * @return boolean
	 */
	public function hasExtraLineBreaksInWidget() {
		$result = true;
		if($this->isResponsive()) {
			$result = false;
		}
		return $result;
	}
	
	public function hasItemInSearchFormData() {
		$result = false;
		if($this->isResponsive()) {
			$result = true;
		}
		return $result;
	}
	
	/**
	 * New responsive map search can take all of the space (width=100%
	 * or if user passes width (for short code) can use that width)
	 * For traditional map search width=595 is passed to map search virtual page
	 */
	public function supportsMapSearchWithMultipleWidths() {
		$result = false;
		if($this->isResponsive()) {
			$result = true;
		}
		return $result;
	}
	
	/**
	 * The QuickSearch short code is handled differently for responsive
	 * 
	 * The legacy version uses the QuickSearchVirtualPage
	 */
	public function supportsQuickSearchVirtualPage() {
		$result = true;
		if($this->isResponsive()) {
			$result = false;
		}
		return $result;			
	}
	
	public function supportsSeoVariables() {
		return $this->isResponsive();
	}
	
}
