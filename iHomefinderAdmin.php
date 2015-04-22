<?php

class iHomefinderAdmin {

	private static $instance;
	
	private $iHomefinderNotification = "By registering this plugin you consent to allow downloads of IDX listings that include images, attribution of iHomefinder as the IDX provider and other MLS-specified compliance requirements.";

	private function __construct() {
		
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new iHomefinderAdmin();
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
		if ($pageName != iHomefinderConstants::OPTION_ACTIVATE && !get_option(iHomefinderConstants::AUTHENTICATION_TOKEN_OPTION)) {
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
			&nbsp;&nbsp;&nbsp;Get an unlimited free trial or paid subscription for your MLS</p>
			<?php
		}
		
		if(array_key_exists(iHomefinderConstants::COMPATIBILITY_CHECK_ENABLED, $_REQUEST) && $_REQUEST[iHomefinderConstants::COMPATIBILITY_CHECK_ENABLED] == "false") {
			update_option(iHomefinderConstants::COMPATIBILITY_CHECK_ENABLED, "false");
		}
		
		if(get_option(iHomefinderConstants::COMPATIBILITY_CHECK_ENABLED) != "false") {
		
			$errors = array();
			//Get current wordpress plugins as array
			$plugins = get_plugins();
			
			//check if permalink structure is set
			if (get_option("permalink_structure") == "") {
				$errors[] = "<p><a href='options-permalink.php'>WordPress permalink settings are set as default (Error 404)</a></p>";					
			}
			
			$remoteRequest = new iHomefinderRequestor();
			$remoteRequest
				->addParameter("method", "handleRequest")
				->addParameter("viewType", "json")
				->addParameter("requestType", "compatibility-check")
			;
			
			$contentInfo = $remoteRequest->remoteGetRequest();
			
			if(empty($contentInfo) === false) {
				
				if(iHomefinderLayoutManager::getInstance()->isResponsive() === true) {
					$content = (string) $remoteRequest->getJson($contentInfo);
				} else {
					$content = json_encode($contentInfo);
				}
				
				if(isset($content) === true && $content != "") {
					$compatibility = json_decode($content, true);
					$compatibilityPluginArray=$compatibility["Plugin"];
													
					//loop through plugin array
					foreach ($plugins as $pluginPath => $plugin) {
						//check if plugin is active
						if (is_plugin_active($pluginPath) == true) {
							//get plugin name
							$pluginName = $plugin["Name"];	
							if(array_key_exists($pluginName, $compatibilityPluginArray)) {
								$message = $compatibilityPluginArray[$pluginName];
								if($message != null) {
									$errors[] = "<p><a href='plugins.php?s=" .  urlencode($pluginName) . "'>" . $pluginName . "</a> (" . $message . ")</p>";
								}
							}	
						}
					}
						
					if (function_exists('wp_get_theme')) {
						//get current wordpress theme as string
						$theme = wp_get_theme();	
						$themeName=$theme["Name"];
						$compatibilityThemeArray=$compatibility["Theme"];	
						if(array_key_exists($themeName, $compatibilityThemeArray)) {
							$message = $compatibilityThemeArray[$themeName];
							if($message != null) {
								$errors[] = "<p><a href='themes.php'>" . $themeName . "</a> (" . $message . ")</p>";
							}	
						}
					}
				}
					
				//check error count
				if (count($errors) > 0) {
					?>
					<div class='error'>
						<div style="">
							<h3 style="float: left;"><?php echo count($errors) ?> compatibility issue(s):</h3>
							<form id="<?php echo iHomefinderConstants::COMPATIBILITY_CHECK_ENABLED ?>" style="float: right; margin-top: 5px;" method="post" action="options.php">
								<?php settings_fields(iHomefinderConstants::OPTION_GROUP_COMPATIBILITY_CHECK); ?>
								<input type="hidden" value="false" name="<?php echo iHomefinderConstants::COMPATIBILITY_CHECK_ENABLED ?>" />
								<input class="button-secondary" type="submit" value="Dismiss compatibility warnings" />
							</form>
						</div>
						<div style="clear: both;">
							<?php
							foreach ($errors as $error) {
								echo $error;
							}
							?>
						</div>
					</div>
					<?php
				}
			}
		}
	}

	public function createAdminMenu() {
		$permissions = iHomefinderPermissions::getInstance();
		add_menu_page("Optima Express", "Optima Express", "manage_options", "ihf_idx", array($this, "adminOptionsForm"));
		add_submenu_page("ihf_idx", "Information", "Information", "manage_options", "ihf_idx", array($this, "adminOptionsForm"));
		add_submenu_page("ihf_idx", "Register", "Register", "manage_options", iHomefinderConstants::OPTION_ACTIVATE, array($this, "adminOptionsActivateForm"));
		add_submenu_page("ihf_idx", "IDX Control Panel", "IDX Control Panel", "manage_options", iHomefinderConstants::OPTION_IDX_CONTROL_PANEL, array($this, "adminIdxControlPanelForm"));
		add_submenu_page("ihf_idx", "IDX Pages", "IDX Pages", "manage_options", iHomefinderConstants::OPTION_PAGES, array($this, "adminOptionsPagesForm"));
		add_submenu_page("ihf_idx", "Configuration", "Configuration", "manage_options", iHomefinderConstants::OPTION_CONFIG_PAGE, array($this, "adminConfigurationForm"));
		add_submenu_page("ihf_idx", "Bio Widget", "Bio Widget", "manage_options", iHomefinderConstants::BIO_PAGE, array($this, "bioInformationForm"));
		add_submenu_page("ihf_idx", "Social Widget", "Social Widget", "manage_options", iHomefinderConstants::SOCIAL_PAGE, array($this, "socialInformationForm"));
		add_submenu_page("ihf_idx", "Email Branding", "Email Branding", "manage_options", iHomefinderConstants::EMAIL_BRANDING_PAGE, array($this, "emailDisplayForm"));
		if(iHomefinderPermissions::getInstance()->isCommunityPagesEnabled()) {
			add_submenu_page("ihf_idx", "Community Pages", "Community Pages", "manage_options", iHomefinderConstants::COMMUNITY_PAGES, array($this, "communityPagesForm"));
		}
		if(iHomefinderPermissions::getInstance()->isSeoCityLinksEnabled()) {
			add_submenu_page("ihf_idx", "SEO City Links", "SEO City Links", "manage_options", iHomefinderConstants::SEO_CITY_LINKS_PAGE, array($this, "seoCityLinksForm"));
		}
	}

	public function adminOptionsForm() {
				if (!current_user_can('manage_options'))  {
					wp_die('You do not have sufficient permissions to access this page.');
				}
				?>
				<div class="wrap">
					<h2>Information</h2>
					<h3>Register</h3>
					<p>The Optima Express plugin needs to be registered with iHomefinder. Registration is automatic if you signup for a trial account or purchase a live account from this page. Or, you can enter a registration key that you've received separately.</p>
					<h3>IDX Pages</h3>
					<p>View and configure your Optima Express IDX pages here. Change permalinks, page titles and templates.</p>
					<h3>Configuration</h3>
					<p>This page provides customization features including the ability to override default styles for Optima Express.</p>
					<h3>Bio Widget</h3>
					<p>Setup your bio information.  Upload a photo and insert contact information.</p>
					<h3>Social Widget</h3>
					<p>Enter your social network information.</p>
					<h3>Email Display</h3>
					<p>Customize your email header and footer</p>
					<h3>Community Pages</h3>
					<p>Create custom pages for your communities.  These pages contain a list of properties in the community, SEO friendly URLs and the ability to add custom content.</p>
					<h3>SEO City Links</h3>
					<p>Create SEO links for display in the SEO City Links widget.
				</div>
				<?php
	}

