<?php
if( !class_exists('IHomefinderVirtualPageHelper')) {
	/**
	 * @author ihomefinder
	 * 
	 * This class defines option names related to iHomefinder Virtual Pages. 
	 * 
	 * For most virtual pages, we store the following options:
	 * - title - page title used by the theme
	 * - template - used by the theme to display the virual page.
	 * - permalink - used in the rewrite rules.
	 * 
	 * This class defines and registers all the options required for
	 * the Virtual Pages.
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
		
		//Map Search VirtualPage Options
		const OPTION_VIRTUAL_PAGE_TITLE_MAP_SEARCH="ihf-virtual-page-title-map-search";
		const OPTION_VIRTUAL_PAGE_TEMPLATE_MAP_SEARCH="ihf-virtual-page-template-map-search";
		const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_MAP_SEARCH="ihf-virtual-page-permalink-text-map-search";			
		
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

		//Contact Form Virtual Page Options
		const OPTION_VIRTUAL_PAGE_TITLE_CONTACT_FORM="ihf-virtual-page-title-contact-form";
		const OPTION_VIRTUAL_PAGE_TEMPLATE_CONTACT_FORM="ihf-virtual-page-template-contact-form";
		const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_CONTACT_FORM="ihf-virtual-page-permalink-text-contact-form";

		//Valuation Form Virtual Page Options
		const OPTION_VIRTUAL_PAGE_TITLE_VALUATION_FORM="ihf-virtual-page-title-valuation-form";
		const OPTION_VIRTUAL_PAGE_TEMPLATE_VALUATION_FORM="ihf-virtual-page-template-valuation-form";
		const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_VALUATION_FORM="ihf-virtual-page-permalink-text-valuation-form";

		//Open Home Search Form Virtual Page Options
		const OPTION_VIRTUAL_PAGE_TITLE_OPEN_HOME_SEARCH_FORM="ihf-virtual-page-title-open-home-search-form";
		const OPTION_VIRTUAL_PAGE_TEMPLATE_OPEN_HOME_SEARCH_FORM="ihf-virtual-page-template-open-home-search-form";
		const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_OPEN_HOME_SEARCH_FORM="ihf-virtual-page-open-home-search-form";
		
		//Featured Sold Listings Virtual Page Options
		const OPTION_VIRTUAL_PAGE_TITLE_SOLD_FEATURED="ihf-virtual-page-title-sold-featured";
		const OPTION_VIRTUAL_PAGE_TEMPLATE_SOLD_FEATURED="ihf-virtual-page-template-sold-featured";
		const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SOLD_FEATURED="ihf-virtual-page-permalink-text-sold-featured";
		
		//Supplemental listings
		const OPTION_VIRTUAL_PAGE_TITLE_SUPPLEMENTAL_LISTING="ihf-virtual-page-title-supplemental-listing";
		const OPTION_VIRTUAL_PAGE_TEMPLATE_SUPPLEMENTAL_LISTING="ihf-virtual-page-template-supplemental-listing";
		const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SUPPLEMENTAL_LISTING="ihf-virtual-page-permalink-text-supplemental-listing";

		//Listing SoldDetailVirtualPage related options
		const OPTION_VIRTUAL_PAGE_TITLE_SOLD_DETAIL="ihf-virtual-page-title-sold-detail";
		const OPTION_VIRTUAL_PAGE_TEMPLATE_SOLD_DETAIL="ihf-virtual-page-template-sold-detail";
		const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SOLD_DETAIL="ihf-virtual-page-permalink-text-sold-detail";		

		//Listing OfficeListVirtualPage related options
		const OPTION_VIRTUAL_PAGE_TITLE_OFFICE_LIST="ihf-virtual-page-title-office-list";
		const OPTION_VIRTUAL_PAGE_TEMPLATE_OFFICE_LIST="ihf-virtual-page-template-office-list";
		const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_OFFICE_LIST="ihf-virtual-page-permalink-text-office-list";		

		//Listing OfficeDetailVirtualPage related options
		const OPTION_VIRTUAL_PAGE_TITLE_OFFICE_DETAIL="ihf-virtual-page-title-office-detail";
		const OPTION_VIRTUAL_PAGE_TEMPLATE_OFFICE_DETAIL="ihf-virtual-page-template-office-detail";
		const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_OFFICE_DETAIL="ihf-virtual-page-permalink-text-office-detail";		

		//Listing AgentListVirtualPage related options
		const OPTION_VIRTUAL_PAGE_TITLE_AGENT_LIST="ihf-virtual-page-title-agent-list";
		const OPTION_VIRTUAL_PAGE_TEMPLATE_AGENT_LIST="ihf-virtual-page-template-agent-list";
		const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_AGENT_LIST="ihf-virtual-page-permalink-text-agent-list";		

		//Listing AgentDetailVirtualPage related options
		const OPTION_VIRTUAL_PAGE_TITLE_AGENT_DETAIL="ihf-virtual-page-title-agent-detail";
		const OPTION_VIRTUAL_PAGE_TEMPLATE_AGENT_DETAIL="ihf-virtual-page-template-agent-detail";
		const OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_AGENT_DETAIL="ihf-virtual-page-permalink-text-agent-detail";		
		
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

			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_MAP_SEARCH );
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_MAP_SEARCH );
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_MAP_SEARCH );
			
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

			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_CONTACT_FORM);
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_CONTACT_FORM );
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_CONTACT_FORM );

			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_VALUATION_FORM);
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_VALUATION_FORM );
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_VALUATION_FORM );

			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_OPEN_HOME_SEARCH_FORM);
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_OPEN_HOME_SEARCH_FORM );
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_OPEN_HOME_SEARCH_FORM);

			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_SUPPLEMENTAL_LISTING);
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_SUPPLEMENTAL_LISTING );
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SUPPLEMENTAL_LISTING );
			
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_SOLD_FEATURED);
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_SOLD_FEATURED );
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SOLD_FEATURED );

			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_SOLD_DETAIL );
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_SOLD_DETAIL );
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SOLD_DETAIL );

			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_OFFICE_LIST );
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_OFFICE_LIST );
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_OFFICE_LIST );
			
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_OFFICE_DETAIL );
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_OFFICE_DETAIL );
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_OFFICE_DETAIL );

			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_AGENT_LIST );
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_AGENT_LIST );
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_AGENT_LIST );
			
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_AGENT_DETAIL );
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_AGENT_DETAIL );
			register_setting( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG, IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_AGENT_DETAIL );
			
		}
		
		public function getDefaultTemplate(){
			$defaultTemplate= get_option(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_DEFAULT);
			return $defaultTemplate ;
		}
		

	}//end class
}
?>