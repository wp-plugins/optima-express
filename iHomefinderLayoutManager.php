<?php
if( !class_exists('IHomefinderLayoutManager')) {
	/**
	 * This class is handle rules related to 
	 * different virtual page layouts
	 *
	 * @author ihomefinder
	 */
	class IHomefinderLayoutManager {

		private static $instance ;
		private $externalUrl ;
		private $layout ;
		
		private function __construct(){
			$this->layout=get_option(IHomefinderConstants::OPTION_LAYOUT_TYPE);		
		}
		
		public function isResponsive(){
			$result=false;
			if( $this->layout == IHomefinderConstants::OPTION_LAYOUT_TYPE_RESPONSIVE){
				$result=true;
			}
			return $result;
		}
		
		public function getLayoutType(){
			return $this->layout;
		}

		public static function getInstance(){
			if( !isset(self::$instance)){
				self::$instance = new IHomefinderLayoutManager();
			}
			return self::$instance;
		}
		
		public function getExternalUrl(){
			if( $this->externalUrl == null ){
				if( $this->isResponsive()){
					$this->externalUrl = IHomefinderConstants::RESPONSIVE_EXTERNAL_URL ;
				}
				else{
					$this->externalUrl = IHomefinderConstants::LEGACY_EXTERNAL_URL ;
				}	
			}
			return $this->externalUrl ;
		}
		
		public function supportsMultipleQuickSearchLayouts(){
			$result=false;
			if( $this->isResponsive()){
				$result=true;
			}
			return $result;
		}
		
		public function supportsMapSearchCenterLatLong(){
			$result=false;
			if( $this->isResponsive()){
				$result=false;
			} else {
				$result=true;
			}
			return $result;
		}
		
		public function supportsMapSearchCenterAddress(){
			$result=false;
			if( $this->isResponsive()){
				$result=true;
			}
			return $result;
		}
		
		public function supportsMapSearchResponsiveness(){
			$result=false;
			if( $this->isResponsive()){
				$result=true;
			}
			return $result;
		}
		
		public function supportsColorScheme(){
			$result=false;
			if( $this->isResponsive()){
				$result=true;
			}
			return $result;
		}
		
		public function supportsListingGallery(){
			$result=true;
			if( $this->isResponsive()){
				$result=false;
			}
			return $result;
		}
		
		/**
		 * 
		 * Legacy widgets were surrounded by <br> tags.
		 * We want to remove these for the new responsive
		 * version for layout reasons.
		 * @return boolean
		 */
		public function hasExtraLineBreaksInWidget(){
			$result=true;
			if( $this->isResponsive()){
				$result=false;
			}
			return $result;
		}
		
		public function hasItemInSearchFormData(){
			$result=false;
			if( $this->isResponsive()){
				$result=true;
			}
			return $result;
		}
		/**
		 * New responsive map search can take all of the space (width=100%
		 * or if user passes width (for short code) can use that width)
		 * For traditional map search width=595 is passed to map search virtual page
		 */

		public function supportsMapSearchWithMultipleWidths(){
			$result=false;
			if( $this->isResponsive()){
				$result=true;
			}
			return $result;
		}
		
		/**
		 * The QuickSearch short code is handled differently for responsive
		 * 
		 * The legacy version uses the QuickSearchVirtualPage
		 */
		public function supportsQuickSearchVirtualPage(){
			$result=true;
			if( $this->isResponsive()){
				$result=false;
			}
			return $result;			
		}
	}
}
?>