	public function updateAuthenticationToken() {
		$activationToken=get_option(iHomefinderConstants::ACTIVATION_TOKEN_OPTION);
		$this->activateAuthenticationToken($activationToken);
		
	}

	public function activateAuthenticationToken($activationToken, $updateActivationTokenOption = false) {
		if($updateActivationTokenOption) {
			update_option(iHomefinderConstants::ACTIVATION_TOKEN_OPTION, $activationToken);
		}

		if(!empty($activationToken)) {
			$authenticationInfo = $this->activate($activationToken);
			$authenticationToken = "";
			if($authenticationInfo->authenticationToken) {
				$authenticationToken = (string) $authenticationInfo->authenticationToken;
				$permissions = $authenticationInfo->permissions;
				iHomefinderPermissions::getInstance()->initialize($permissions);
				if(!$this->previouslyActivated()) {
					update_option(iHomefinderConstants::IS_ACTIVATED_OPTION,'true');
				}
			}				
			update_option(iHomefinderConstants::AUTHENTICATION_TOKEN_OPTION, $authenticationToken);				
		}
		iHomefinderMenu::getInstance()->updateMenu();
	}
	
	/**
	 * This forces reactivation of the plugin at next site visit.
	 */
	public function deleteAuthenticationToken() {
		delete_option(iHomefinderConstants::AUTHENTICATION_TOKEN_OPTION);
	}
	
	/**
	 * If the authentication token has expired then generate a new authentication token
	 * from the activationToken.
	 */
	public function getAuthenticationToken() {
		$authenticationToken = get_option(iHomefinderConstants::AUTHENTICATION_TOKEN_OPTION);
		return $authenticationToken;
	}

	public function previouslyActivated() {
		return get_option(iHomefinderConstants::IS_ACTIVATED_OPTION);
	}

	private function createOneLink($name, $url, $description) {
		$link = array(
			"link_url" => $url,
			"link_name" => $name,
			"link_description" => $description
		);
		wp_insert_link($link);
	}

