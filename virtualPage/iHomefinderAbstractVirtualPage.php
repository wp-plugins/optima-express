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
		return $this->remoteResponse->head;
	}
	
	public function getTitle() {
		return null;
	}
	
	public function getContent() {
		return null;
	}
	
	public function getMetaTags() {
		return $this->remoteResponse->metatags;
	}
	
}