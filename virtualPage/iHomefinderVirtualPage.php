<?php
if( !interface_exists('IHomefinderVirtualPage')) {
	interface IHomefinderVirtualPage {
		function getContent( $authenticationToken ) ;
		function getTitle();
		function getPageTemplate();
		function getPath();
	}
}//end if( !class_exists('IHomefinderFilter')) 
?>