	private function activate($activationToken) {
		
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
		
		//Push CSS Override to iHomefinder
		$cssOverride = get_option(iHomefinderConstants::CSS_OVERRIDE_OPTION);
		$cssOverride = urlencode($cssOverride);
		
		//Push layout style to iHomefinder
		$layoutType = iHomefinderLayoutManager::getInstance()->getLayoutType();
		$layoutType = urlencode($layoutType);
		
		//Push color scheme to iHomefinder
		$colorScheme = iHomefinderLayoutManager::getInstance()->getColorScheme();
		$colorScheme = urlencode($colorScheme);
		
		//Push mobile site setting to iHomefinder
		$mobileSiteYn = get_option(iHomefinderConstants::OPTION_MOBILE_SITE_YN);
		
		$emailHeader = iHomefinderAdminEmailDisplay::getInstance()->getHeader();
		$emailHeader = urlencode($emailHeader);

		$emailFooter = iHomefinderAdminEmailDisplay::getInstance()->getFooter();
		$emailFooter = urlencode($emailFooter);
		
		$postData = array(
			"method" => "handleRequest",
			"requestType" => "activate",
			"viewType" => "json",
			"activationToken" => $activationToken,
			"ajaxBaseUrl" => $ajaxBaseUrl,
			"type" => "wordpress",
			"listingSearchResultsUrl" => $listingsSearchResultsUrl,
			"listingSearchFormUrl" => $listingsSearchFormUrl,
			"listingDetailUrl" => $listingDetailUrl,
			"featuredSearchResultsUrl" => $featuredSearchResultsUrl,
			"hotsheetSearchResultsUrl" => $hotsheetSearchResultsUrl,
			"organizerLoginUrl" => $organizerLoginUrl,
			"organizerLogoutUrl" => $organizerLogoutUrl,
			"organizerLoginSubmitUrl" => $organizerLoginSubmitUrl,
			"organizerEditSavedSearchUrl" => $organizerEditSavedSearchUrl,
			"organizerEditSavedSearchSubmitUrl" => $organizerEditSavedSearchSubmitUrl,
			"organizerDeleteSavedSearchSubmitUrl" => $organizerDeleteSavedSearchSubmitUrl,
			"organizerViewSavedSearchUrl" => $organizerViewSavedSearchUrl,
			"organizerViewSavedSearchListUrl" => $organizerViewSavedSearchListUrl,
			"organizerViewSavedListingListUrl" => $organizerViewSavedListingListUrl,
			"organizerDeleteSavedListingUrl" => $organizerDeleteSavedListingUrl,
			"organizerResendConfirmationEmailUrl" => $organizerResendConfirmationEmailUrl,
			"organizerActivateSubscriberUrl" => $organizerActivateSubscriberUrl,
			"organizerSendSubscriberPasswordUrl" => $organizerSendSubscriberPasswordUrl,
			"listingAdvancedSearchFormUrl" => $listingsAdvancedSearchFormUrl,
			"organizerHelpUrl" => $organizerHelpUrl,
			"organizerEditSubscriberUrl" => $organizerEditSubscriberUrl,
			"contactFormUrl" => $contactFormUrl,
			"valuationFormUrl" => $valuationFormUrl,
			"listingSoldDetailUrl" => $listingSoldDetailUrl,
			"openHomeSearchFormUrl" => $openHomeSearchFormUrl,
			"soldFeaturedListingUrl" => $soldFeaturedListingUrl,
			"supplementalListingUrl" => $supplementalListingUrl,
			"listingSearchByAddressResultsUrl" => $listingSearchByAddressResultsUrl,
			"listingSearchByListingIdResultsUrl" => $listingSearchByListingIdResultsUrl,
			"officeListUrl" => $officeListUrl,
			"officeDetailUrl" => $officeDetailUrl,
			"agentBioListUrl" => $agentBioListUrl,
			"agentBioDetailUrl" => $agentBioDetailUrl,
			"mapSearchUrl" => $mapSearchUrl,
			"cssOverride" => $cssOverride,
			"emailHeader" => $emailHeader,
			"emailFooter" => $emailFooter,
			"layoutType" => $layoutType,
			"colorScheme" => $colorScheme,
			"mobileSiteYn" => $mobileSiteYn
		);
		
		$remoteRequest = new iHomefinderRequestor();
		$remoteRequest->addParameters($postData);
		$authenticationInfo = $remoteRequest->remotePostRequest();

		//We need to flush the rewrite rules, if any permalinks have been updated.
		//Only flush in the admin screens, because that is the only point
		//where urls patterns may change.
		if(is_admin()) {
			iHomefinderRewriteRules::getInstance()->flushRules();
		}
		iHomefinderLogger::getInstance()->debugDumpVar($authenticationInfo);
		
		return $authenticationInfo;
	
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

	//Check if an options form has been updated.
	//When new options are updated, the parameter "updated" is set to true
	private function isUpdated() {
		$isUpdated = (array_key_exists("settings-updated", $_REQUEST) && $_REQUEST["settings-updated"] === "true");
		return $isUpdated;
	}

	public function adminOptionsActivateForm() {
		
		?>		
					
		<div class="wrap">
		
		<?php
		if (!current_user_can("manage_options"))  {
			wp_die("You do not have sufficient permissions to access this page.");
		}
		
		$activationToken = null;
		if(array_key_exists("reg", $_REQUEST)) {
			$activationToken = $_REQUEST["reg"];
		}
		
		if($activationToken) {
			update_option(iHomefinderConstants::ACTIVATION_TOKEN_OPTION, $activationToken);
			$this->updateAuthenticationToken();
			?>
			<h2>Thanks For Signing Up</h2>
			<div class="updated">
				<p>Your Optima Express plugin has been registered.</p>
			</div>
			<p>You will receive an email from us with IDX paperwork for your MLS. Please complete the paperwork and return it to iHomefinder promptly. Listings from your MLS will appear in Optima Express as soon as your MLS approves your IDX paperwork.</p>
			<?php

		} elseif($this->isUpdated()) {
			//call function here to pass the activation key to ihf and get
			//an authentication token
			$this->updateAuthenticationToken();
		}
		
		$section = null;
		if(array_key_exists("section", $_REQUEST)) {
			$section = $_REQUEST["section"];
		}
		
		if($section === "enter-reg-key") {
			
			?>
			
			<h2>Add Registration Key</h2>
			
			<?php
			if(get_option(iHomefinderConstants::ACTIVATION_TOKEN_OPTION) == "") {
				?>
				<div class="error">
					<p>Add your Registration Key and click "Save Changes" to get started with Optima Express.</p>
				</div>
				<?php
			} elseif(get_option(iHomefinderConstants::AUTHENTICATION_TOKEN_OPTION) != "") {
				?>
				<div class="updated">
					<p>Your Optima Express plugin has been registered.</p>
				</div>
				<?php
			} else {
				?>
				<div class="error">
					<p>Incorrect Registration Key.</p>
				</div>
				<?php
			}
			?>
			
			<form method="post" action="options.php">
			<?php settings_fields(iHomefinderConstants::OPTION_ACTIVATE); ?>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						<strong>Registration Key:</strong>
					</th>
					<td>
						<input type="text" size="45" name="<?php echo iHomefinderConstants::ACTIVATION_TOKEN_OPTION ?>" value="<?php echo get_option(iHomefinderConstants::ACTIVATION_TOKEN_OPTION); ?>" />
					</td>
				</tr>
				<tr valign="top">
					<td colspan="2">
						<?php echo $this->iHomefinderNotification; ?>
					</td>
				</tr>
			</table>
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e("Save Changes") ?>" />
			</p>
			</form>
			
		<?php
			
		} elseif($section === "free-trial") {
			?>
			<h2>Free Trial Sign-Up</h2>
			<p>
				<?php echo $this->iHomefinderNotification; ?>
			</p>
			<?php
			
			$firstName = $_POST["firstName"];
			$lastName = $_POST["lastName"];
			$phoneNumber = $_POST["phoneNumber"];
			$email = $_POST["email"];
			$accountType = $_POST["accountType"];
			
			$errors = array();
			
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$errors[] = "<p>Email address is not valid.</p>";
			}
							
			if(empty($accountType)) {
				$errors[] = "<p>Select type of trial account.</p>";
			}
			
			if(count($errors) == 0) {
				
				if($accountType == "Broker") {
					$companyname = "Many Homes Realty";
				} else {
					$companyname = "Jamie Agent";
				}
				
				$password = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"), 0, 6);
				
				$params = array(
					"plugin" => "true",
					"clientfirstname" => $firstName,
					"clientlastname" => $lastName,
					"companyname" => $companyname,
					"companyemail" => $email,
					"password" => $password,
					"companyphone" => $phoneNumber,
					"companyaddress" => "123 Main St.",
					"companycity" => "Anytown",
					"companystate" => "CA",
					"companyzip" => "12345",
					"account_type" => $accountType,
					"product" => "Optima Express",
					"lead_source" => "Plugin",
					"lead_source_description" => "Optima Express Trial Form",
					"ad_code" => "",
					"ip_address" => $_SERVER["REMOTE_ADDR"],
				);
				
				$requestUrl = "http://www.ihomefinder.com/store/optima-express-trial.php";
				
				set_time_limit(90);
				$requestArgs = array("timeout" => "90", "body" => $params);
				$response = wp_remote_post($requestUrl, $requestArgs);
				if(!is_wp_error($response)) {
					$responseBody = wp_remote_retrieve_body($response);
					$responseBody = json_decode($responseBody, true);
					
					$clientId = $responseBody["clientID"];
					$regKey = $responseBody["regKey"];
					$username = $responseBody["username"];
					$password = $responseBody["password"];
					
					update_option(iHomefinderConstants::ACTIVATION_TOKEN_OPTION, $regKey);
					$this->updateAuthenticationToken();
					
					?>
					<div class="updated">
						<p>Your Optima Express plugin has been registered.</p>
					</div>
					<p>Thank you for evaluating Optima Express!</p>
					<p>Your trial account uses sample listing data from Northern California. For search and listings in your MLS, <a href="http://www.ihomefinder.com/store/convert.php?cid=<?php echo $clientId ?>" target="_blank">upgrade to a paid account</a>.</p>
					<p>Visit our <a href="http://support.ihomefinder.com/index.php?/Knowledgebase/List/Index/23/optima-express-responsive/" target="_blank">knowledge base</a> for assistance setting up IDX on your site.</p>
					<p>Don't hesitate to <a href="http://www.ihomefinder.com/contact-us/" target="_blank">contact us</a> if you have any questions.</p>
					
					<?php
					
				} else {
					?>
					<div class="error">
						<p>Error creating your account.</p>
					</div>
					<?php
				}
				
			} else {
			
				if($_POST) {
					echo "<div class='error'>";
					foreach ($errors as $error) {
						echo $error;
					}
					echo "</div>";
				}
				
				?>
				<style type="text/css">
					table {
						width: 300px;
					}
					tr td:nth-child(1) {
						width: 150px;
					}
					input,
					select {
						display: block;
						width: 250px;
					}
					label {
						font-weight: bold;
					}
				</style>
				<form method="post">
					<table class="form-table">
						<tr>
							<td>
								<label for="email">First Name:</label>
							</td>
							<td>
								<input id="email" name="firstName" type="text" required="required" value="<?php echo $firstName ?>" />
							</td>
						</tr>
						<tr>
							<td>
								<label for="email">Last Name:</label>
							</td>
							<td>
								<input id="email" name="lastName" type="text" required="required" value="<?php echo $lastName ?>" />
							</td>
						</tr>
						<tr>
							<td>
								<label for="email">Phone Number:</label>
							</td>
							<td>
								<input id="email" name="phoneNumber" type="text" required="required" value="<?php echo $phoneNumber ?>" />
							</td>
						</tr>
						<tr>
							<td>
								<label for="email">Email:</label>
							</td>
							<td>
								<input id="email" name="email" type="email" required="required" placeholder="Your email will be your username" value="<?php echo $email ?>" />
							</td>
						</tr>
						<tr>
							<td>
								<label>Account Type:</label>
							</td>
							<td>
								<?php
								if($accountType == "Agent") {
									$agentSelected = "selected=\"selected\"";
								}
								if($accountType == "Broker") {
									$brokerSelected = "selected=\"selected\"";
								}									
								?>
								<select name="accountType">
									<option>Select One</option>
									<option value="Agent" <?php echo $agentSelected ?>>Individual Agent</option>
									<option value="Broker" <?php echo $brokerSelected ?>>Office with Multiple Agents</option>
								</select>
							</td>
						</tr>
					</table>
					<p class="submit">
						<button type="submit" class="button-primary">Start Trial</button>
						<span>&nbsp;&nbsp;&nbsp;Creating your trial account can take up to 60 seconds to complete. Please do not refresh the page or press the back button.</span>
					</p>
				</form>
				<?php
			
			}
			
		} else {
			$authenticationToken = get_option(iHomefinderConstants::AUTHENTICATION_TOKEN_OPTION);
			if(empty($authenticationToken)) {
				?>
				<style type="text/css">
					.button-large-ihf {
						height: 54px !important;
						text-align: center;
						font: 14px arial !important;
						padding-top: 10px !important;
						margin-right: 15px !important;
					}
				</style>
				<h2>Register Optima Express</h2>
				<br />
				<a href="admin.php?page=<?php echo iHomefinderConstants::OPTION_ACTIVATE ?>&section=enter-reg-key">I already have a registration key</a>
				<br />
				<br />
				<a href="admin.php?page=<?php echo iHomefinderConstants::OPTION_ACTIVATE ?>&section=free-trial" class="button button-primary button-large-ihf" >Get a Free<br />30-Day Trial</a>
				<a href="http://www.ihomefinder.com/products/optima-express/optima-express-agent-pricing/?plugin=true&redirectURL=<?php echo urlencode ('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" class="button button-primary button-large-ihf">Sign Up for a<br />Paid Account</a>
				<br />
				<br />
				<p>Optima Express from iHomefinder adds MLS/IDX search and listings directly into your WordPress site.</p>
				<p>A free trial account uses sample IDX listings from Northern California.</p>
				<p>Signing up for a paid account provides access to listings in your MLS&reg; System and full support from iHomefinder. Plans start at $39.95 per month. You must be a member of an MLS to qualify for IDX service. <a target="_blank" href="http://www.ihomefinder.com/mls-coverage/">Learn More</a></p>
				<p>
					<?php echo $this->iHomefinderNotification; ?>
				</p>
				<?php
			} elseif($activationToken == false) {
				?>
				<h2>Unregister Optima Express</h2>
				<p>Optima Express is currently registered. Clicking the below button will unregister the IDX plugin.<p>
				<form method="post" action="options.php">
					<?php settings_fields(iHomefinderConstants::OPTION_ACTIVATE); ?>
					<input type="hidden" name="<?php echo iHomefinderConstants::ACTIVATION_TOKEN_OPTION ?>" value="" />
					<input type="hidden" name="<?php echo iHomefinderConstants::AUTHENTICATION_TOKEN_OPTION ?>" value="" />
					<p class="submit">
						<input type="submit" class="button-primary" value="<?php _e("Unregister") ?>" onclick="return confirm('Are you sure you want to unregister Optima Express?');" />
					</p>
				</form>
				<form method="post" action="options.php" name="refreshRegistration">
					<?php settings_fields(iHomefinderConstants::OPTION_ACTIVATE); ?>
					<input type="hidden" name="<?php echo iHomefinderConstants::ACTIVATION_TOKEN_OPTION ?>" value="<?php echo get_option(iHomefinderConstants::ACTIVATION_TOKEN_OPTION); ?>" />
					<a href="#" onclick="document.refreshRegistration.submit();">Refresh Registration</a>
				</form>
				<?php
				
			}
		}
		
		?>
		
		</div>			
	
		<?php			
		
	}
	
