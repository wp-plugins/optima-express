<?php

abstract class iHomefinderAbstractVirtualPage implements iHomefinderVirtualPageInterface {
	
	protected $remoteResponse;
	protected $remoteRequest;
	
	public function __construct() {
		$this->remoteRequest = new iHomefinderRequestor();
	}
	
	public function getPageTemplate() {
		return null;
	}
	
	public function getPath() {
		return null;
	}
	
	public function getHead() {
		$result = null;
		if(is_object($this->remoteResponse) && property_exists($this->remoteResponse, "head")) {
			$result = $this->remoteResponse->head;
		}
		return $result;
	}
	
	public function getTitle() {
		return null;
	}
	
	public function getContent() {
		return null;
	}
	
	public function getMetaTags() {
		$result = null;
		if(is_object($this->remoteResponse) && property_exists($this->remoteResponse, "metatags")) {
			$result = $this->remoteResponse->metatags;
		}
		return $result;
	}
	
}