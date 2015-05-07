<?php

class iHomefinderAdmin {

	private static $instance;
	
	private function __construct() {
		
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function checkError() {
		$pageName = null;
		if(array_key_exists("page", $_REQUEST)) {
			$pageName = $_REQUEST["page"];
		}
		
		//Check for valid plugin registration
		//Do not check for registration on the registration page.
		if($pageName != iHomefinderConstants::OPTION_ACTIVATE && !$this->isActivated()) {
			?>
			<style type="text/css">
				.green-bar {
					border-radius: 3px 3px 3px 3px;
					border-style: solid;
					border-width: 1px;
					color: #FFFFFF;
					width: 95%;
					padding: 0.4em 1em;
					text-align: left;
					font:12px arial;
					background-color: #4F800D;
				}
			</style>
			<p class="green-bar">
				<a href="admin.php?page=<?php echo iHomefinderConstants::OPTION_ACTIVATE ?>" class="button button-primary">Activate Your Optima Express Account</a>
				&nbsp;&nbsp;&nbsp;Get an unlimited free trial or paid subscription for your MLS
			</p>
			<?php
		}
		
		if(get_option(iHomefinderConstants::COMPATIBILITY_CHECK_ENABLED, true) !== "false") {
			$errors = array();
			//check if permalink structure is set
			$permalinkStructure = get_option("permalink_structure", null);
			if(empty($permalinkStructure)) {
				$errors[] = "<a href=\"" . admin_url("options-permalink.php") . "\">WordPress permalink settings are set as default (Error 404)</a>";					
			}
			$remoteRequest = new iHomefinderRequestor();
			$remoteRequest
				->addParameter("method", "handleRequest")
				->addParameter("viewType", "json")
				->addParameter("requestType", "compatibility-check")
			;
			$remoteRequest->setCacheExpiration(60*60*24);
			$remoteResponse = $remoteRequest->remoteGetRequest();
			if(!empty($remoteResponse)) {
				$content = null;
				if(iHomefinderLayoutManager::getInstance()->isResponsive()) {
					$content = (string) $remoteRequest->getJson($remoteResponse);
				} else {
					$content = json_encode($remoteResponse);
				}
				if(!empty($content)) {
					$compatibility = json_decode($content, true);
					if(!empty($compatibility) && is_array($compatibility)) {
						//check plugins
						if(array_key_exists("Plugin", $compatibility)) {
							$incompatiblePlugins = $compatibility["Plugin"];
							$plugins = get_plugins();
							foreach($plugins as $pluginPath => $plugin) {
								if(is_plugin_active($pluginPath)) {
									$pluginName = $plugin["Name"];
									if(array_key_exists($pluginName, $incompatiblePlugins)) {
										$message = $incompatiblePlugins[$pluginName];
										if($message != null) {
											$errors[] = "<a href=\"" . admin_url("plugins.php") . "?s=" . urlencode($pluginName) . "\">" . $pluginName . "</a> (" . $message . ")";
										}
									}
								}
							}
						}
						//check theme
						if(array_key_exists("Theme", $compatibility)) {
							$theme = wp_get_theme();
							$themeName = $theme->get("Name");
							$incompatibleThemes = $compatibility["Theme"];
							if(array_key_exists($themeName, $incompatibleThemes)) {
								$message = $incompatibleThemes[$themeName];
								if($message != null) {
									$errors[] = "<a href=\"" . admin_url("themes.php") . "\">" . $themeName . "</a> (" . $message . ")";
								}
							}
						}
					}
				}
			}
			//check error count
			if(count($errors) > 0) {
				?>
				<div class="error">
					<h3 style="float: left;">
						<?php echo count($errors) ?> compatibility issue<?php if(count($errors) > 1) { ?>s<?php } ?>:
					</h3>
					<form style="float: right; margin-top: 5px;" method="post" action="options.php">
						<?php settings_fields(iHomefinderConstants::OPTION_GROUP_COMPATIBILITY_CHECK); ?>
						<input type="hidden" value="false" name="<?php echo iHomefinderConstants::COMPATIBILITY_CHECK_ENABLED ?>" />
						<button class="button-secondary" type="submit">Dismiss compatibility warnings</button>
					</form>
					<div style="clear: both;">
						<?php foreach($errors as $error) { ?>
							<p>
								<?php echo $error; ?>
							</p>
						<?php } ?>
					</div>
				</div>
				<?php
			}
		}
	}

	public function createAdminMenu() {
		add_menu_page("Optima Express", "Optima Express", "manage_options", "ihf_idx", array(iHomefinderAdminInformation::getInstance(), "getPage"));
		add_submenu_page("ihf_idx", "Information", "Information", "manage_options", "ihf_idx", array(iHomefinderAdminInformation::getInstance(), "getPage"));
		add_submenu_page("ihf_idx", "Register", "Register", "manage_options", iHomefinderConstants::OPTION_ACTIVATE, array(iHomefinderAdminActivate::getInstance(), "getPage"));
		add_submenu_page("ihf_idx", "IDX Control Panel", "IDX Control Panel", "manage_options", iHomefinderConstants::OPTION_IDX_CONTROL_PANEL, array(iHomefinderAdminControlPanel::getInstance(), "getPage"));
		add_submenu_page("ihf_idx", "IDX Pages", "IDX Pages", "manage_options", iHomefinderConstants::OPTION_PAGES, array(iHomefinderAdminPageConfig::getInstance(), "getPage"));
		add_submenu_page("ihf_idx", "Configuration", "Configuration", "manage_options", iHomefinderConstants::OPTION_CONFIG_PAGE, array(iHomefinderAdminConfiguration::getInstance(), "getPage"));
		add_submenu_page("ihf_idx", "Bio Widget", "Bio Widget", "manage_options", iHomefinderConstants::BIO_PAGE, array(iHomefinderAdminBio::getInstance(), "getPage"));
		add_submenu_page("ihf_idx", "Social Widget", "Social Widget", "manage_options", iHomefinderConstants::SOCIAL_PAGE, array(iHomefinderAdminSocial::getInstance(), "getPage"));
		add_submenu_page("ihf_idx", "Email Branding", "Email Branding", "manage_options", iHomefinderConstants::EMAIL_BRANDING_PAGE, array(iHomefinderAdminEmail::getInstance(), "getPage"));
		if(iHomefinderPermissions::getInstance()->isCommunityPagesEnabled()) {
			add_submenu_page("ihf_idx", "Community Pages", "Community Pages", "manage_options", iHomefinderConstants::COMMUNITY_PAGES, array(iHomefinderAdminCommunityPages::getInstance(), "getPage"));
		}
		if(iHomefinderPermissions::getInstance()->isSeoCityLinksEnabled()) {
			add_submenu_page("ihf_idx", "SEO City Links", "SEO City Links", "manage_options", iHomefinderConstants::SEO_CITY_LINKS_PAGE, array(iHomefinderAdminSeoCityLinks::getInstance(), "getPage"));
		}
	}

	public function updateAuthenticationToken() {
		$this->activateAuthenticationToken();
	}

	public function activateAuthenticationToken($activationToken = null) {
		if(!empty($activationToken)) {
			update_option(iHomefinderConstants::ACTIVATION_TOKEN_OPTION, $activationToken);
		}
		$authenticationInfo = $this->getAuthenticationInfo();
		if($authenticationInfo !== null) {
			if(property_exists($authenticationInfo, "authenticationToken")) {
				$authenticationToken = (string) $authenticationInfo->authenticationToken;
				update_option(iHomefinderConstants::AUTHENTICATION_TOKEN_OPTION, $authenticationToken);
			}
			if(property_exists($authenticationInfo, "permissions")) {
				$permissions = $authenticationInfo->permissions;
				iHomefinderPermissions::getInstance()->initialize($permissions);
			}
			update_option(iHomefinderConstants::IS_ACTIVATED_OPTION, "true");
			//We need to flush the rewrite rules, if any permalinks have been updated.
			//Only flush in the admin screens, because that is the only point where urls patterns may change.
			iHomefinderRewriteRules::getInstance()->flushRules();
			//clear the cache
			iHomefinderCacheUtility::getInstance()->deleteItems();
			//update menu with any new available menu items
			iHomefinderMenu::getInstance()->updateMenu();
		}
	}
	
	public function deleteAuthenticationToken() {
		delete_option(iHomefinderConstants::AUTHENTICATION_TOKEN_OPTION);
	}
	
	/**
	 * If the authentication token has expired then generate a new authentication token
	 * from the activationToken.
	 */
	public function getAuthenticationToken() {
		$authenticationToken = get_option(iHomefinderConstants::AUTHENTICATION_TOKEN_OPTION, null);
		return $authenticationToken;
	}
	
	public function previouslyActivated() {
		return get_option(iHomefinderConstants::IS_ACTIVATED_OPTION, false);
	}
	
	public function isActivated() {
		$result = false;
		$authenticationToken = $this->getAuthenticationToken();
		if(!empty($authenticationToken)) {
			$result = true;
		}
		return $result;
	}
	
	private function getAuthenticationInfo() {
		$activationToken = get_option(iHomefinderConstants::ACTIVATION_TOKEN_OPTION, null);
		if(empty($activationToken)) {
			return null;
		}
		$urlFactory = iHomefinderUrlFactory::getInstance();
		$ajaxBaseUrl = urlencode($urlFactory->getAjaxBaseUrl());
		$listingsSearchResultsUrl = urlencode($urlFactory->getListingsSearchResultsUrl(true));
		$listingsSearchFormUrl = urlencode($urlFactory->getListingsSearchFormUrl(true));
		$listingDetailUrl = urlencode($urlFactory->getListingDetailUrl(true));
		$featuredSearchResultsUrl = urlencode($urlFactory->getFeaturedSearchResultsUrl(true));
		$hotsheetSearchResultsUrl = urlencode($urlFactory->getHotsheetSearchResultsUrl(true));
		$organizerLoginUrl = urlencode($urlFactory->getOrganizerLoginUrl(true));
		$organizerLogoutUrl = urlencode($urlFactory->getOrganizerLogoutUrl(true));
		$organizerLoginSubmitUrl = urlencode($urlFactory->getOrganizerLoginSubmitUrl(true));
		$organizerEditSavedSearchUrl = urlencode($urlFactory->getOrganizerEditSavedSearchUrl(true));
		$organizerEditSavedSearchSubmitUrl = urlencode($urlFactory->getOrganizerEditSavedSearchSubmitUrl(true));
		$organizerDeleteSavedSearchSubmitUrl = urlencode($urlFactory->getOrganizerDeleteSavedSearchSubmitUrl(true));
		$organizerViewSavedSearchUrl = urlencode($urlFactory->getOrganizerViewSavedSearchUrl(true));
		$organizerViewSavedSearchListUrl = urlencode($urlFactory->getOrganizerViewSavedSearchListUrl(true));
		$organizerViewSavedListingListUrl = urlencode($urlFactory->getOrganizerViewSavedListingListUrl(true));
		$organizerDeleteSavedListingUrl = urlencode($urlFactory->getOrganizerDeleteSavedListingUrl(true));
		$organizerResendConfirmationEmailUrl = urlencode($urlFactory->getOrganizerResendConfirmationEmailUrl(true));
		$organizerActivateSubscriberUrl = urlencode($urlFactory->getOrganizerActivateSubscriberUrl(true));
		$organizerSendSubscriberPasswordUrl = urlencode($urlFactory->getOrganizerSendSubscriberPasswordUrl(true));
		$listingsAdvancedSearchFormUrl = urlencode($urlFactory->getListingsAdvancedSearchFormUrl(true));
		$organizerHelpUrl = urlencode($urlFactory->getOrganizerHelpUrl(true));
		$organizerEditSubscriberUrl = urlencode($urlFactory->getOrganizerEditSubscriberUrl(true));
		$contactFormUrl = urlencode($urlFactory->getContactFormUrl(true));
		$valuationFormUrl = urlencode($urlFactory->getValuationFormUrl(true));
		$listingSoldDetailUrl = urlencode($urlFactory->getListingSoldDetailUrl(true));
		$openHomeSearchFormUrl = urlencode($urlFactory->getOpenHomeSearchFormUrl(true));
		$soldFeaturedListingUrl = urlencode($urlFactory->getSoldFeaturedListingUrl(true));
		$supplementalListingUrl = urlencode($urlFactory->getSupplementalListingUrl(true));
		$listingSearchByAddressResultsUrl = urlencode($urlFactory->getListingSearchByAddressResultsUrl(true));
		$listingSearchByListingIdResultsUrl = urlencode($urlFactory->getListingSearchByListingIdResultsUrl(true));
		$officeListUrl = urlencode($urlFactory->getOfficeListUrl(true));
		$officeDetailUrl = urlencode($urlFactory->getOfficeDetailUrl(true));
		$agentBioListUrl = urlencode($urlFactory->getAgentListUrl(true));
		$agentBioDetailUrl = urlencode($urlFactory->getAgentDetailUrl(true));
		$mapSearchUrl = urlencode($urlFactory->getMapSearchFormUrl(true));
		$cssOverride = urlencode(get_option(iHomefinderConstants::CSS_OVERRIDE_OPTION, null));
		$layoutType = urlencode(iHomefinderLayoutManager::getInstance()->getLayoutType());
		$colorScheme = urlencode(iHomefinderLayoutManager::getInstance()->getColorScheme());
		$mobileSiteYn = get_option(iHomefinderConstants::OPTION_MOBILE_SITE_YN, null);
		$emailDisplayType = get_option(iHomefinderConstants::EMAIL_DISPLAY_TYPE_OPTION, null);
		$emailHeader = urlencode(iHomefinderAdminEmail::getInstance()->getHeader());
		$emailFooter = urlencode(iHomefinderAdminEmail::getInstance()->getFooter());
		$emailPhotoUrl = get_option(iHomefinderConstants::EMAIL_PHOTO_OPTION, null);
		$emailLogoUrl = get_option(iHomefinderConstants::EMAIL_LOGO_OPTION, null);
		$emailName = get_option(iHomefinderConstants::EMAIL_NAME_OPTION, null);
		$emailCompany = get_option(iHomefinderConstants::EMAIL_COMPANY_OPTION, null);
		$emailPhone = get_option(iHomefinderConstants::EMAIL_PHONE_OPTION, null);
		$emailAddressLine1 = get_option(iHomefinderConstants::EMAIL_ADDRESS_LINE1_OPTION, null);
		$emailAddressLine2 = get_option(iHomefinderConstants::EMAIL_ADDRESS_LINE2_OPTION, null);
				
		$emailBrandingType = null;
		switch($emailDisplayType) {
			case iHomefinderAdminEmail::EMAIL_DISPLAY_TYPE_CUSTOM_IMAGES_VALUE;
			case iHomefinderAdminEmail::EMAIL_DISPLAY_TYPE_DEFAULT_VALUE;
			$emailBrandingType = "basic";
				break;
			case iHomefinderAdminEmail::EMAIL_DISPLAY_TYPE_CUSTOM_HTML_VALUE;
				$emailBrandingType = "custom";
				break;
		}
		
		$remoteRequest = new iHomefinderRequestor();
		$remoteRequest
			->addParameter("method", "handleRequest")
			->addParameter("requestType", "activate")
			->addParameter("viewType", "json")
			->addParameter("activationToken", $activationToken)
			->addParameter("ajaxBaseUrl", $ajaxBaseUrl)
			->addParameter("type", "wordpress")
			->addParameter("listingSearchResultsUrl", $listingsSearchResultsUrl)
			->addParameter("listingSearchFormUrl", $listingsSearchFormUrl)
			->addParameter("listingDetailUrl", $listingDetailUrl)
			->addParameter("featuredSearchResultsUrl", $featuredSearchResultsUrl)
			->addParameter("hotsheetSearchResultsUrl", $hotsheetSearchResultsUrl)
			->addParameter("organizerLoginUrl", $organizerLoginUrl)
			->addParameter("organizerLogoutUrl", $organizerLogoutUrl)
			->addParameter("organizerLoginSubmitUrl", $organizerLoginSubmitUrl)
			->addParameter("organizerEditSavedSearchUrl", $organizerEditSavedSearchUrl)
			->addParameter("organizerEditSavedSearchSubmitUrl", $organizerEditSavedSearchSubmitUrl)
			->addParameter("organizerDeleteSavedSearchSubmitUrl", $organizerDeleteSavedSearchSubmitUrl)
			->addParameter("organizerViewSavedSearchUrl", $organizerViewSavedSearchUrl)
			->addParameter("organizerViewSavedSearchListUrl", $organizerViewSavedSearchListUrl)
			->addParameter("organizerViewSavedListingListUrl", $organizerViewSavedListingListUrl)
			->addParameter("organizerDeleteSavedListingUrl", $organizerDeleteSavedListingUrl)
			->addParameter("organizerResendConfirmationEmailUrl", $organizerResendConfirmationEmailUrl)
			->addParameter("organizerActivateSubscriberUrl", $organizerActivateSubscriberUrl)
			->addParameter("organizerSendSubscriberPasswordUrl", $organizerSendSubscriberPasswordUrl)
			->addParameter("listingAdvancedSearchFormUrl", $listingsAdvancedSearchFormUrl)
			->addParameter("organizerHelpUrl", $organizerHelpUrl)
			->addParameter("organizerEditSubscriberUrl", $organizerEditSubscriberUrl)
			->addParameter("contactFormUrl", $contactFormUrl)
			->addParameter("valuationFormUrl", $valuationFormUrl)
			->addParameter("listingSoldDetailUrl", $listingSoldDetailUrl)
			->addParameter("openHomeSearchFormUrl", $openHomeSearchFormUrl)
			->addParameter("soldFeaturedListingUrl", $soldFeaturedListingUrl)
			->addParameter("supplementalListingUrl", $supplementalListingUrl)
			->addParameter("listingSearchByAddressResultsUrl", $listingSearchByAddressResultsUrl)
			->addParameter("listingSearchByListingIdResultsUrl", $listingSearchByListingIdResultsUrl)
			->addParameter("officeListUrl", $officeListUrl)
			->addParameter("officeDetailUrl", $officeDetailUrl)
			->addParameter("agentBioListUrl", $agentBioListUrl)
			->addParameter("agentBioDetailUrl", $agentBioDetailUrl)
			->addParameter("mapSearchUrl", $mapSearchUrl)
			->addParameter("cssOverride", $cssOverride)
			->addParameter("layoutType", $layoutType)
			->addParameter("colorScheme", $colorScheme)
			->addParameter("mobileSiteYn", $mobileSiteYn)
			->addParameter("emailBrandingType", $emailBrandingType)
			->addParameter("emailHeader", $emailHeader)
			->addParameter("emailFooter", $emailFooter)
			->addParameter("emailPhotoUrl", $emailPhotoUrl)
			->addParameter("emailLogoUrl", $emailLogoUrl)
			->addParameter("emailName", $emailName)
			->addParameter("emailCompany", $emailCompany)
			->addParameter("emailPhone", $emailPhone)
			->addParameter("emailAddressLine1", $emailAddressLine1)
			->addParameter("emailAddressLine2", $emailAddressLine2)
		;
		
		$remoteResponse = $remoteRequest->remotePostRequest();
		iHomefinderLogger::getInstance()->debugDumpVar($remoteResponse);
		
		return $remoteResponse;
		
	}

	/**
	 * Create register option groups and associated options.
	 * Later use settings_fields in the forms to populate the options.
	 */
	public function registerSettings() {
		//Activation settings
		register_setting(iHomefinderConstants::OPTION_ACTIVATE, iHomefinderConstants::ACTIVATION_TOKEN_OPTION);		
		register_setting(iHomefinderConstants::OPTION_ACTIVATE, iHomefinderConstants::AUTHENTICATION_TOKEN_OPTION);
		
		//Configuration Settings
		register_setting(iHomefinderConstants::OPTION_CONFIG_PAGE, iHomefinderConstants::OPTION_LAYOUT_TYPE);
		register_setting(iHomefinderConstants::OPTION_CONFIG_PAGE, iHomefinderConstants::CSS_OVERRIDE_OPTION);
		register_setting(iHomefinderConstants::OPTION_CONFIG_PAGE, iHomefinderConstants::COLOR_SCHEME_OPTION);

		//Bio Options
		register_setting(iHomefinderConstants::OPTION_GROUP_BIO, iHomefinderConstants::AGENT_PHOTO_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_BIO, iHomefinderConstants::OFFICE_LOGO_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_BIO, iHomefinderConstants::AGENT_TEXT_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_BIO, iHomefinderConstants::AGENT_DISPLAY_TITLE_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_BIO, iHomefinderConstants::AGENT_LICENSE_INFO_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_BIO, iHomefinderConstants::AGENT_DESIGNATIONS_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_BIO, iHomefinderConstants::CONTACT_PHONE_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_BIO, iHomefinderConstants::CONTACT_EMAIL_OPTION);

		//Social Options
		register_setting(iHomefinderConstants::OPTION_GROUP_SOCIAL, iHomefinderConstants::FACEBOOK_URL_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_SOCIAL, iHomefinderConstants::LINKEDIN_URL_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_SOCIAL, iHomefinderConstants::TWITTER_URL_OPTION);

		//Email Display Options
		register_setting(iHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY, iHomefinderConstants::EMAIL_HEADER_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY, iHomefinderConstants::EMAIL_FOOTER_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY, iHomefinderConstants::EMAIL_PHOTO_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY, iHomefinderConstants::EMAIL_LOGO_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY, iHomefinderConstants::EMAIL_NAME_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY, iHomefinderConstants::EMAIL_COMPANY_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY, iHomefinderConstants::EMAIL_ADDRESS_LINE1_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY, iHomefinderConstants::EMAIL_ADDRESS_LINE2_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY, iHomefinderConstants::EMAIL_PHONE_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY, iHomefinderConstants::EMAIL_DISPLAY_TYPE_OPTION);

		//SEO City Links Options
		register_setting(iHomefinderConstants::OPTION_GROUP_SEO_CITY_LINKS, iHomefinderConstants::SEO_CITY_LINKS_SETTINGS);
		register_setting(iHomefinderConstants::OPTION_GROUP_SEO_CITY_LINKS, iHomefinderConstants::SEO_CITY_LINK_WIDTH);

		//Compatibility Check Options
		register_setting(iHomefinderConstants::OPTION_GROUP_COMPATIBILITY_CHECK, iHomefinderConstants::COMPATIBILITY_CHECK_ENABLED);

		//Register Virtual Page related groups and options
		iHomefinderVirtualPageHelper::getInstance()->registerOptions();

	}

	public function addScripts() {
		$pages = array(
			iHomefinderConstants::OPTION_ACTIVATE,
			iHomefinderConstants::OPTION_IDX_CONTROL_PANEL,
			iHomefinderConstants::OPTION_PAGES,
			iHomefinderConstants::OPTION_CONFIG_PAGE,
			iHomefinderConstants::BIO_PAGE,
			iHomefinderConstants::SOCIAL_PAGE,
			iHomefinderConstants::EMAIL_BRANDING_PAGE,
			iHomefinderConstants::COMMUNITY_PAGES,
			iHomefinderConstants::SEO_CITY_LINKS_PAGE
		);
		if(array_key_exists("page", $_GET)) {
			$page = $_GET["page"];
			$foo = array_search($page, $pages);
			if($foo !== false && $foo >= 0) {
				wp_enqueue_script("jquery");
				wp_enqueue_script("jquery-ui-core");
				wp_enqueue_script("jquery-ui-autocomplete", "", array("jquery-ui-widget", "jquery-ui-position"), "1.8.6");
				wp_enqueue_style("thickbox");
				wp_enqueue_script("oe-dashboard", plugins_url("js/dashboard.js", __FILE__), array("jquery", "editor", "media-upload", "thickbox"), iHomefinderConstants::VERSION);
			}
		}
	}
	
}