	public function adminIdxControlPanelForm() {
		if(get_option(iHomefinderConstants::AUTHENTICATION_TOKEN_OPTION) != "") {
			?>
			
			<h2>Your IDX Control Panel will open in a new window.</h2>
			<p>If a new window does not open, please enable pop-ups for this site.</p>
			<script type="text/javascript">
				window.open("<?php echo iHomefinderConstants::CONTROL_PANEL_EXTERNAL_URL; ?>/z.cfm?w=<?php echo get_option(iHomefinderConstants::ACTIVATION_TOKEN_OPTION) ?>");
			</script>
			<?php
		}
	}

	public function addScripts() {
		//Used for the Bio Page for image uploads
		if (isset($_GET["page"]) && ($_GET["page"] == iHomefinderConstants::BIO_PAGE || $_GET["page"] == iHomefinderConstants::EMAIL_BRANDING_PAGE)) {
			wp_enqueue_script("jquery"); // include jQuery
			wp_register_script("bioInformation", plugins_url("/optima-express/js/bioInformation.js"), array("jquery","editor","media-upload","thickbox"));
			wp_enqueue_style("thickbox");
			wp_enqueue_script("bioInformation");  // include script.js
		}
	}

	public function bioInformationForm() {

		?>
		<div class="wrap">
		<h2>Bio Widget Setup</h2>

		<p/>
		Configure and edit the Optima Express Bio Widget display here.
		<p/>

		<form method="post" action="options.php">
			<?php settings_fields(iHomefinderConstants::OPTION_GROUP_BIO); ?>

			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e("Save Changes") ?>" />
			</p>

			<h3>Agent Photo</h3>
			<?php if(get_option(iHomefinderConstants::AGENT_PHOTO_OPTION)) {
				?>
				<img id="ihf_upload_agent_photo_image" src="<?php echo(get_option(iHomefinderConstants::AGENT_PHOTO_OPTION))?>"
					<?php if(!get_option(iHomefinderConstants::AGENT_PHOTO_OPTION)) {echo(" style='display:none'");}?>/><br />
				<?php
			}
			?>
			<input id="ihf_upload_agent_photo" type="text" size="36" name="<?php echo(iHomefinderConstants::AGENT_PHOTO_OPTION)?>" value="<?php echo(get_option(iHomefinderConstants::AGENT_PHOTO_OPTION))?>" />
			<input id="ihf_upload_agent_photo_button" type="button" value="Upload Agent Photo" class="button-secondary"/>
			<br />
			Enter an image URL or use an image from the Media Library
			<br /><br /><br />

			<div style="float:left;width:100px;">Display Name:</div>
			<input type="text" size="36" name="<?php echo(iHomefinderConstants::AGENT_DISPLAY_TITLE_OPTION)?>" value="<?php echo(get_option(iHomefinderConstants::AGENT_DISPLAY_TITLE_OPTION))?>" />
			<div style="clear:both;"></div>


			<div style="float:left;width:100px;">Contact Phone:</div>
			<input type="text" size="36" name="<?php echo(iHomefinderConstants::CONTACT_PHONE_OPTION)?>" value="<?php echo(get_option(iHomefinderConstants::CONTACT_PHONE_OPTION))?>" />
			<div style="clear:both;"></div>

			<div style="float:left;width:100px;">Contact Email:</div>
			<input type="text" size="36" name="<?php echo(iHomefinderConstants::CONTACT_EMAIL_OPTION)?>" value="<?php echo(get_option(iHomefinderConstants::CONTACT_EMAIL_OPTION))?>" />
			<div style="clear:both;"></div>

			<div style="float:left;width:100px;">Designations:</div>
			<input type="text" size="36" name="<?php echo(iHomefinderConstants::AGENT_DESIGNATIONS_OPTION)?>" value="<?php echo(get_option(iHomefinderConstants::AGENT_DESIGNATIONS_OPTION))?>" />
			<div style="clear:both;"></div>

			<div style="float:left;width:100px;">License Info:</div>
			<input type="text" size="36" name="<?php echo(iHomefinderConstants::AGENT_LICENSE_INFO_OPTION)?>" value="<?php echo(get_option(iHomefinderConstants::AGENT_LICENSE_INFO_OPTION))?>" />
			<div style="clear:both;"></div>
			
			<br /><br />

			<h3>Agent Bio Text</h3>
			<?php
				$agent_bio_editor_settings =  array (
					"textarea_rows" => 15,
					"media_buttons" => true,
					"teeny"         => true,
					"tinymce"       => true,
					"textarea_name" => iHomefinderConstants::AGENT_TEXT_OPTION
				);
				wp_editor(get_option(iHomefinderConstants::AGENT_TEXT_OPTION), "agentbiotextid", $agent_bio_editor_settings);

			?>
			<br /><br />

			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e("Save Changes") ?>" />
			</p>

		</form>
		</div>

		<?php
	}

