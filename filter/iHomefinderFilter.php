<?php
if( !interface_exists('IHomefinderFilter')) {
	interface IHomefinderFilter {
		function filter( $content, $authenticationToken ) ;
		function getTitle();
	}
}//end if( !class_exists('IHomefinderFilter')) 
?>