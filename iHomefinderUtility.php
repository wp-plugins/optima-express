<?php
if( !class_exists('IHomefinderUtility')) {
	/**
	 *
	 *
	 * @author ihomefinder
	 */
	class IHomefinderUtility {

		private static $instance ;

		private function __construct(){
		}

		public static function getInstance(){
			if( !isset(self::$instance)){
				self::$instance = new IHomefinderUtility();
			}
			return self::$instance;
		}

		public function getQueryVar($name){
			global $wp;
			$result = $this->getVarFromArray( $name, $wp->query_vars ) ;
			return $result ;
		}

		public function getRequestVar($name){
			$result = $this->getVarFromArray( $name, $_REQUEST ) ;
			return $result ;
		}

		public function getVarFromArray($name, $arrayVar){
			$result=null ;
			if( array_key_exists($name, $arrayVar)){
				$result = $arrayVar[$name];
			}
			return $result ;
		}
		
		/**
		 * When navigating listing detail pages, we need to set the next and previous
		 * details and pass in the request, to properly create next and previous links
		 * 
		 * @param string $ihfUrl
		 * @param int $boardId
		 * @param string $listingNumber
		 */
		public function setPreviousAndNextInformation( $ihfUrl, $boardId, $listingNumber ){
			$searchSummaryArray = IHomefinderStateManager::getInstance()->getSearchSummary() ;
			$key= $boardId . "|" . $listingNumber ;
			if( isset( $searchSummaryArray )){
				$searchSummaryObject = $searchSummaryArray[ $key ];				
				if( isset( $searchSummaryObject )){
					if( isset($searchSummaryObject->previousId)){
						$searchSummaryPrevious = $searchSummaryArray[ $searchSummaryObject->previousId ];	
						$ihfUrl .= "&prevBoardId=" . $searchSummaryPrevious->boardId ;					
						$ihfUrl .= "&prevListingNumber=" . $searchSummaryPrevious->listingNumber ;
						$ihfUrl .= "&prevAddress=" . urlencode($searchSummaryPrevious->address) ;
					}
					
					if( isset($searchSummaryObject->nextId)){
						$searchSummaryNext = $searchSummaryArray[ $searchSummaryObject->nextId ];
						$ihfUrl .= "&nextBoardId=" . $searchSummaryNext->boardId ;					
						$ihfUrl .= "&nextListingNumber=" . $searchSummaryNext->listingNumber ;
						$ihfUrl .= "&nextAddress=" . urlencode($searchSummaryNext->address) ;						
					}
				}	
			}
			
			return $ihfUrl ;
		}		
	}
}
?>