	public function socialInformationForm() {
		?>
		<div class="wrap">
		<h2>Social Widget Setup</h2>

		Enter your social media addresses for the Optima Express Social Media Widget.
		<p/>

		<form method="post" action="options.php">
			<?php settings_fields(iHomefinderConstants::OPTION_GROUP_SOCIAL); ?>

			<h3>Facebook</h3>
			http://www.facebook.com/
			<input type="text" size="36" name="<?php echo(iHomefinderConstants::FACEBOOK_URL_OPTION)?>" value="<?php echo(get_option(iHomefinderConstants::FACEBOOK_URL_OPTION))?>" />

			<br />
			<h3>LinkedIn</h3>
			http://www.linkedin.com/
			<input type="text" size="36" name="<?php echo(iHomefinderConstants::LINKEDIN_URL_OPTION)?>" value="<?php echo(get_option(iHomefinderConstants::LINKEDIN_URL_OPTION))?>" />

			<br />
			<h3>Twitter</h3>
			http://www.twitter.com/
			<input type="text" size="36" name="<?php echo(iHomefinderConstants::TWITTER_URL_OPTION)?>" value="<?php echo(get_option(iHomefinderConstants::TWITTER_URL_OPTION))?>" />

			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e("Save Changes") ?>" />
			</p>

		</form>
		</div>

		<?php
	}

	public function seoCityLinksForm() {
		$formData = iHomefinderSearchFormFieldsUtility::getInstance()->getFormData();
		$propertyTypesList=$formData->getPropertyTypesList();
		$cityZipList=$formData->getCityZipList();
		$cityZipListJson=json_encode($cityZipList);

		wp_enqueue_script("jquery");
		wp_enqueue_script("jquery-ui-core");
		wp_enqueue_script("jquery-ui-autocomplete", "", array("jquery-ui-widget", "jquery-ui-position"), "1.8.6");
		wp_enqueue_style("jquery-ui-autocomplete", plugins_url("css/jquery-ui-1.8.18.custom.css", __FILE__));
		?>
		<script type="text/javascript">
		function ihfRemoveSeoLink(button) {
			//debugger;
			var theButton = jQuery(button);
			var theForm = theButton.closest("form");
			theButton.parent().remove();
			theForm.submit();
		}
		jQuery(document).ready(function() {
			jQuery("input#ihfSeoLinksAutoComplete").focus(function() {jQuery("input#ihfSeoLinksAutoComplete").val("");});
			jQuery("input#ihfSeoLinksAutoComplete").autocomplete({
				autoFocus: true,
				source: function(request,response) {
					var data=<?php echo($cityZipListJson);?>;
					var searchTerm=request.term;
					searchTerm=searchTerm.toLowerCase();
					var results=new Array();
					for(var i=0; i<data.length;i++) {
						//debugger;
						var oneTerm=data[i];
						//appending '' converts numbers to strings for the indexOf function call
						var value=oneTerm.value + "";
						value=value.toLowerCase();
						if(value && value != null && value.indexOf(searchTerm) == 0) {
							results.push(oneTerm);
						}
					}
					response(results);
				},
				select: function(event, ui) {
					//When an item is selected, set the text value for the link
					jQuery("#ihfSeoLinksText").val(ui.item.label);
				}
			});

		});
		</script>
		<div class="wrap">
			<h2>SEO City Links Setup</h2>
			<p>Add city links for display in the SEO City Links widget.<p/>
			<form method="post" action="options.php" id="ihfSeoLinksForm">
				<?php settings_fields(iHomefinderConstants::OPTION_GROUP_SEO_CITY_LINKS); ?>
				<div style="float:left; padding-right:15px;">
					<div>
						<label>Location:</label>
					</div>
					<input type="text" id="ihfSeoLinksAutoComplete"
						name="<?php echo iHomefinderConstants::SEO_CITY_LINKS_SETTINGS . '[0][' . iHomefinderConstants::SEO_CITY_LINKS_CITY_ZIP . ']'?>"
						placeholder="Enter City - OR - Postal Code"
						style="width: 220px;"
						autocomplete="off"
						/>
				</div>
				<div style="float:left; padding-right:15px;">
					<div>
						<label>Property Type:</label>
					</div>
					<select
						name="<?php echo iHomefinderConstants::SEO_CITY_LINKS_SETTINGS . '[0][' . iHomefinderConstants::SEO_CITY_LINKS_PROPERTY_TYPE . ']' ?>"
						style="width: 220px;"
					>
						<?php
						foreach ($propertyTypesList as $i => $value) {
							echo "<option value='" . $propertyTypesList[$i]->propertyTypeCode . "'>" . $propertyTypesList[$i]->displayName . "</option>";
						}
						?>
					</select>
				</div>
				<div style="clear:both;"></div>
				<div style="float:left; padding-right:15px;">
					<div>
						<label>Min Price:</label>
					</div>
					<div>
						<input
							name="<?php echo iHomefinderConstants::SEO_CITY_LINKS_SETTINGS . '[0][' . iHomefinderConstants::SEO_CITY_LINKS_MIN_PRICE . ']'?>"
							type="number"
							style="width: 220px;"
						/>
					</div>
				</div>
				<div style="float:left; padding-right:15px;">
					<div>
						<label>Max Price:</label>
					</div>
					<div>
						<input
							name="<?php echo iHomefinderConstants::SEO_CITY_LINKS_SETTINGS . '[0][' . iHomefinderConstants::SEO_CITY_LINKS_MAX_PRICE . ']'?>"
							type="number"
							style="width: 220px;"
						/>
					</div>
				</div>
				<div style="clear:both;"></div>
				<div>
					<label>Link Text:</label>
				</div>
				<div>
					<input
						id="ihfSeoLinksText" name="<?php echo iHomefinderConstants::SEO_CITY_LINKS_SETTINGS . '[0][' . iHomefinderConstants::SEO_CITY_LINKS_TEXT. ']'?>"
						type="text"
						style="width: 458px;"
						/>
				</div>
				<p class="submit">
					<button type="submit" class="button-primary">Save Changes</button>
				</p>
				<p>The following links will display in the SEO City Links widget.  Click the &#x2715; to remove an entry.</p>
				<?php
					$seoCityLinksSettings = get_option(iHomefinderConstants::SEO_CITY_LINKS_SETTINGS);
					if($seoCityLinksSettings && is_array($seoCityLinksSettings)) {
						sort($seoCityLinksSettings);
						//save sorted array
						update_option(iHomefinderConstants::SEO_CITY_LINKS_SETTINGS, $seoCityLinksSettings);
						foreach($seoCityLinksSettings as $i => $value) {
							$index = $value[iHomefinderConstants::SEO_CITY_LINKS_TEXT];
							if($index) {
								?>
								<div style="margin-bottom: 6px;">
									<button class="button-secondary" onclick="ihfRemoveSeoLink(this);">
										&#x2715;&nbsp;&nbsp;&nbsp;
										<?php echo $index ?>
									</button>
									<input type="hidden" name="<?php echo iHomefinderConstants::SEO_CITY_LINKS_SETTINGS ?>[<?php echo $index ?>][<?php echo iHomefinderConstants::SEO_CITY_LINKS_TEXT ?>]" value="<?php echo $value[iHomefinderConstants::SEO_CITY_LINKS_TEXT] ?>">
									<input type="hidden" name="<?php echo iHomefinderConstants::SEO_CITY_LINKS_SETTINGS ?>[<?php echo $index ?>][<?php echo iHomefinderConstants::SEO_CITY_LINKS_CITY_ZIP ?>]" value="<?php echo $value[iHomefinderConstants::SEO_CITY_LINKS_CITY_ZIP] ?>">
									<input type="hidden" name="<?php echo iHomefinderConstants::SEO_CITY_LINKS_SETTINGS ?>[<?php echo $index ?>][<?php echo iHomefinderConstants::SEO_CITY_LINKS_PROPERTY_TYPE ?>]" value="<?php echo $value[iHomefinderConstants::SEO_CITY_LINKS_PROPERTY_TYPE] ?>">
									<input type="hidden" name="<?php echo iHomefinderConstants::SEO_CITY_LINKS_SETTINGS ?>[<?php echo $index ?>][<?php echo iHomefinderConstants::SEO_CITY_LINKS_MIN_PRICE ?>]" value="<?php echo $value[iHomefinderConstants::SEO_CITY_LINKS_MIN_PRICE] ?>">
									<input type="hidden" name="<?php echo iHomefinderConstants::SEO_CITY_LINKS_SETTINGS ?>[<?php echo $index ?>][<?php echo iHomefinderConstants::SEO_CITY_LINKS_MAX_PRICE ?>]" value="<?php echo $value[iHomefinderConstants::SEO_CITY_LINKS_TEXT] ?>">
								</div>
								<?php
							}
						}
					}
	
				?>
			</form>
		</div>
		<?php
	}

