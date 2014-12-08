<?php
if( !class_exists('IHomefinderListingInfo')) {
	/**
	 * IHomefinderListingInfo Class
	 */
	class IHomefinderListingInfo  {
		
		private $listingNumber=null;
		private $boardId=null;
		private $address=null;
		private $clientPropertyId=null;
		private $sold="false";
		
		/** constructor */
	    public function IHomefinderListingInfo($listingNumber, $boardId, $address, $clientPropertyId, $sold  ) {
	    	$this->listingNumber=$listingNumber;
	    	$this->boardId=$boardId;
	    	$this->address=$address;
	    	$this->clientPropertyId=$clientPropertyId;
	    	$this->sold=$sold;
	    }
	    
	    public function getListingNumber(){
	    	return $this->listingNumber ;
	    } 
	    
	    public function getAddress(){
	    	return $this->address ;
	    } 

	    public function getBoardId(){
	    	return $this->boardId ;
	    } 
	    
	    public function getClientPropertyId(){
	    	return $this->clientPropertyId ;
	    } 
	    
	    public function getSold(){
	    	return $this->sold ;
	    } 
	}
	    
}//end if( !class_exists('iHomefinderAgentBioWidget'))

?>
