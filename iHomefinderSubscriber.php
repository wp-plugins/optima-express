<?php
if( !class_exists('IHomefinderSubscriber')) {
	
	class IHomefinderSubscriber {
	
		private $id ;
		private $name ;
		private $email  ;
		private $separator = "|@|"; 
		
		private function __construct(){
		}
		
		public static function getInstance($subscriberId, $subscriberName, $subscriberEmail ){
			$subscriber = new IHomefinderSubscriber();
			$subscriber->id=$subscriberId ;
			$subscriber->name=$subscriberName ;
			$subscriber->email=$subscriberEmail ;
			return $subscriber ;
		}
		
		public static function getDeserialized($serializedValue ){
			$subscriber = new IHomefinderSubscriber();
			$subscriber=$subscriber->deserialize($serializedValue);
			return $subscriber ;
		}		
		
		public function getId(){
			return $this->id ;
		}

		public function getEmail(){
			return $this->email ;
		}
	
		public function getName(){
			return $this->name ;
		}
		
		public function serializedValue(){
			$serializedValue = 
				$this->id . $this->separator . 
				$this->name . $this->separator . 
				$this->email; 
				
			return $serializedValue ;
		}

		public function deserialize( $serializedValue ){
			$subscriberParts = explode($this->separator, $serializedValue) ;
			$this->id = $subscriberParts[0];
			$this->name = $subscriberParts[1];
			$this->email = $subscriberParts[2];
			return $this ;
		}
		
		
	}//end class
}// end if class_exists

?>