	public function emailDisplayForm() {

		$emailDisplayType=get_option(iHomefinderConstants::EMAIL_DISPLAY_TYPE_OPTION);
		//On Update, push the CSS_OVERRIDE_OPTION to iHomefinder
		if($this->isUpdated()) {
			iHomefinderAdminEmailDisplay::getInstance()->setHeaderAndFooter();
			//call function here to pass the activation key to ihf and update Email Display Information
			$this->updateAuthenticationToken();
		}

		?>
		<div class="wrap">
		<h2>Email Branding</h2>

		<p/>
		Add branding to the emails sent to leads by choosing an option below. Information saved here will overwrite branding entered in the Control Panel.
		<p/>

		<form method="post" action="options.php">
			<?php settings_fields(iHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY); ?>
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e("Save Changes") ?>" />
			</p>
			<?php
				//Check if we support default display which uses the logo and photo
				//previously uploaded.
				if(iHomefinderAdminEmailDisplay::getInstance()->includeDefaultDisplay()) {
					echo("Default Logo " . iHomefinderAdminEmailDisplay::getInstance()->getDefaultLogo());
				?>
				
				<input type="radio" name="<?php echo(iHomefinderConstants::EMAIL_DISPLAY_TYPE_OPTION)?>"
					<?php if(iHomefinderAdminEmailDisplay::EMAIL_DISPLAY_TYPE_DEFAULT_VALUE == $emailDisplayType) {echo(" checked ");}?>
					value="<?php echo(iHomefinderAdminEmailDisplay::EMAIL_DISPLAY_TYPE_DEFAULT_VALUE)?>"/>Use Agent Bio photo & Header logo<br />
				<p/>
				<?php } ?>

			<p/>

			<input type="radio" name="<?php echo(iHomefinderConstants::EMAIL_DISPLAY_TYPE_OPTION)?>"
				<?php if(iHomefinderAdminEmailDisplay::EMAIL_DISPLAY_TYPE_CUSTOM_IMAGES_VALUE == $emailDisplayType || empty($emailDisplayType)) {echo(" checked ");}?>
				value="<?php echo(iHomefinderAdminEmailDisplay::EMAIL_DISPLAY_TYPE_CUSTOM_IMAGES_VALUE)?>"/>&nbsp;Basic Branding<br />
			<p/>
			Add the logo, photo and business information you would like displayed in your email branding.

			<h3>Agent Photo</h3>
			<?php if(get_option(iHomefinderConstants::EMAIL_PHOTO_OPTION)) {
				?>
				<img id="ihf_upload_agent_photo_image" src="<?php echo(get_option(iHomefinderConstants::EMAIL_PHOTO_OPTION))?>"
					<?php if(!get_option(iHomefinderConstants::EMAIL_PHOTO_OPTION)) {echo(" style='display:none'");}?>/><br />
				<?php
			}
			?>
			<input id="ihf_upload_agent_photo" type="text" size="36" name="<?php echo(iHomefinderConstants::EMAIL_PHOTO_OPTION)?>" value="<?php echo(get_option(iHomefinderConstants::EMAIL_PHOTO_OPTION))?>" />
			<input id="ihf_upload_agent_photo_button" type="button" value="Upload Agent Photo" class="button-secondary"/>
			<br />
			Enter an image URL or use an image from the Media Library
			<br /><br />

			<h3>Logo</h3>
			<?php if(get_option(iHomefinderConstants::EMAIL_LOGO_OPTION)) {
				?>
				<img id="ihf_upload_email_logo_image" src="<?php echo(get_option(iHomefinderConstants::EMAIL_LOGO_OPTION))?>"
					<?php if(!get_option(iHomefinderConstants::EMAIL_LOGO_OPTION)) {echo(" style='display:none'");}?>/><br />
				<?php
			}
			?>
			<input id="ihf_upload_email_logo" type="text" size="36" name="<?php echo(iHomefinderConstants::EMAIL_LOGO_OPTION)?>" value="<?php echo(get_option(iHomefinderConstants::EMAIL_LOGO_OPTION))?>" />
			<input id="ihf_upload_email_logo_button" type="button" value="Upload Logo" class="button-secondary"/>
			<br />
			Enter an image URL or use an image from the Media Library
			<br /><br />
			
			<h3>Business Information</h3>
			<div style="float:left;width:320px;">
				<div style="float:left;width:90px;font-family: sans-serif;font-size: 12px;">Name:</div>
				<input type="text" size="36" name="<?php echo(iHomefinderConstants::EMAIL_NAME_OPTION)?>" value="<?php echo(get_option(iHomefinderConstants::EMAIL_NAME_OPTION))?>" />
			</div>
			<div style="float:left;width:320px;">
				<div style="float:left;width:90px;font-family: sans-serif;font-size: 12px;"">Company:</div>
				<input type="text" size="36" name="<?php echo(iHomefinderConstants::EMAIL_COMPANY_OPTION)?>" value="<?php echo(get_option(iHomefinderConstants::EMAIL_COMPANY_OPTION))?>" />
			</div>
			<div style="clear:both"></div>	

			<div style="float:left;width:320px;">
				<div style="float:left;width:90px;font-family: sans-serif;font-size: 12px;"">Address Line 1:</div>
				<input type="text" size="36" name="<?php echo(iHomefinderConstants::EMAIL_ADDRESS_LINE1_OPTION)?>" value="<?php echo(get_option(iHomefinderConstants::EMAIL_ADDRESS_LINE1_OPTION))?>" />
			</div>
			<div style="float:left;width:320px;">
				<div style="float:left;width:90px;font-family: sans-serif;font-size: 12px;"">Address Line 2:</div>
				<input type="text" size="36" name="<?php echo(iHomefinderConstants::EMAIL_ADDRESS_LINE2_OPTION)?>" value="<?php echo(get_option(iHomefinderConstants::EMAIL_ADDRESS_LINE2_OPTION))?>" />
			</div>		    	
			
			<div style="clear:both"></div>	
			
			<div style="float:left;width:320px;">
				<div style="float:left;width:90px;font-family: sans-serif;font-size: 12px;"">Phone:</div>
				<input type="text" size="36" name="<?php echo(iHomefinderConstants::EMAIL_PHONE_OPTION)?>" value="<?php echo(get_option(iHomefinderConstants::EMAIL_PHONE_OPTION))?>" />
			</div>

			
			<div style="clear:both"></div>	 	
				
			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e("Save Changes") ?>" />
			</p>
										
			<br />
			<input type="radio" name="<?php echo(iHomefinderConstants::EMAIL_DISPLAY_TYPE_OPTION)?>"
				<?php if(iHomefinderAdminEmailDisplay::EMAIL_DISPLAY_TYPE_CUSTOM_HTML_VALUE == $emailDisplayType) {echo(" checked ");}?>
				value="<?php echo(iHomefinderAdminEmailDisplay::EMAIL_DISPLAY_TYPE_CUSTOM_HTML_VALUE)?>">&nbsp;Custom HTML
			<br />
			
			<p/>
			Insert custom HTML for your email header and footer.
			
			<h3>Email Header</h3>
			<?php
				$email_header_editor_settings =  array (
					"textarea_rows" => 15,
					"media_buttons" => true,
					"teeny" => true,
					"tinymce" => true,
					"textarea_name" => iHomefinderConstants::EMAIL_HEADER_OPTION
				);
				$emailHeaderContent = "";
				if($emailDisplayType == iHomefinderAdminEmailDisplay::EMAIL_DISPLAY_TYPE_CUSTOM_HTML_VALUE) {
					$emailHeaderContent=get_option(iHomefinderConstants::EMAIL_HEADER_OPTION);
				}
				wp_editor($emailHeaderContent, "emailheaderid", $email_header_editor_settings);
			?>

			<br />
			<h3>Email Footer</h3>
			<?php
				$email_footer_editor_settings =  array (
					"textarea_rows" => 15,
					"media_buttons" => true,
					"teeny"  => true,
					"tinymce" => true,
					"textarea_name" => iHomefinderConstants::EMAIL_FOOTER_OPTION
				);
				$emailFooterContent="";
				if($emailDisplayType == iHomefinderAdminEmailDisplay::EMAIL_DISPLAY_TYPE_CUSTOM_HTML_VALUE) {
					$emailFooterContent=get_option(iHomefinderConstants::EMAIL_FOOTER_OPTION);
				}
				wp_editor($emailFooterContent, "emailfooterid", $email_footer_editor_settings);
			?>

			<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e("Save Changes") ?>" />
			</p>

		</form>
		</div>

		<?php
	}

