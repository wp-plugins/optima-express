<?php

interface iHomefinderVirtualPageInterface {
	
	/**
	 * @return string
	 */
	function getPageTemplate();
	
	/**
	 * @return string
	 */
	function getPermalink();
	
	/**
	 * @return string
	 */
	function getHead();
	
	/**
	 * @return string
	 */
	function getTitle();
	
	/**
	 * @return string
	 */
	function getContent();
	
	/**
	 * @return string
	 */
	function getBody();
	
	/**
	 * @return string
	 */
	function getMetaTags();
	
	/**
	 * @return array<iHomefinderVariable>
	 */
	function getVariables();
	
	/**
	 * @return array<iHomefinderVariable>
	 */
	function getAvailableVariables();
	
}