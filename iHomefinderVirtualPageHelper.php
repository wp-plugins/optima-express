<?php
if( !class_exists('IHomefinderVirtualPageHelper')) {
	/**
	 * @author ihomefinder
	 */
	class IHomefinderVirtualPageHelper {

		//Group for virtual page related options
		const OPTION_VIRTUAL_PAGE_CONFIG="ihf-virtual-page-config";
		
		//Default template for Virtual Pages that do not have a template.
		const OPTION_VIRTUAL_PAGE_TEMPLATE_DEFAULT="ihf-virtual-page-template-default";
		
		//Listing DetailVirtualPage related options
		const OPTION_VIRTUAL_PAGE_TITLE_DETAIL="ihf-virtual-page-title-detail";
		const OPTION_VIRTUAL_PAGE_TEMPLATE_DETAIL="ihf-virtual-page-template-detail";
		const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_DETAIL="ihf-virtual-page-permalink-text-detail";	

		//Listing Search VirtualPage Options
		const OPTION_VIRTUAL_PAGE_TITLE_SEARCH="ihf-virtual-page-title-search";
		const OPTION_VIRTUAL_PAGE_TEMPLATE_SEARCH="ihf-virtual-page-template-search";
		const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SEARCH="ihf-virtual-page-permalink-text-search";	
		
		//Advanced Listing Search VirtualPage Options
		const OPTION_VIRTUAL_PAGE_TITLE_ADV_SEARCH="ihf-virtual-page-title-adv-search";
		const OPTION_VIRTUAL_PAGE_TEMPLATE_ADV_SEARCH="ihf-virtual-page-template-adv-search";
		const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_ADV_SEARCH="ihf-virtual-page-permalink-text-adv-search";	
				
		//Organizer Login VirtualPage Options
		const OPTION_VIRTUAL_PAGE_TITLE_ORG_LOGIN="ihf-virtual-page-title-org-login";
		const OPTION_VIRTUAL_PAGE_TEMPLATE_ORG_LOGIN="ihf-virtual-page-template-org-login";
		const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_ORG_LOGIN="ihf-virtual-page-permalink-text-org-login";	

		//Email Updated VirtualPage Options
		const OPTION_VIRTUAL_PAGE_TITLE_EMAIL_UPDATES="ihf-virtual-page-title-email-updates";
		const OPTION_VIRTUAL_PAGE_TEMPLATE_EMAIL_UPDATES="ihf-virtual-page-template-email-updates";
		const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_EMAIL_UPDATES="ihf-virtual-page-permalink-text-email-updates";	

		//Featured VirtualPage Options
		const OPTION_VIRTUAL_PAGE_TITLE_FEATURED="ihf-virtual-page-title-featured";
		const OPTION_VIRTUAL_PAGE_TEMPLATE_FEATURED="ihf-virtual-page-template-featured";
		const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_FEATURED="ihf-virtual-page-permalink-text-featured";	

		//Hotsheet VirtualPage Options
		const OPTION_VIRTUAL_PAGE_TITLE_HOTSHEET="ihf-virtual-page-title-hotsheet";
		const OPTION_VIRTUAL_PAGE_TEMPLATE_HOTSHEET="ihf-virtual-page-template-hotsheet";
		const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_HOTSHEET="ihf-virtual-page-permalink-text-hotsheet";	


		private static $instance ;

		private function __construct(){
		}

		public static function getInstance(){
			if( !isset(self::$instance)){
				self::$instance = new IHomefinderVirtualPageHelper();
			}
			return self::$instance;
		}
		
		public function registerOptions(){
			//Virtual Page settings
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_DEFAULT );
			
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_DETAIL );
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_DETAIL );
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_DETAIL );
			
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_SEARCH );
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_SEARCH );
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SEARCH );
			
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_ADV_SEARCH );
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_ADV_SEARCH );
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_ADV_SEARCH );
			
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_ORG_LOGIN );
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_ORG_LOGIN );
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_ORG_LOGIN );
			
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_EMAIL_UPDATES );
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_EMAIL_UPDATES );
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_EMAIL_UPDATES );
			
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_FEATURED);
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_FEATURED );
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_FEATURED );
			
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_HOTSHEET);
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_HOTSHEET );
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_HOTSHEET );
						
		}
		
		public function getDefaultTemplate(){
			$defaultTemplate= get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_DEFAULT);
			return $defaultTemplate ;
		}
		

	}//end class
}
?>