	public function adminConfigurationForm() {
		if (!current_user_can("manage_options"))  {
			wp_die("You do not have sufficient permissions to access this page.");
		}

		//On Update, push the CSS_OVERRIDE_OPTION to iHomefinder
		if($this->isUpdated()) {
			//call function here to pass the activation key to ihf and update the CSS Override value
			$this->updateAuthenticationToken();
		}
		?>

		<div class="wrap">
			<?php $responsive=iHomefinderLayoutManager::getInstance()->isResponsive(); ?>
			<h2>Configuration</h2>
			<form method="post" action="options.php">
				<?php settings_fields(iHomefinderConstants::OPTION_CONFIG_PAGE); ?>
				<table class="form-table">
					<?php if(!iHomefinderPermissions::getInstance()->isOmnipressSite()) {?>
					<tr valign="top">
						<th scope="row">Layout Style</th>
						<td>
							<select name="<?php echo iHomefinderConstants::OPTION_LAYOUT_TYPE ?>">
								<option value="<?php echo iHomefinderConstants::OPTION_LAYOUT_TYPE_RESPONSIVE ?>" <?php if($responsive) {?>selected <?php }?>>Responsive</option>
								<option value="<?php echo iHomefinderConstants::OPTION_LAYOUT_TYPE_LEGACY ?>" <?php if(!$responsive) {?>selected <?php }?>>Fixed-width</option>
							</select>
						</td>
					</tr>
					<?php } else { ?>
						<input type="hidden" name="<?php echo iHomefinderConstants::OPTION_LAYOUT_TYPE ?>" value="<?php echo get_option(iHomefinderConstants::OPTION_LAYOUT_TYPE) ?>" />
					<?php } ?>
					<?php if(iHomefinderLayoutManager::getInstance()->supportsColorScheme()) { ?>
					<tr valign="top">
						<th scope="row">Button Color</th>
						<td>
							<?php $colorScheme=get_option(iHomefinderConstants::COLOR_SCHEME_OPTION) ?>
							<select name="<?php echo iHomefinderConstants::COLOR_SCHEME_OPTION ?>">
								<option value="gray" <?php if($colorScheme=="gray") {?>selected <?php }?>>Gray</option>
								<option value="red" <?php if($colorScheme=="red") {?>selected <?php }?>>Red</option>
								<option value="green" <?php if($colorScheme=="green") {?>selected <?php }?>>Green</option>
								<option value="orange" <?php if($colorScheme=="orange") {?>selected <?php }?>>Orange</option>
								<option value="blue" <?php if($colorScheme=="blue") {?>selected <?php }?>>Blue</option>
								<option value="light_blue" <?php if($colorScheme=="light_blue") {?>selected <?php }?>>Light Blue</option>
								<option value="blue_gradient" <?php if($colorScheme=="blue_gradient") {?>selected <?php }?>>Blue Gradient</option>
							</select>
						</td>
					</tr>
					<?php } ?>
					<tr valign="top">
						<th scope="row">CSS Override</th>
						<td>
							<p>To redefine an Optima Express style, paste the edited style below.</p>
							<textarea name="<?php echo iHomefinderConstants::CSS_OVERRIDE_OPTION ?>" style="width: 100%; height: 300px; "><?php echo get_option(iHomefinderConstants::CSS_OVERRIDE_OPTION); ?></textarea>
						</td>
					</tr>
				</table>
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e("Save Changes") ?>" />
				</p>
			</form>
		</div>
		<?php
	}

	private function updateCommunityPages($title, $cityZip, $propertyType, $bed, $bath, $minPrice, $maxPrice) {
		$errorMessage = null;
		if($cityZip == null || $cityZip == "") {
			$errorMessage .= "Please select a location<br />";
		}
		if($title == null || $title == "") {
			$errorMessage .=  "Please enter a title";
		}
		if(empty($errorMessage)) {
			$shortCode=iHomefinderShortcodeDispatcher::getInstance()->buildSearchResultsShortCode($cityZip, $propertyType, $bed, $bath, $minPrice, $maxPrice);
			$post = array(
			  "comment_status" => "closed" ,// "closed" means no comments.
			  "ping_status" => "closed", // "closed" means pingbacks or trackbacks turned off
			  "post_content" =>  $shortCode, //The full text of the post.
			  "post_name" => $title, // The name (slug) for your post
			  "post_status" => "publish",  //Set the status of the new post.
			  "post_title" => $title, //The title of your post.
			  "post_type" => "page" //You may want to insert a regular post, page, link, a menu item or some custom post type
			);
			$postId = wp_insert_post($post);
			iHomefinderMenu::getInstance()->addPageToCommunityPages($postId);
		}
		return $errorMessage;
	}

