<?php

/**
 * 
 * This class has methods to support creating forms to set templates, custom url patterns and
 * titles for iHomefinder Virtual Pages.
 * 
 * @author ihomefinder
 */
class iHomefinderAdminPageConfig {
	
	private static $instance;
	private $virtualPageFactory;

	private function __construct() {
		$this->virtualPageFactory=iHomefinderVirtualPageFactory::getInstance();
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new iHomefinderAdminPageConfig();
		}
		return self::$instance;
	}
	
	public function getDefaultPageSetup() {
		$selectedTemplate=iHomefinderVirtualPageHelper::getInstance()->getDefaultTemplate();
		?>
		<h3>Other IDX Pages</h3>
		<table>
			<tr>
				<td><b>Theme Template*:</b></td>
				<td>
					<select name="<?php echo iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_DEFAULT ?>">
						<option value='default'><?php _e('Default Template'); ?></option>
						<?php page_template_dropdown($selectedTemplate); ?>
					</select>
				</td>
			</tr>
		</table>
	<?php
	}

	public function permalinkJavascript($permalinkId, $urlFactory) {
		?>
		<script>
			jQuery('#<?php echo $permalinkId?>EditButton').click(function() {
				jQuery('#<?php echo $permalinkId ?>Edit').show();
				jQuery('#<?php echo $permalinkId ?>Container').hide();
			});
			jQuery('#<?php echo $permalinkId ?>DoneButton').click(function() {
				var inputObject=jQuery('#<?php echo $permalinkId ?>');
				var inputValue = inputObject.val();
				inputValue = inputValue.replace(/\s/g,"-");
				inputObject.val(inputValue);

				jQuery('#<?php echo $permalinkId ?>Text').text(jQuery('#<?php echo $permalinkId ?>').val());
				jQuery('#<?php echo $permalinkId ?>Container').show();
				jQuery('#<?php echo $permalinkId ?>Edit').hide();
			});
		</script>
		<?php
	}

	/**
	 * We do not use getPageSetup to display the detail page customization, because we require some 
	 * extra explanation of the permalink structure for detail pages.
	 * Otherwisse, this function is similar to getPageSetup.
	 */
	public function getDetailPageSetup() {
		$urlFactory = iHomefinderUrlFactory::getInstance();
		$this->getPageSetup(
			"Property Details",
			iHomefinderVirtualPageFactory::LISTING_DETAIL, 
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_DETAIL, 
			$urlFactory->getListingDetailUrl(false),
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_DETAIL,
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_DETAIL,
			"(If empty, the property address will be the title)",
			"%ADDRESS%/%LISTING_NUMBER%/%LISTING_PROVIDER%"
		); 			
	}
	
	/**
	 * We do not use getPageSetup to display the detail page customization, because we require some 
	 * extra explanation of the permalink structure for detail pages.
	 * Otherwisse, this function is similar to getPageSetup.
	 */
	public function getSoldDetailPageSetup() {
		$urlFactory = iHomefinderUrlFactory::getInstance();
		$this->getPageSetup(
			"Sold Property Details",
			iHomefinderVirtualPageFactory::LISTING_SOLD_DETAIL, 
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SOLD_DETAIL, 
			$urlFactory->getListingSoldDetailUrl(false),
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_SOLD_DETAIL,
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_SOLD_DETAIL,
			"(If empty, the property address will be the title)",
			"%ADDRESS%/%LISTING_NUMBER%/%LISTING_PROVIDER%"
		); 			
	}

	public function getSearchPageSetup() {
		$urlFactory = iHomefinderUrlFactory::getInstance();
		$this->getPageSetup(
			"Search Form",
			iHomefinderVirtualPageFactory::LISTING_SEARCH_FORM, 
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SEARCH,
			$urlFactory->getListingsSearchFormUrl(false), 
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_SEARCH, 
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_SEARCH
		);				
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::LISTING_SEARCH_FORM);
		$permalinkId=iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SEARCH;
	}
	
	public function getMapSearchPageSetup() {
		$urlFactory = iHomefinderUrlFactory::getInstance();
		$this->getPageSetup(
			"Map Search",
			iHomefinderVirtualPageFactory::MAP_SEARCH_FORM, 
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_MAP_SEARCH,
			$urlFactory->getMapSearchFormUrl(false), 
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_MAP_SEARCH, 
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_MAP_SEARCH
		);				
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::MAP_SEARCH_FORM);
		$permalinkId=iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_MAP_SEARCH;
	}

	public function getAdvSearchPageSetup() {
		$urlFactory = iHomefinderUrlFactory::getInstance();
		$this->getPageSetup(
			"Advanced Search Form",
			iHomefinderVirtualPageFactory::LISTING_ADVANCED_SEARCH_FORM, 
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_ADV_SEARCH, 
			$urlFactory->getListingsAdvancedSearchFormUrl(false), 
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_ADV_SEARCH, 
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_ADV_SEARCH
		);
	}

	public function getOrganizerLoginPageSetup() {
		$urlFactory = iHomefinderUrlFactory::getInstance();
		$this->getPageSetup(
			"Organizer Login",
			iHomefinderVirtualPageFactory::ORGANIZER_LOGIN, 
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_ORG_LOGIN, 
			$urlFactory->getOrganizerLoginUrl(false), 
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_ORG_LOGIN, 
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_ORG_LOGIN
		);
	}

	public function getEmailAlertsPageSetup() {
		$urlFactory = iHomefinderUrlFactory::getInstance();
		$this->getPageSetup(
			"Email Alerts",
			iHomefinderVirtualPageFactory::ORGANIZER_EDIT_SAVED_SEARCH, 
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_EMAIL_UPDATES, 
			$urlFactory->getOrganizerEditSavedSearchUrl(false), 
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_EMAIL_UPDATES, 
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_EMAIL_UPDATES
		);
	}

	public function getFeaturedPageSetup() {
		$urlFactory = iHomefinderUrlFactory::getInstance();
		$this->getPageSetup(
			"Featured Properties",
			iHomefinderVirtualPageFactory::FEATURED_SEARCH, 
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_FEATURED, 
			$urlFactory->getFeaturedSearchResultsUrl(false), 
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_FEATURED, 
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_FEATURED
		);
	}
	
	public function getContactFormPageSetup() {
		$urlFactory = iHomefinderUrlFactory::getInstance();
		$this->getPageSetup(
			"Contact Form",
			iHomefinderVirtualPageFactory::CONTACT_FORM, 
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_CONTACT_FORM, 
			$urlFactory->getContactFormUrl(false),
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_CONTACT_FORM,
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_CONTACT_FORM
		);
	}
	
	public function getValuationFormPageSetup() {
		$urlFactory = iHomefinderUrlFactory::getInstance();
		$this->getPageSetup(
			"Valuation Request",
			iHomefinderVirtualPageFactory::VALUATION_FORM, 
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_VALUATION_FORM, 
			$urlFactory->getValuationFormUrl(false),
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_VALUATION_FORM,
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_VALUATION_FORM
		); 				
	}
	
	public function getSupplementalListingPageSetup() {
		$urlFactory = iHomefinderUrlFactory::getInstance();
		$this->getPageSetup(
			"Supplemental Listing",
			iHomefinderVirtualPageFactory::SUPPLEMENTAL_LISTING, 
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SUPPLEMENTAL_LISTING, 
			$urlFactory->getSupplementalListingUrl(false),
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_SUPPLEMENTAL_LISTING,
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_SUPPLEMENTAL_LISTING
		); 			
	}
	
	public function getSoldFeaturedListingPageSetup() {
		$urlFactory = iHomefinderUrlFactory::getInstance();
		$this->getPageSetup(
			"Sold Featured Listing",
			iHomefinderVirtualPageFactory::SOLD_FEATURED_LISTING, 
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SOLD_FEATURED, 
			$urlFactory->getSoldFeaturedListingUrl(false),
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_SOLD_FEATURED,
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_SOLD_FEATURED
		); 			
	}
	
	public function getOpenHomeSearchFormPageSetup() {
		$urlFactory = iHomefinderUrlFactory::getInstance();
		$this->getPageSetup(
			"Open Home Search",
			iHomefinderVirtualPageFactory::OPEN_HOME_SEARCH_FORM, 
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_OPEN_HOME_SEARCH_FORM, 
			$urlFactory->getOpenHomeSearchFormUrl(false),
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_OPEN_HOME_SEARCH_FORM,
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_OPEN_HOME_SEARCH_FORM
		); 				
	}
	
	public function getOfficeListPageSetup() {
		$urlFactory = iHomefinderUrlFactory::getInstance();
		$this->getPageSetup(
			"Office List",
			iHomefinderVirtualPageFactory::OFFICE_LIST, 
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_OFFICE_LIST, 
			$urlFactory->getOfficeListUrl(false),
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_OFFICE_LIST,
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_OFFICE_LIST
		); 				
	}

	public function getOfficeDetailPageSetup() {
		$urlFactory = iHomefinderUrlFactory::getInstance();
		$this->getPageSetup(
			"Office Detail",
			iHomefinderVirtualPageFactory::OFFICE_DETAIL, 
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_OFFICE_DETAIL, 
			$urlFactory->getOfficeDetailUrl(false),
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_OFFICE_DETAIL,
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_OFFICE_DETAIL,
			"(If the title is blank, the office name will be used for the title)",
			"%OFFICE_NAME%/%OFFICE_ID%"
		); 				
	}
			
	public function getAgentListPageSetup() {
		$urlFactory = iHomefinderUrlFactory::getInstance();
		$this->getPageSetup(
			"Agent List",
			iHomefinderVirtualPageFactory::AGENT_LIST, 
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_AGENT_LIST, 
			$urlFactory->getAgentListUrl(false),
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_AGENT_LIST,
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_AGENT_LIST
		); 				
	}

	public function getAgentDetailPageSetup() {
		$urlFactory = iHomefinderUrlFactory::getInstance();
		$this->getPageSetup(
			"Agent Bio",
			iHomefinderVirtualPageFactory::AGENT_DETAIL, 
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_AGENT_DETAIL, 
			$urlFactory->getAgentDetailUrl(false),
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_AGENT_DETAIL,
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_AGENT_DETAIL,
			"(If the title is blank, the agent name will be used for the title)",
			"%AGENT_NAME%/%AGENT_ID%"
		); 				
	}

	public function getHotsheetPageSetup() {
		$urlFactory = iHomefinderUrlFactory::getInstance();
		$virtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::HOTSHEET_SEARCH_RESULTS);
		$permalinkId=iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_HOTSHEET . "-list";
		$hotsheetListVirtualPage = $this->virtualPageFactory->getVirtualPage(iHomefinderVirtualPageFactory::HOTSHEET_LIST);
		?>
		<h3>List of Saved Search Pages</h3>
		<table>
			<tr>
				<td>
					<b>Permalink:</b>
				</td>
				<td>
				  <div id="<?php echo $permalinkId ?>Container">
					<?php echo $urlFactory->getHotsheetListUrl(true) ?>
				  </div>
				</td>
			</tr>
			<tr>
				<td>
					<b>Title:</b>
				</td>
				<td>
				  <?php echo $hotsheetListVirtualPage->getTitle() ?>
				</td>
			</tr>
		</table>
		<?php
		$this->getPageSetup(
			"Saved Search Page",
			iHomefinderVirtualPageFactory::HOTSHEET_SEARCH_RESULTS, 
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_HOTSHEET, 
			$urlFactory->getHotsheetSearchResultsUrl(false),
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_HOTSHEET,
			iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_HOTSHEET,
			"(If empty, the name of the Saved Search Page will be the title)",
			"%SAVED_SEARCH_PAGE_NAME%/%SAVED_SEARCH_ID%"
		);
	}
	
	/**
	 * 
	 * Function to setup form elements to allow customization of iHomefinder page title, urls and 
	 * display templates.  iHomefinder pages are not true pages in the Wordpress database, so we 
	 * need to remember the title, permalink and template as options.
	 * 
	 * @param unknown_type $pageTitle
	 * @param unknown_type $virtualPageKey
	 * @param unknown_type $permalLinkId
	 * @param unknown_type $currentUrl
	 * @param unknown_type $titleOption
	 * @param unknown_type $templateOption
	 */
	public function getPageSetup(
		$pageTitle,
		$virtualPageKey,
		$permalinkId,
		$currentUrl,
		$titleOption, 
		$templateOption,
		$extraTitleText = null,
		$extraPermalinkText = null
	) {
		$urlFactory = iHomefinderUrlFactory::getInstance();
		$virtualPage = $this->virtualPageFactory->getVirtualPage($virtualPageKey);
		?>
		<h3>
			<?php echo $pageTitle ?>
		</h3>
		<table>
			<tr>
				<td>
					<b>Permalink:</b>
				</td>
				<td>
				  <div id="<?php echo $permalinkId ?>Container">
					<?php echo $urlFactory->getBaseUrl() ?>/<span id="<?php echo $permalinkId ?>Text"><?php echo $currentUrl ?></span>/<?php if($extraPermalinkText != null) {echo $extraPermalinkText;} ?>
					<input id="<?php echo $permalinkId ?>EditButton" type="button" value="Edit">
				  </div>
				  <div id="<?php echo $permalinkId ?>Edit" style="display: none;" >
					<?php echo $urlFactory->getBaseUrl() ?>/
					<input type="text" id="<?php echo $permalinkId ?>" name="<?php echo $permalinkId ?>" value="<?php echo $currentUrl ?>" />/<?php if($extraPermalinkText != null) {echo $extraPermalinkText;} ?>
					<input id="<?php echo $permalinkId ?>DoneButton" type="button" value="Done">
				  </div>
				</td>
			</tr>
			<tr>
				<td>
					<b>Title:</b>
				</td>
				<td>
					<input type="text" name="<?php echo $titleOption ?>" value="<?php echo $virtualPage->getTitle() ?>" />
					<?php if($extraTitleText != null) {echo $extraTitleText;} ?>
				</td>
			</tr>
			<tr>
				<td>
					<b>Theme Template*:</b>
				</td>
				<td>
					<select name="<?php echo $templateOption ?>">
						<option value='default'><?php _e('Default Template'); ?></option>
						<?php page_template_dropdown($virtualPage->getPageTemplate()); ?>
					</select>
				</td>
			</tr>
		</table>

	<?php
		$this->permalinkJavascript($permalinkId, $urlFactory);
	}
	
}