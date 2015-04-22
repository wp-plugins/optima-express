<?php

/**
 *
 * This class has methods to support creating forms to set templates, custom url patterns and
 * titles for iHomefinder Virtual Pages.
 *
 * @author ihomefinder
 */
class iHomefinderAdminEmailDisplay {

	//Only possible values for
	const EMAIL_DISPLAY_TYPE_DEFAULT_VALUE="ihf-email-display-type-default";
	const EMAIL_DISPLAY_TYPE_CUSTOM_IMAGES_VALUE="ihf-email-display-type-custom-images";
	const EMAIL_DISPLAY_TYPE_CUSTOM_HTML_VALUE="ihf-email-display-type-custom-hi";

	private static $instance;

	private function __construct() {
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new iHomefinderAdminEmailDisplay();
		}
		return self::$instance;
	}

	public function includeDefaultDisplay() {
		$result=false;
		$result=$this->getDefaultLogo();
		return $result;
	}

	public function getDefaultLogo() {
		$defaultLogo=false;
		if(function_exists('get_option_tree')) {
			$defaultLogo=get_option_tree('office_logo');
		}
		return $defaultLogo;
	}

	private function basicEmailHeader($agentPhoto, $logo, $name, $company, $address1, $address2, $phone) {
		
		$emailHeader="<table width='650' border='0' cellpadding='2' cellspacing='0' bgcolor='#9b9b9b'><tr><td>";
		$emailHeader.="<table width='100%' border='0' cellpadding='0' cellspacing='0' bgcolor='#ffffff'><tr>";

		$emailHeader .="<td>";
		if($agentPhoto) {
			$agentPhotoSize=getimagesize($agentPhoto);
			$agentPhotoHeight=$agentPhotoSize[1];
			if($agentPhotoHeight > 142) {
				$emailHeader .= "<img src='" . $agentPhoto . "' height='142px'/>";	
			}
			else{
				$emailHeader .= "<img src='" . $agentPhoto . "'/>";	
			}
			
		}
		$emailHeader .=	"</td>";
		$emailHeader .="<td>";
		$emailHeader .="<font  face='Arial, Helvetica, sans-serif'>";
		if($name) {
			$emailHeader .= "<b>" . $name . "</b><br/>";
		}			
		if($company) {
			$emailHeader .= "<b>" . $company . "</b><br/><br/>";
		}			
		if($address1) {
			$emailHeader .= $address1 . "<br/>";
		}
		if($address2) {
			$emailHeader .= $address2 . "<br/>";
		}
		if($phone) {
			$emailHeader .= $phone . "<br/>";
		}
		
		$emailHeader .="</font>";
		$emailHeader .="</td>";	
		$emailHeader .="<td align='right'>";
		if($logo) {
			$logoSize=getimagesize($logo);
			$logoHeight=$logoSize[1];
			if($logoHeight > 142) {
				$emailHeader .= "<img src='" . $logo . "' height='142px'/>";
			}
			else{
				$emailHeader .= "<img src='" . $logo . "'/>";
			}
		}
		$emailHeader .=	"</td>";					
		$emailHeader .="</tr></table>";
		$emailHeader .="</td></tr>";
		$emailHeader .="<tr><td>";
		$emailHeader .="<table width='100%' bgcolor='#ffffff'><tr><td>";
		return $emailHeader;
	}

	
	private function basicEmailFooter($agentPhoto, $logo, $name, $company, $address1, $address2, $phone) {
		$emailFooter="</td></tr></table>";
		$emailFooter .="</td></tr><tr><td>";
		$emailFooter .="<table width='100%' cellpadding='10' cellspacing='0' border='0' bgcolor='#dedede'><tr>";
		$emailFooter .="<td align='right'>";
		$emailFooter .="<font  face='Arial, Helvetica, sans-serif'>";
		if($name) {
			$emailFooter .= "<b>" . $name . "</b><br/>";
		}			
		if($company) {
			$emailFooter .= "<b>" . $company . "</b><br/><br/>";
		}			
		if($address1) {
			$emailFooter .= $address1 . "<br/>";
		}
		if($address2) {
			$emailFooter .= $address2 . "<br/>";
		}
		if($phone) {
			$emailFooter .= $phone . "<br/>";
		}
		
		$emailFooter .="</font>";
		$emailFooter .="</td>";				
		$emailFooter .="</tr></table>";
		$emailFooter .="</td></tr></table>";
		return $emailFooter;

	}

	public function setHeaderAndFooter() {
		$emailHeader=$this->buildHeader();
		$emailFooter=$this->buildFooter();
		update_option(iHomefinderConstants::EMAIL_HEADER_OPTION, $emailHeader);
		update_option(iHomefinderConstants::EMAIL_FOOTER_OPTION, $emailFooter);
	}
	
	public function getHeader() {
		$emailHeader=get_option(iHomefinderConstants::EMAIL_HEADER_OPTION);
		return $emailHeader;
	}
	
	public function getFooter() {
		$emailFooter=get_option(iHomefinderConstants::EMAIL_FOOTER_OPTION);
		return $emailFooter;
	}

	public function buildHeader() {
		$header="";
		$displayTypeValue=get_option(iHomefinderConstants::EMAIL_DISPLAY_TYPE_OPTION);
		if(!$displayTypeValue) {
			$displayTypeValue=iHomefinderAdminEmailDisplay::EMAIL_DISPLAY_TYPE_DEFAULT_VALUE;
		}

		switch ($displayTypeValue) {
			case iHomefinderAdminEmailDisplay::EMAIL_DISPLAY_TYPE_CUSTOM_IMAGES_VALUE:
				$agentPhoto=get_option(iHomefinderConstants::EMAIL_PHOTO_OPTION);
				$logo=get_option(iHomefinderConstants::EMAIL_LOGO_OPTION);
				$name=get_option(iHomefinderConstants::EMAIL_NAME_OPTION);
				$company=get_option(iHomefinderConstants::EMAIL_COMPANY_OPTION);
				$address1=get_option(iHomefinderConstants::EMAIL_ADDRESS_LINE1_OPTION);
				$address2=get_option(iHomefinderConstants::EMAIL_ADDRESS_LINE2_OPTION);
				$phone=get_option(iHomefinderConstants::EMAIL_PHONE_OPTION);
				$header=$this->basicEmailHeader($agentPhoto, $logo, $name, $company, $address1, $address2, $phone);
				break;
			case iHomefinderAdminEmailDisplay::EMAIL_DISPLAY_TYPE_CUSTOM_HTML_VALUE:
				$header=get_option(iHomefinderConstants::EMAIL_HEADER_OPTION);
				break;
			case iHomefinderAdminEmailDisplay::EMAIL_DISPLAY_TYPE_DEFAULT_VALUE:
				//Use the agent photo and office logo that were previoulsy uploaded
				$agentPhoto=get_option(iHomefinderConstants::AGENT_PHOTO_OPTION);
				$logo=$this->getDefaultLogo();
				$header=$this->basicEmailHeader($agentPhoto, $logo);
				break;
			default:
				$header="";
				break;
		}

		return $header;
	}

	public function buildFooter() {
		$footer="";
		$displayTypeValue=get_option(iHomefinderConstants::EMAIL_DISPLAY_TYPE_OPTION);
		if(!$displayTypeValue) {
			$displayTypeValue=iHomefinderAdminEmailDisplay::EMAIL_DISPLAY_TYPE_DEFAULT_VALUE;
		}

		switch ($displayTypeValue) {
			case iHomefinderAdminEmailDisplay::EMAIL_DISPLAY_TYPE_CUSTOM_IMAGES_VALUE:
				$agentPhoto=get_option(iHomefinderConstants::EMAIL_PHOTO_OPTION);
				$logo=get_option(iHomefinderConstants::EMAIL_LOGO_OPTION);
				$name=get_option(iHomefinderConstants::EMAIL_NAME_OPTION);
				$company=get_option(iHomefinderConstants::EMAIL_COMPANY_OPTION);
				$address1=get_option(iHomefinderConstants::EMAIL_ADDRESS_LINE1_OPTION);
				$address2=get_option(iHomefinderConstants::EMAIL_ADDRESS_LINE2_OPTION);
				$phone=get_option(iHomefinderConstants::EMAIL_PHONE_OPTION);					
				$footer=$this->basicEmailFooter($agentPhoto, $logo, $name, $company, $address1, $address2, $phone);
				break;
			case iHomefinderAdminEmailDisplay::EMAIL_DISPLAY_TYPE_CUSTOM_HTML_VALUE:
				$footer=get_option(iHomefinderConstants::EMAIL_FOOTER_OPTION);
				break;
			case iHomefinderAdminEmailDisplay::EMAIL_DISPLAY_TYPE_DEFAULT_VALUE:
				//Use the agent photo and office logo that were previoulsy uploaded
				$agentPhoto=get_option(iHomefinderConstants::AGENT_PHOTO_OPTION);
				$logo=$this->getDefaultLogo();
				$footer=$this->basicEmailFooter($agentPhoto, $logo);
				break;
			default:
				$footer="";
				break;
		}
		return $footer;
	}
	
}