	public function communityPagesForm() {
		$errorMessage = false;
		//On Update, push the CSS_OVERRIDE_OPTION to iHomefinder
		if($this->isUpdated()) {
			//call function here to pass the activation key to ihf and update the CSS Override value
			$title = $_REQUEST["ihfPageTitle"];
			$cityZip = $_REQUEST["cityZip"];
			$propertyType = $_REQUEST["propertyType"];
			$bed = $_REQUEST["bed"];
			$bath = $_REQUEST["bath"];
			$minPrice = $_REQUEST["minPrice"];
			$maxPrice = $_REQUEST["maxPrice"];
			$errorMessage = $this->updateCommunityPages($title, $cityZip, $propertyType, $bed, $bath, $minPrice, $maxPrice);
		}
		?>
		<div class="wrap">
			<h2>Community Pages</h2>
			<div style="float:left; padding-right: 40px;">
				<h3>Create a new Community Page</h3>
				<div>Enter search criteria to create a new page under the Community Pages menu.</div>
				<?php
					if($errorMessage) {
						echo "<br />" . $errorMessage . "<br />";
					}
				?>
				<form method="post">
					<input type="hidden" name="settings-updated" value="true"/>
					<?php settings_fields(iHomefinderConstants::COMMUNITY_PAGES); ?>
					<div style="margin: 10px;"></div>
					<div style="float:left; margin: 10px;">
						<b>Location:</b>
						<br />
						<div style="padding-bottom: 9px;">
							<?php $this->createCityZipAutoComplete()?>
						</div>
						<b>Page Title:</b>
						<br />
						<div style="padding-bottom: 9px;">
							<input type="text" id="ihfPageTitle" name="ihfPageTitle" style="width: 220px;" />
						</div>
						<b>Property Type:</b><br />
						<div>
							<?php $this->createPropertyTypeSelect()?>
						</div>
					</div>
					<div style="float:left; margin: 10px;">
						<b>Bed:</b>
						<br />
						<div style="padding-bottom: 9px;">
							<input type="text" name="bed" style="width: 220px;" />
						</div>
						<b>Bath:</b>
						<br />
						<div style="padding-bottom: 9px;">
							<input type="text" name="bath" style="width: 220px;" />
						</div>
						<b>Min Price:</b>
						<br />
						<div style="padding-bottom: 9px;">
							<input type="text" name="minPrice" style="width: 220px;" />
						</div>
						<b>Max Price:</b>
						<br />
						<div style="padding-bottom: 9px;">
							<input type="text" name="maxPrice" style="width: 220px;" />
						</div>
					</div>
					<div style="clear:both;"></div>
					<p class="submit">
						<input type="submit" class="button-primary" value="<?php _e("Save") ?>" />
					</p>
				</form>
			</div>
			<div style="float: left">
				<h3>Existing Community Pages</h3>
				<div style="padding-bottom: 9px;">Click the page name to edit Community Page content.</div>
				<div style="padding-bottom: 9px;">
					Change or edit the links that appear within the
					<a href="<?php echo site_url() ?>/wp-admin/nav-menus.php">Menus</a>
					section.
				</div>
				<?php
					$communityPageMenuItems = (array) iHomefinderMenu::getInstance()->getCommunityPagesMenuItems();
					?>
					<ul>
						<?php
						foreach($communityPageMenuItems as $key => $menu_item) {
							?>
							<li>
								<a href="post.php?post=<?php echo $menu_item->object_id ?>&action=edit">
									<?php echo $menu_item->title; ?>
								</a>
							</li>
							<?php
						}
						?>
					</ul>
					<?php
				?>
			</div>
		</div>
		<?php
	}

	public function adminOptionsPagesForm() {
		$permissions = iHomefinderPermissions::getInstance();
		if($this->isUpdated()) {
			//call function here will re-activate the plugin and re-register the new URL patterns
			$this->updateAuthenticationToken();
		}
		$pageConfig = iHomefinderAdminPageConfig::getInstance();
		?>
		<div class="wrap">
			<h2>IDX Pages</h2>
			<br />
			<div>
				<form method="post" action="options.php">
					<input type="submit" class="button-primary" value="<?php _e("Save Changes") ?>" />
					<?php settings_fields(iHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG); ?>
					<?php
						$pageConfig->getDetailPageSetup();
						$pageConfig->getSearchPageSetup();
						if($permissions->isMapSearchEnabled()) {
							$pageConfig->getMapSearchPageSetup();
						}
						$pageConfig->getAdvSearchPageSetup();
						if($permissions->isOrganizerEnabled()) {
							$pageConfig->getOrganizerLoginPageSetup();
						}
						if($permissions->isEmailUpdatesEnabled()) {
							$pageConfig->getEmailAlertsPageSetup();
						}
						if($permissions->isFeaturedPropertiesEnabled()) {
							$pageConfig->getFeaturedPageSetup();
						}
						if($permissions->isHotSheetEnabled()) {
							$pageConfig->getHotsheetPageSetup();
						}
						if($permissions->isContactFormEnabled()) {
							$pageConfig->getContactFormPageSetup();
						}
						if($permissions->isValuationEnabled()) {
							$pageConfig->getValuationFormPageSetup();
						}
						$pageConfig->getOpenHomeSearchFormPageSetup();
						if($permissions->isSupplementalListingsEnabled()) {
							$pageConfig->getSupplementalListingPageSetup();
						}
						if($permissions->isSoldPendingEnabled()) {
							$pageConfig->getSoldFeaturedListingPageSetup();
							$pageConfig->getSoldDetailPageSetup();
						}
						if($permissions->isOfficeEnabled()) {
							$pageConfig->getOfficeListPageSetup();
							$pageConfig->getOfficeDetailPageSetup();
						}
						if($permissions->isAgentBioEnabled()) {
							$pageConfig->getAgentListPageSetup();
							$pageConfig->getAgentDetailPageSetup();
						}
						$pageConfig->getDefaultPageSetup();
					?>
					<div>* Template selection is compatible only with select themes.</div>
					<p class="submit">
						<input type="submit" class="button-primary" value="<?php _e("Save Changes") ?>" />
					</p>
				</form>
			</div>
		</div>
		<?php
	}


	private function createCityZipAutoComplete() {
		$formData = iHomefinderSearchFormFieldsUtility::getInstance()->getFormData();
		$cityZipList = $formData->getCityZipList();
		$cityZipListJson = json_encode($cityZipList);
		wp_enqueue_script("jquery");
		wp_enqueue_script("jquery-ui-core");
		wp_enqueue_script("jquery-ui-autocomplete", "", array("jquery-ui-widget", "jquery-ui-position"), "1.8.6");
		wp_enqueue_style("jquery-ui-autocomplete", plugins_url("css/jquery-ui-1.8.18.custom.css", __FILE__));
		?>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery("input#ihfCommunityPagesAutoComplete").focus(function() {jQuery("input#ihfCommunityPagesAutoComplete").val("");});
				jQuery("input#ihfCommunityPagesAutoComplete").autocomplete({
					autoFocus: true,
					source: function(request,response) {
						var data=<?php echo($cityZipListJson);?>;
						var searchTerm=request.term;
						searchTerm=searchTerm.toLowerCase();
						var results=new Array();
						for(var i=0; i<data.length;i++) {
							var oneTerm=data[i];
							var value=oneTerm.value + "";
							value=value.toLowerCase();
							if(value && value != null && value.indexOf(searchTerm) == 0) {
								results.push(oneTerm);
							}
						}
						response(results);
					},
					select: function(event, ui) {
						//When an item is selected, set the text value for the link
						jQuery("#ihfPageTitle").val(ui.item.label);
					},
					selectFirst: true
				});
			});
		</script>
		<input type="text" id="ihfCommunityPagesAutoComplete" name="cityZip" placeholder="Enter City - OR - Postal Code" style="width: 220px;" />
		<?php
	}

	private function createPropertyTypeSelect() {
		$formData = iHomefinderSearchFormFieldsUtility::getInstance()->getFormData();
		if(isset($formData)) {
			$propertyTypesList=$formData->getPropertyTypesList();
			if(isset($propertyTypesList)) {
				$selectText = "<select id='propertyType' name='propertyType' style='width: 220px;'>";
				foreach ($propertyTypesList as $i => $value) {
					$selectText .= "<option value='" . $propertyTypesList[$i]->propertyTypeCode . "'>";
					$selectText .=  $propertyTypesList[$i]->displayName;
					$selectText .=  "</option>";
				}
				$selectText .= "</select>";
				echo $selectText;
			}
		}
	}
	
}