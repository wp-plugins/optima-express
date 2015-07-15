<?php

class iHomefinderAdminPageConfig extends iHomefinderAdminAbstractPage {
	
	private static $instance;
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function registerSettings() {
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_DEFAULT);
		
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_DETAIL);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_DETAIL);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_DETAIL);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_META_TAGS_DETAIL);
		
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_SEARCH);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_SEARCH);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SEARCH);
		
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_MAP_SEARCH);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_MAP_SEARCH);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_MAP_SEARCH);
		
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_ADVANCED_SEARCH);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_ADVANCED_SEARCH);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_ADVANCED_SEARCH);
		
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_ORGANIZER_LOGIN);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_ORGANIZER_LOGIN);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_ORGANIZER_LOGIN);
		
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_EMAIL_UPDATES);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_EMAIL_UPDATES);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_EMAIL_UPDATES);
		
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_FEATURED);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_FEATURED);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_FEATURED);
		
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_HOTSHEET);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_HOTSHEET);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_HOTSHEET);
		
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_HOTSHEET_LIST);
		
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_CONTACT_FORM);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_CONTACT_FORM);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_CONTACT_FORM);
		
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_VALUATION_FORM);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_VALUATION_FORM);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_VALUATION_FORM);
		
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_OPEN_HOME_SEARCH_FORM);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_OPEN_HOME_SEARCH_FORM);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_OPEN_HOME_SEARCH_FORM);
		
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_SUPPLEMENTAL_LISTING);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_SUPPLEMENTAL_LISTING);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SUPPLEMENTAL_LISTING);
		
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_SOLD_FEATURED);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_SOLD_FEATURED);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SOLD_FEATURED);
		
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_SOLD_DETAIL);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_SOLD_DETAIL);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SOLD_DETAIL);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_META_TAGS_SOLD_DETAIL);
		
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_OFFICE_LIST);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_OFFICE_LIST);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_OFFICE_LIST);
		
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_OFFICE_DETAIL);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_OFFICE_DETAIL);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_OFFICE_DETAIL);
		
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_AGENT_LIST);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_AGENT_LIST);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_AGENT_LIST);
		
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_AGENT_DETAIL);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_AGENT_DETAIL);
		register_setting(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG, iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_AGENT_DETAIL);
	}
	
	private function showDuplicateUrlMessage() {
		$urlFactory = iHomefinderUrlFactory::getInstance();
		$urls = array(
			$urlFactory->getListingsSearchResultsUrl(),
			$urlFactory->getListingsSearchFormUrl(),
			$urlFactory->getMapSearchFormUrl(),
			$urlFactory->getListingsAdvancedSearchFormUrl(),
			$urlFactory->getListingDetailUrl(),
			$urlFactory->getListingSoldDetailUrl(),
			$urlFactory->getFeaturedSearchResultsUrl(),
			$urlFactory->getHotsheetSearchResultsUrl(),
			//$urlFactory->getHotsheetListUrl(), //this is an intentional duplicate
			$urlFactory->getOrganizerLoginUrl(),
			$urlFactory->getOrganizerLogoutUrl(),
			$urlFactory->getOrganizerLoginSubmitUrl(),
			$urlFactory->getOrganizerEditSavedSearchUrl(),
			$urlFactory->getOrganizerEditSavedSearchSubmitUrl(),
			$urlFactory->getOrganizerDeleteSavedSearchSubmitUrl(),
			$urlFactory->getOrganizerViewSavedSearchUrl(),
			$urlFactory->getOrganizerViewSavedSearchListUrl(),
			$urlFactory->getOrganizerResendConfirmationEmailUrl(),
			$urlFactory->getOrganizerActivateSubscriberUrl(),
			$urlFactory->getOrganizerSendSubscriberPasswordUrl(),
			$urlFactory->getOrganizerViewSavedListingListUrl(),
			$urlFactory->getOrganizerDeleteSavedListingUrl(),
			$urlFactory->getOrganizerEmailUpdatesConfirmationUrl(),
			$urlFactory->getOrganizerHelpUrl(),
			$urlFactory->getOrganizerEditSubscriberUrl(),
			$urlFactory->getContactFormUrl(),
			$urlFactory->getValuationFormUrl(),
			$urlFactory->getOpenHomeSearchFormUrl(),
			$urlFactory->getSoldFeaturedListingUrl(),
			$urlFactory->getSupplementalListingUrl(),
			$urlFactory->getOfficeListUrl(),
			$urlFactory->getOfficeDetailUrl(),
			$urlFactory->getAgentListUrl(),
			$urlFactory->getAgentDetailUrl(),
		);
		$duplicateUrls = array_unique(array_diff_assoc($urls, array_unique($urls)));
		if(!empty($duplicateUrls)) {
			?>
			<div class="updated">
				<?php foreach($duplicateUrls as $duplicateUrl) { ?>
					<p>
						<?php echo $duplicateUrl; ?> is a duplicate URL. Please change permalink.
					</p>
				<?php } ?>
			</div>
			<?php
		}
	}
	
	protected function getContent() {
		wp_enqueue_script("postbox");
		$permissions = iHomefinderPermissions::getInstance();
		?>
		<h2>IDX Pages</h2>
		<?php $this->showDuplicateUrlMessage(); ?>
		<form method="post" action="options.php">
			<p class="submit">
				<button type="submit" class="button-primary">Save Changes</button>
				<button class="button" type="button" data-ihf-postbox-toggle="closed">Expand All</button>
			</p>
			<p>Edit page attributes for the IDX pages listed below. Type "{" in the Title or Meta Tags field for a list of available options.</p>
			<div id="poststuff">
				<div id="postbox-container" class="postbox-container">
					<div class="meta-box-sortables ui-sortable">
						<?php settings_fields(iHomefinderConstants::OPTION_VIRTUAL_PAGE_CONFIG); ?>
						<?php
						if($permissions->isListingDetailEnabled()) {
							$this->getDetailPageSetup();
						}
						if($permissions->isBasicSearchEnabled()) {
							$this->getBasicSearchPageSetup();
						}
						if($permissions->isMapSearchEnabled()) {
							$this->getMapSearchPageSetup();
						}
						if($permissions->isAdvancedSearchEnabled()) {
							$this->getAdvancedSearchPageSetup();
						}
						if($permissions->isOrganizerEnabled()) {
							$this->getOrganizerLoginPageSetup();
						}
						if($permissions->isEmailUpdatesEnabled()) {
							$this->getEmailAlertsPageSetup();
						}
						if($permissions->isFeaturedPropertiesEnabled()) {
							$this->getFeaturedPageSetup();
						}
						if($permissions->isHotSheetEnabled()) {
							$this->getHotsheetListPageSetup();
							$this->getHotsheetPageSetup();
						}
						if($permissions->isContactFormEnabled()) {
							$this->getContactFormPageSetup();
						}
						if($permissions->isValuationEnabled()) {
							$this->getValuationFormPageSetup();
						}
						if($permissions->isOpenHomeSearchEnabled()) {
							$this->getOpenHomeSearchFormPageSetup();
						}
						if($permissions->isSupplementalListingsEnabled()) {
							$this->getSupplementalListingPageSetup();
						}
						if($permissions->isSoldPendingEnabled()) {
							$this->getSoldFeaturedListingPageSetup();
							$this->getSoldDetailPageSetup();
						}
						if($permissions->isOfficeEnabled()) {
							$this->getOfficeListPageSetup();
							$this->getOfficeDetailPageSetup();
						}
						if($permissions->isAgentBioEnabled()) {
							$this->getAgentListPageSetup();
							$this->getAgentDetailPageSetup();
						}
						$this->getDefaultPageSetup();
						?>
					</div>
				</div>
			</div>
			<p>* Template selection is compatible only with select themes.</p>
			<p class="submit">
				<button type="submit" class="button-primary">Save Changes</button>
			</p>
		</form>
		<script type="text/javascript">
			jQuery(document).on("ready", function(){
				postboxes.add_postbox_toggles();
				jQuery(".ui-sortable").sortable("disable");
				jQuery("[data-ihf-postbox-toggle]").on("click", function() {
					$button = jQuery(this);
					if($button.text() === "Expand All") {
						jQuery(".postbox").removeClass("closed");
						$button.text("Close All");
					} else {
						jQuery(".postbox").addClass("closed");
						$button.text("Expand All");
					}
				});
			});
			jQuery("[data-ihf-toggle]").on("click", function() {
				var $button = jQuery(this);
				var $preview = $button.parent().find(".ihf-permalink-preview");
				var $field = $button.parent().find(".ihf-permalink-field");
				if($button.text() === "Edit") {
					$button.text("Save");
				} else {
					$button.text("Edit");
				}
				$preview.text($field.val());
				$preview.toggle();
				$field.toggle();
			});
			</script>
		<?php
	}
	
	private function getDetailPageSetup() {
		$this->getPageSetup(array(
			"sectionTitle" => "Property Details",
			"virtualPageType" => iHomefinderVirtualPageFactory::LISTING_DETAIL,
			"permalinkOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_DETAIL,
			"extraPermalinkText" => "{listingAddress}/{listingNumber}/{boardId}/",
			"titleOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_DETAIL,
			"templateOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_DETAIL,
			"metaTagsOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_META_TAGS_DETAIL
		));
	}
	
	private function getSoldDetailPageSetup() {
		$this->getPageSetup(array(
			"sectionTitle" => "Sold Property Details",
			"virtualPageType" => iHomefinderVirtualPageFactory::LISTING_SOLD_DETAIL, 
			"permalinkOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SOLD_DETAIL, 
			"extraPermalinkText" => "{listingAddress}/{listingNumber}/{boardId}/",
			"titleOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_SOLD_DETAIL,
			"templateOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_SOLD_DETAIL,
			"metaTagsOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_META_TAGS_SOLD_DETAIL
		));
	}

	private function getBasicSearchPageSetup() {
		$this->getPageSetup(array(
			"sectionTitle" => "Search Form",
			"virtualPageType" => iHomefinderVirtualPageFactory::LISTING_SEARCH_FORM, 
			"permalinkOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SEARCH,
			"titleOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_SEARCH, 
			"templateOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_SEARCH
		));
	}
	
	private function getMapSearchPageSetup() {
		$this->getPageSetup(array(
			"sectionTitle" => "Map Search",
			"virtualPageType" => iHomefinderVirtualPageFactory::MAP_SEARCH_FORM, 
			"permalinkOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_MAP_SEARCH,
			"titleOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_MAP_SEARCH, 
			"templateOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_MAP_SEARCH
		));
	}

	private function getAdvancedSearchPageSetup() {
		$this->getPageSetup(array(
			"sectionTitle" => "Advanced Search Form",
			"virtualPageType" => iHomefinderVirtualPageFactory::LISTING_ADVANCED_SEARCH_FORM, 
			"permalinkOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_ADVANCED_SEARCH, 
			"titleOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_ADVANCED_SEARCH, 
			"templateOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_ADVANCED_SEARCH
		));
	}

	private function getOrganizerLoginPageSetup() {
		$this->getPageSetup(array(
			"sectionTitle" => "Organizer Login",
			"virtualPageType" => iHomefinderVirtualPageFactory::ORGANIZER_LOGIN,
			"permalinkOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_ORGANIZER_LOGIN,
			"titleOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_ORGANIZER_LOGIN, 
			"templateOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_ORGANIZER_LOGIN
		));
	}

	private function getEmailAlertsPageSetup() {
		$this->getPageSetup(array(
			"sectionTitle" => "Email Alerts",
			"virtualPageType" => iHomefinderVirtualPageFactory::ORGANIZER_EDIT_SAVED_SEARCH, 
			"permalinkOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_EMAIL_UPDATES,
			"titleOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_EMAIL_UPDATES, 
			"templateOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_EMAIL_UPDATES
		));
	}

	private function getFeaturedPageSetup() {
		$this->getPageSetup(array(
			"sectionTitle" => "Featured Properties",
			"virtualPageType" => iHomefinderVirtualPageFactory::FEATURED_SEARCH, 
			"permalinkOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_FEATURED, 
			"titleOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_FEATURED, 
			"templateOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_FEATURED
		));
	}
	
	private function getHotsheetListPageSetup() {
		$this->getPageSetup(array(
			"sectionTitle" => "List of Saved Search Pages",
			"virtualPageType" => iHomefinderVirtualPageFactory::HOTSHEET_LIST,
			"permalinkEditable" => false,
			"permalinkOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_HOTSHEET,
			"titleOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_HOTSHEET_LIST
		));
	}
	
	private function getHotsheetPageSetup() {
		$this->getPageSetup(array(
			"sectionTitle" => "Saved Search Page",
			"virtualPageType" => iHomefinderVirtualPageFactory::HOTSHEET_SEARCH_RESULTS,
			"permalinkOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_HOTSHEET,
			"extraPermalinkText" => "{savedSearchName}/{savedSearchId}/",
			"titleOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_HOTSHEET,
			"templateOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_HOTSHEET,
			"metaTagsOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_META_TAGS_HOTSHEET
		));
	}
	
	private function getContactFormPageSetup() {
		$this->getPageSetup(array(
			"sectionTitle" => "Contact Form",
			"virtualPageType" => iHomefinderVirtualPageFactory::CONTACT_FORM, 
			"permalinkOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_CONTACT_FORM, 
			"titleOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_CONTACT_FORM,
			"templateOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_CONTACT_FORM
		));
	}
	
	private function getValuationFormPageSetup() {
		$this->getPageSetup(array(
			"sectionTitle" => "Valuation Request",
			"virtualPageType" => iHomefinderVirtualPageFactory::VALUATION_FORM, 
			"permalinkOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_VALUATION_FORM, 
			"titleOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_VALUATION_FORM,
			"templateOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_VALUATION_FORM
		));
	}
	
	private function getSupplementalListingPageSetup() {
		$this->getPageSetup(array(
			"sectionTitle" => "Supplemental Listing",
			"virtualPageType" => iHomefinderVirtualPageFactory::SUPPLEMENTAL_LISTING, 
			"permalinkOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SUPPLEMENTAL_LISTING, 
			"titleOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_SUPPLEMENTAL_LISTING,
			"templateOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_SUPPLEMENTAL_LISTING
		));
	}
	
	private function getSoldFeaturedListingPageSetup() {
		$this->getPageSetup(array(
			"sectionTitle" => "Sold Featured Listing",
			"virtualPageType" => iHomefinderVirtualPageFactory::SOLD_FEATURED_LISTING, 
			"permalinkOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SOLD_FEATURED, 
			"titleOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_SOLD_FEATURED,
			"templateOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_SOLD_FEATURED
		));
	}
	
	private function getOpenHomeSearchFormPageSetup() {
		$this->getPageSetup(array(
			"sectionTitle" => "Open Home Search",
			"virtualPageType" => iHomefinderVirtualPageFactory::OPEN_HOME_SEARCH_FORM, 
			"permalinkOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_OPEN_HOME_SEARCH_FORM, 
			"titleOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_OPEN_HOME_SEARCH_FORM,
			"templateOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_OPEN_HOME_SEARCH_FORM
		));
	}
	
	private function getOfficeListPageSetup() {
		$this->getPageSetup(array(
			"sectionTitle" => "Office List",
			"virtualPageType" => iHomefinderVirtualPageFactory::OFFICE_LIST, 
			"permalinkOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_OFFICE_LIST, 
			"titleOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_OFFICE_LIST,
			"templateOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_OFFICE_LIST
		));
	}

	private function getOfficeDetailPageSetup() {
		$this->getPageSetup(array(
			"sectionTitle" => "Office Detail",
			"virtualPageType" => iHomefinderVirtualPageFactory::OFFICE_DETAIL, 
			"permalinkOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_OFFICE_DETAIL, 
			"extraPermalinkText" => "{officeName}/{officeId}/",
			"titleOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_OFFICE_DETAIL,
			"templateOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_OFFICE_DETAIL
		));
	}
			
	private function getAgentListPageSetup() {
		$this->getPageSetup(array(
			"sectionTitle" => "Agent List",
			"virtualPageType" => iHomefinderVirtualPageFactory::AGENT_LIST, 
			"permalinkOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_AGENT_LIST, 
			"titleOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_AGENT_LIST,
			"templateOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_AGENT_LIST
		));
	}

	private function getAgentDetailPageSetup() {
		$this->getPageSetup(array(
			"sectionTitle" => "Agent Bio",
			"virtualPageType" => iHomefinderVirtualPageFactory::AGENT_DETAIL, 
			"permalinkOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_AGENT_DETAIL,
			"extraPermalinkText" => "{agentName}/{agentId}/",
			"titleOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TITLE_AGENT_DETAIL,
			"templateOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_AGENT_DETAIL
		));
	}
	
	private function getDefaultPageSetup() {
		$this->getPageSetup(array(
			"sectionTitle" => "Other IDX Pages",
			"templateOption" => iHomefinderConstants::OPTION_VIRTUAL_PAGE_TEMPLATE_DEFAULT,
			"virtualPageType" => iHomefinderVirtualPageFactory::DEFAULT_PAGE
		));
	}
	
	/**
	 * 
	 * Used to setup form elements to allow customization of iHomefinder page title, urls and 
	 * display templates. iHomefinder pages are not true pages in the WordPress database, so we 
	 * need to remember the title, permalink and template as options.
	 * 
	 * @param array $settings
	 */
	private function getPageSetup($settings) {
		
		$sectionTitle = $this->getSetting($settings, "sectionTitle");
		
		$virtualPageType = $this->getSetting($settings, "virtualPageType");
		$virtualPage = iHomefinderVirtualPageFactory::getInstance()->getVirtualPage($virtualPageType);
		
		$permalinkOption = $this->getSetting($settings, "permalinkOption");
		$permalinkEditable = $this->getSetting($settings, "permalinkEditable", true);
		$extraPermalinkText = $this->getSetting($settings, "extraPermalinkText");
		
		$titleOption = $this->getSetting($settings, "titleOption");
		$extraTitleText = $this->getSetting($settings, "extraTitleText");
		
		$templateOption = $this->getSetting($settings, "templateOption");
		
		$metaTagsOption = $this->getSetting($settings, "metaTagsOption");
		
		if($virtualPage !== null) {
			?>
			<div class="postbox closed">
				<div title="Click to toggle" class="handlediv"></div>
				<h3 class="hndle">
					<?php echo $sectionTitle ?>
				</h3>
				<div class="inside">
					<table class="form-table condensed">
						<?php if($permalinkOption !== null) { ?>
							<tr>
								<th>
									<label for="<?php echo $permalinkOption; ?>">Permalink</label>
								</th>
								<td>
									<?php if($permalinkEditable) { ?>
										<span><?php echo iHomefinderUrlFactory::getInstance()->getBaseUrl(); ?>/<span class="ihf-permalink-preview"><?php echo $virtualPage->getPermalink(); ?></span><input id="<?php echo $permalinkOption; ?>" class="ihf-permalink-field" type="text" name="<?php echo $permalinkOption; ?>" value="<?php echo $virtualPage->getPermalink(); ?>" />/<?php echo $extraPermalinkText; ?></span>
										<button class="button" style="vertical-align: middle;" type="button" data-ihf-toggle>Edit</button>
									<?php } else { ?>
										<span><?php echo iHomefinderUrlFactory::getInstance()->getBaseUrl(); ?>/<?php echo $virtualPage->getPermalink(); ?>/<?php echo $extraPermalinkText; ?></span>
									<?php } ?>
								</td>
							</tr>
						<?php } ?>
						<?php if($titleOption !== null) { ?>
							<tr>
								<th>
									<label for="<?php echo $titleOption; ?>">Title</label>
								</th>
								<td>
									<input id="<?php echo $titleOption; ?>" class="regular-text" type="text" name="<?php echo $titleOption; ?>" value="<?php echo $virtualPage->getTitle(); ?>" autocomplete="off" />
									<?php $this->getAutoComplete($virtualPage, $titleOption); ?>
									<?php if($extraTitleText != null) { ?>
										<span class="description">
											<?php echo $extraTitleText; ?>
										</span>
									<?php } ?>
								</td>
							</tr>
						<?php } ?>
						<?php if($templateOption !== null) { ?>
							<tr>
								<th>
									<label for="<?php echo $templateOption; ?>">Theme Template*</label>
								</th>
								<td>
									<select id="<?php echo $templateOption; ?>" name="<?php echo $templateOption; ?>">
										<option value="default">Default Template</option>
										<?php page_template_dropdown($virtualPage->getPageTemplate()); ?>
									</select>
								</td>
							</tr>
						<?php } ?>
						<?php if($metaTagsOption !== null && iHomefinderLayoutManager::getInstance()->supportsSeoVariables()) { ?>
							<tr>
								<th>
									<label for="<?php echo $metaTagsOption; ?>">Meta Tags</label>
								</th>
								<td>
									<textarea id="<?php echo $metaTagsOption; ?>" style="width: 100%; height: 105px;" name="<?php echo $metaTagsOption; ?>"><?php echo $virtualPage->getMetaTags(); ?></textarea>
									<?php $this->getAutoComplete($virtualPage, $metaTagsOption); ?>
								</td>
							</tr>
						<?php } ?>
					</table>
				</div>
			</div>
			<?php
		}
	}
	
	private function getAutoComplete($virtualPage, $fieldId) {
		$variables = $virtualPage->getAvailableVariables();
		if(!empty($variables) && iHomefinderLayoutManager::getInstance()->supportsSeoVariables()) {
			$data = iHomefinderVariableUtility::getInstance()->getAffixedArray($variables);
			?>
			<script type="text/javascript">
				jQuery(document).on("ready", function() {
					ihfVariablesAutocomplete(
						"<?php echo $fieldId; ?>",
						<?php echo json_encode($data); ?>,
						"<?php echo iHomefinderVariable::PREFIX; ?>",
						"<?php echo iHomefinderVariable::SUFFIX; ?>"
					);
				});
			</script>
			<?php
		}
	}
	
	private function getSetting($settings, $name, $defaultValue = null) {
		$result = $defaultValue;
		if(is_array($settings) && array_key_exists($name, $settings)) {
			$result = $settings[$name];
		}
		return $result;
	}
	
}