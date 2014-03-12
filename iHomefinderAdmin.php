<?php
if( !class_exists('IHomefinderAdmin')) {
	class IHomefinderAdmin {

		private static $instance ;
		private $virtualPageFactory ;

		private function __construct(){
			$this->virtualPageFactory=IHomefinderVirtualPageFactory::getInstance() ;
		}

		public static function getInstance(){
			if( !isset(self::$instance)){
				self::$instance = new IHomefinderAdmin();
			}
			return self::$instance;
		}
		
		public function checkError(){
			$pageName=$_REQUEST["page"];
			
			//Check for valid plugin registration
			//Do not check for registration on the registration page.
			if ($pageName != IHomefinderConstants::OPTION_ACTIVATE && !get_option(IHomefinderConstants::AUTHENTICATION_TOKEN_CACHE) ) {
				
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
				<a href="admin.php?page=<?php echo IHomefinderConstants::OPTION_ACTIVATE ?>" class="button button-primary">Activate Your Optima Express Account</a>
				&nbsp;&nbsp;&nbsp;Get an unlimited free trial or paid subscription for your MLS</p>

				<?php
				
			}
			
			if( $_REQUEST[iHomefinderConstants::COMPATIBILITY_CHECK_ENABLED] == 'false' ) {
				update_option( iHomefinderConstants::COMPATIBILITY_CHECK_ENABLED, 'false' );
			}
			
			if( get_option( iHomefinderConstants::COMPATIBILITY_CHECK_ENABLED ) != 'false' ) {
			
				$errors = array();
				//Get current wordpress plugins as array
				$plugins = get_plugins();
					
				//check if wordpress address and site address match
				if (get_home_url() != get_site_url()) {					
					$errors[] = "<p><a href='options-general.php'>WordPress Address and Site Address do not match (Error 404)</a></p>";
				}
					
				//check if permalink structure is set
				if (get_option('permalink_structure') == "") {
					$errors[] = "<p><a href='options-permalink.php'>WordPress permalink settings are set as default (Error 404)</a></p>";					
				}
		
				//check if both OE plugins are active
				if (array_key_exists("optima-express/iHomefinder.php",$plugins) == true && array_key_exists("wordpress-idx/WordpressIDX.php",$plugins) == true) {
					$errors[] = "<p><a href='plugins.php?s=idx'>Multiple IDX plugins are installed</a></p>";					
				}
					
				//Get compatibility JSON as array
				$compatibilityUrl = iHomefinderConstants::LEGACY_EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=compatibility-check' ;
				$requestArgs = array("timeout"=>"20" );
				$response = wp_remote_get($compatibilityUrl, $requestArgs);
				if( !is_wp_error($response)){
					$responseBody = wp_remote_retrieve_body( $response );
					$compatibility = json_decode($responseBody, true);
					$compatibilityPluginArray=$compatibility["Plugin"];
													
					//loop through plugin array
					foreach ($plugins as $pluginPath => $plugin) {
						//check if plugin is active
						if (is_plugin_active($pluginPath) == true) {
							//get plugin name
							$pluginName = $plugin["Name"];	
							$message=$compatibilityPluginArray[$pluginName];
							if( $message != null ){
								$errors[] = "<p><a href='plugins.php?s=" .  urlencode($pluginName) . "'>" . $pluginName . " (" . $message . ")</a></p>";	
							}		
						}
					}
						
					if ( function_exists('wp_get_theme')){
						//get current wordpress theme as string
						$theme = wp_get_theme();	
						$themeName=$theme["Name"];
						$compatibilityThemeArray=$compatibility["Theme"];	
						$message=$compatibilityThemeArray[$themeName];
						if($message != null ){
							$errors[] = "<p><a href='themes.php'>" . $themeName . " (" . $message . ")</a></p>";
						}
					}
				}
					
				//check error count
				if (count($errors) > 0) {
					?>
					<div class='error'>
						<div style="">
							<h3 style="float: left;"><?php echo count($errors) ?> compatibility issue(s):</h3>
							<form id="<?php echo IHomefinderConstants::COMPATIBILITY_CHECK_ENABLED ?>" style="float: right; margin-top: 5px;" method="post" action="options.php">
								<?php settings_fields( IHomefinderConstants::OPTION_GROUP_COMPATIBILITY_CHECK ); ?>
								<input type="hidden" value="false" name="<?php echo IHomefinderConstants::COMPATIBILITY_CHECK_ENABLED ?>" />
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
				
				$CurrentUrl = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
				if(IHomefinderLayoutManager::getInstance()->isResponsive() && ( $CurrentUrl == get_bloginfo('wpurl') . '/wp-admin/' || $CurrentUrl == get_bloginfo('wpurl') . '/wp-admin/index.php' ) ) {
					?>
					<div class="error">
						<p><a href="http://www.ihomefinder.com/support/optima-express-kb/optima-express-beta/">Learn more</a> about this beta version of Optima Express. Report errors or bugs to <a href="mailto:support@ihomefinder.com">support@ihomefinder.com</a></p>
					</div>
					<?php
				}
				
			}
		}

		public function createAdminMenu(){
			$permissions=IHomefinderPermissions::getInstance() ;
			add_menu_page('Optima Express', 'Optima Express', 'manage_options', 'ihf_idx', array( $this, 'adminOptionsForm' ));
            add_submenu_page( 'ihf_idx', 'Information', 'Information', 'manage_options', 'ihf_idx', array( &$this, 'adminOptionsForm'));
            add_submenu_page( 'ihf_idx', 'Register', 'Register', 'manage_options', IHomefinderConstants::OPTION_ACTIVATE, array( &$this, 'adminOptionsActivateForm'));
			add_submenu_page( 'ihf_idx', 'IDX Control Panel', 'IDX Control Panel', 'manage_options', IHomefinderConstants::OPTION_IDX_CONTROL_PANEL, array( &$this, 'adminIdxControlPanelForm'));
            add_submenu_page( 'ihf_idx', 'IDX Pages', 'IDX Pages', 'manage_options', IHomefinderConstants::OPTION_PAGES, array( &$this, 'adminOptionsPagesForm'));
            add_submenu_page( 'ihf_idx', 'Configuration', 'Configuration', 'manage_options', IHomefinderConstants::OPTION_CONFIG_PAGE, array( &$this, 'adminConfigurationForm'));

			add_submenu_page( 'ihf_idx', 'Bio Widget', 'Bio Widget', 'manage_options', IHomefinderConstants::BIO_PAGE, array( &$this, 'bioInformationForm'));
			add_submenu_page( 'ihf_idx', 'Social Widget', 'Social Widget', 'manage_options', IHomefinderConstants::SOCIAL_PAGE, array( &$this, 'socialInformationForm'));

			add_submenu_page( 'ihf_idx', 'Email Branding', 'Email Branding', 'manage_options', IHomefinderConstants::EMAIL_BRANDING_PAGE, array( &$this, 'emailDisplayForm'));
			if(IHomefinderPermissions::getInstance()->isCommunityPagesEnabled()){
				add_submenu_page( 'ihf_idx', 'Community Pages', 'Community Pages', 'manage_options', IHomefinderConstants::COMMUNITY_PAGES, array( &$this, 'communityPagesForm'));
			}
			if(IHomefinderPermissions::getInstance()->isSeoCityLinksEnabled()){
				add_submenu_page( 'ihf_idx', 'SEO City Links', 'SEO City Links', 'manage_options', IHomefinderConstants::SEO_CITY_LINKS_PAGE, array( &$this, 'seoCityLinksForm'));
			}

		}

		public function adminOptionsForm(){
                    if (!current_user_can('manage_options'))  {
                        wp_die( __('You do not have sufficient permissions to access this page.') );
                    }
                    ?>

                    <div class="wrap">
                        <h2>Information</h2>
                        <br/>
                        <div>

                            <h3>Version <?php echo IHomefinderConstants::VERSION ?></h3>

                            <h3>Register</h3>
                            The Optima Express plugin needs to be registered with iHomefinder. Registration is automatic if you signup for a trial account or purchase a live account from this page. Or, you can enter a registration key that you've received separately.

                            <h3>IDX Pages</h3>
                            View and configure your Optima Express IDX pages here. Change permalinks, page titles and templates.

                            <h3>Configuration</h3>
                            This page provides customization features including the ability to override default styles for Optima Express.

                            <h3>Bio Widget</h3>
                            Setup your bio information.  Upload a photo and insert contact information.

                            <h3>Social Widget</h3>
                            Enter your social network information.

                            <h3>Email Display</h3>
                            Customize your email header and footer

                            <h3>Community Pages</h3>
                            Create custom pages for your communities.  These pages contain a list of properties in
                            the community, SEO friendly URLs and the ability to add custom content.

                            <h3>SEO City Links</h3>
                            Create SEO links for display in the SEO City Links widget.

                        </div>
                    </div>
                    <?php
		}

		public function updateAuthenticationToken(){
			$activationToken=get_option(IHomefinderConstants::ACTIVATION_TOKEN_OPTION);
			$this->activateAuthenticationToken( $activationToken ) ;
			
		}

		public function activateAuthenticationToken( $activationToken, $updateActivationTokenOption = false ){
			if( $updateActivationTokenOption ){
				update_option(IHomefinderConstants::ACTIVATION_TOKEN_OPTION, $activationToken );
			}

			if($activationToken != null && "" != $activationToken){
				
        IHomefinderLogger::getInstance()->debug('Begin IHomefinderAdmin.activate');
        
        $authenticationInfo=$this->activate($activationToken);
        
        IHomefinderLogger::getInstance()->debugDumpVar($authenticationInfo);
        
        IHomefinderLogger::getInstance()->debug('Begin set authentication token');
        
        $authenticationToken = '';
				if( $authenticationInfo->authenticationToken ){
					$authenticationToken = (string) $authenticationInfo->authenticationToken;
					
					$permissions = $authenticationInfo->permissions;
					IHomefinderPermissions::getInstance()->initialize( $permissions );

					if( !$this->previouslyActivated()){
						update_option(IHomefinderConstants::IS_ACTIVATED_OPTION,'true');
					}
				}				
				update_option(IHomefinderConstants::AUTHENTICATION_TOKEN_CACHE, $authenticationToken);				
			}
			IHomefinderMenu::getInstance()->updateOptimaExpressMenu() ;
		}


		public function deleteAuthenticationToken(){
			//This forces reactivation of the plugin at next site visit.
		    delete_option(IHomefinderConstants::AUTHENTICATION_TOKEN_CACHE);
		}

		/**
		 * If the authentication token has expired then generate a new authentication token
		 * from the activationToken.
		 */
		public function getAuthenticationToken(){
			$authenticationToken = get_option(IHomefinderConstants::AUTHENTICATION_TOKEN_CACHE);
			return $authenticationToken ;
		}

		public function previouslyActivated(){
			return get_option(IHomefinderConstants::IS_ACTIVATED_OPTION);
		}

		private function createOneLink( $name, $url, $description ){
		  	$link = array(
		    	'link_url' => $url,
		  		'link_name' => $name,
		  		'link_description' => $description
		  	);

			// Insert the post into the database
			try {
				wp_insert_link( $link );
			} catch (Exception $e) {
				echo( '<hr/>exception: '.$e.'<hr/>');
			}

		}

		private function activate($activationToken){
			$urlFactory=IHomefinderUrlFactory::getInstance();
			$ajaxBaseUrl                          = urlencode($urlFactory->getAjaxBaseUrl());
			$listingsSearchResultsUrl             = urlencode($urlFactory->getListingsSearchResultsUrl(true));
			$listingsSearchFormUrl                = urlencode($urlFactory->getListingsSearchFormUrl(true));
			$listingDetailUrl                     = urlencode($urlFactory->getListingDetailUrl(true));
			$featuredSearchResultsUrl             = urlencode($urlFactory->getFeaturedSearchResultsUrl(true));
			$hotsheetSearchResultsUrl             = urlencode($urlFactory->getHotsheetSearchResultsUrl(true));
			$organizerLoginUrl                    = urlencode($urlFactory->getOrganizerLoginUrl(true));
			$organizerLogoutUrl                   = urlencode($urlFactory->getOrganizerLogoutUrl(true));
			$organizerLoginSubmitUrl              = urlencode($urlFactory->getOrganizerLoginSubmitUrl(true));
			$organizerEditSavedSearchUrl          = urlencode($urlFactory->getOrganizerEditSavedSearchUrl(true));
			$organizerEditSavedSearchSubmitUrl    = urlencode($urlFactory->getOrganizerEditSavedSearchSubmitUrl(true));
			$organizerDeleteSavedSearchSubmitUrl  = urlencode($urlFactory->getOrganizerDeleteSavedSearchSubmitUrl(true));
			$organizerViewSavedSearchUrl          = urlencode($urlFactory->getOrganizerViewSavedSearchUrl(true));
			$organizerViewSavedSearchListUrl      = urlencode($urlFactory->getOrganizerViewSavedSearchListUrl(true));
			$organizerViewSavedListingListUrl     = urlencode($urlFactory->getOrganizerViewSavedListingListUrl(true));
			$organizerDeleteSavedListingUrl       = urlencode($urlFactory->getOrganizerDeleteSavedListingUrl(true));
			$organizerResendConfirmationEmailUrl  = urlencode($urlFactory->getOrganizerResendConfirmationEmailUrl(true));
			$organizerActivateSubscriberUrl       = urlencode($urlFactory->getOrganizerActivateSubscriberUrl(true));
			$organizerSendSubscriberPasswordUrl   = urlencode($urlFactory->getOrganizerSendSubscriberPasswordUrl(true));
			$listingsAdvancedSearchFormUrl        = urlencode($urlFactory->getListingsAdvancedSearchFormUrl(true));
			$organizerHelpUrl                     = urlencode($urlFactory->getOrganizerHelpUrl(true));
			$organizerEditSubscriberUrl           = urlencode($urlFactory->getOrganizerEditSubscriberUrl(true));
			$contactFormUrl                       = urlencode($urlFactory->getContactFormUrl(true));
			$valuationFormUrl                     = urlencode($urlFactory->getValuationFormUrl(true));
			$listingSoldDetailUrl                 = urlencode($urlFactory->getListingSoldDetailUrl(true));
			$openHomeSearchFormUrl                = urlencode($urlFactory->getOpenHomeSearchFormUrl(true));
			$soldFeaturedListingUrl               = urlencode($urlFactory->getSoldFeaturedListingUrl(true));
			$supplementalListingUrl               = urlencode($urlFactory->getSupplementalListingUrl(true));
      		$listingSearchByAddressResultsUrl     = urlencode($urlFactory->getListingSearchByAddressResultsUrl(true));
      		$listingSearchByListingIdResultsUrl   = urlencode($urlFactory->getListingSearchByListingIdResultsUrl(true));
			$officeListUrl                        = urlencode($urlFactory->getOfficeListUrl(true));
			$officeDetailUrl                      = urlencode($urlFactory->getOfficeDetailUrl(true));
			$agentBioListUrl                      = urlencode($urlFactory->getAgentListUrl(true));
			$agentBioDetailUrl                    = urlencode($urlFactory->getAgentDetailUrl(true));
			$mapSearchUrl                    	  = urlencode($urlFactory->getMapSearchFormUrl(true));
			
			//Push CSS Override to iHomefinder
			$cssOverride = get_option(IHomefinderConstants::CSS_OVERRIDE_OPTION);
			$cssOverride = urlencode( $cssOverride);
			
			//Push layout style to iHomefinder
			$layoutType = IHomefinderLayoutManager::getInstance()->getLayoutType();
			$layoutType = urlencode( $layoutType);
			
			//Push color scheme to iHomefinder
			$colorScheme = get_option(IHomefinderConstants::COLOR_SCHEME_OPTION);
			$colorScheme = urlencode( $colorScheme);

			$emailHeader=IHomefinderAdminEmailDisplay::getInstance()->getHeader() ;
			$emailHeader = urlencode( $emailHeader);

			$emailFooter=IHomefinderAdminEmailDisplay::getInstance()->getFooter() ;
			$emailFooter = urlencode( $emailFooter);

			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl()  ;
			$postData= array(
				'method'=>'handleRequest',
			  'requestType'=>'activate',
			  'viewType'=>'json',
				'activationToken'=>$activationToken,
				'ajaxBaseUrl'=> $ajaxBaseUrl,
				'type'=> "wordpress",
				'listingSearchResultsUrl'=> $listingsSearchResultsUrl,
				'listingSearchFormUrl'=> $listingsSearchFormUrl,
				'listingDetailUrl'=> $listingDetailUrl,
				'featuredSearchResultsUrl'=> $featuredSearchResultsUrl,
				'hotsheetSearchResultsUrl'=> $hotsheetSearchResultsUrl,
				'organizerLoginUrl'=> $organizerLoginUrl,
				'organizerLogoutUrl'=> $organizerLogoutUrl,
				'organizerLoginSubmitUrl'=> $organizerLoginSubmitUrl,
				'organizerEditSavedSearchUrl'=> $organizerEditSavedSearchUrl,
				'organizerEditSavedSearchSubmitUrl'=> $organizerEditSavedSearchSubmitUrl,
				'organizerDeleteSavedSearchSubmitUrl'=> $organizerDeleteSavedSearchSubmitUrl,
				'organizerViewSavedSearchUrl'=> $organizerViewSavedSearchUrl,
				'organizerViewSavedSearchListUrl'=> $organizerViewSavedSearchListUrl,
				'organizerViewSavedListingListUrl'=> $organizerViewSavedListingListUrl,
				'organizerDeleteSavedListingUrl'=> $organizerDeleteSavedListingUrl,
				'organizerResendConfirmationEmailUrl'=> $organizerResendConfirmationEmailUrl,
				'organizerActivateSubscriberUrl'=> $organizerActivateSubscriberUrl,
				'organizerSendSubscriberPasswordUrl'=> $organizerSendSubscriberPasswordUrl,
				'listingAdvancedSearchFormUrl'=> $listingsAdvancedSearchFormUrl,
				'organizerHelpUrl'=> $organizerHelpUrl,
				'organizerEditSubscriberUrl'=> $organizerEditSubscriberUrl,
				'contactFormUrl'=> $contactFormUrl,
				'valuationFormUrl'=> $valuationFormUrl,
				'listingSoldDetailUrl'=> $listingSoldDetailUrl,
				'openHomeSearchFormUrl'=> $openHomeSearchFormUrl,
				'soldFeaturedListingUrl'=> $soldFeaturedListingUrl,
				'supplementalListingUrl'=> $supplementalListingUrl,
        		'listingSearchByAddressResultsUrl' => $listingSearchByAddressResultsUrl,
        		'listingSearchByListingIdResultsUrl' => $listingSearchByListingIdResultsUrl,
				'officeListUrl'=> $officeListUrl,
				'officeDetailUrl'=> $officeDetailUrl,
				'agentBioListUrl'=> $agentBioListUrl,
				'agentBioDetailUrl'=> $agentBioDetailUrl,
				'mapSearchUrl' => $mapSearchUrl,
				'cssOverride'=> $cssOverride,
				'emailHeader'=> $emailHeader,
				'emailFooter'=> $emailFooter,
				'layoutType'=> $layoutType,
				'colorScheme'=> $colorScheme
			);
      
      IHomefinderLogger::getInstance()->debug( '$ihfUrl:::' . $ihfUrl.http_build_query($postData) ) ;
			$authenticationInfo = IHomefinderRequestor::remotePostRequest( $ihfUrl, $postData ) ;

			//We need to flush the rewrite rules, if any permalinks have been updated.
			//Only flush in the admin screens, because that is the only point
			//where urls patterns may change.
			if( is_admin() ){
				IHomefinderRewriteRules::getInstance()->flushRules() ;
			}
			IHomefinderLogger::getInstance()->debugDumpVar($authenticationInfo);
			return $authenticationInfo ;
		}


		/**
		 * Create register option groups and associated options.
		 * Later use settings_fields in the forms to populate the options.
		 */
		public function registerSettings(){
			//Activation settings
			register_setting( IHomefinderConstants::OPTION_ACTIVATE, IHomefinderConstants::ACTIVATION_TOKEN_OPTION );
			register_setting( IHomefinderConstants::OPTION_ACTIVATE, IHomefinderConstants::ACTIVATION_DATE_OPTION );			
			
			//Configuration Settings
			register_setting( IHomefinderConstants::OPTION_CONFIG_PAGE, IHomefinderConstants::OPTION_LAYOUT_TYPE );
			register_setting( IHomefinderConstants::OPTION_CONFIG_PAGE, IHomefinderConstants::CSS_OVERRIDE_OPTION );
			register_setting( IHomefinderConstants::OPTION_CONFIG_PAGE, IHomefinderConstants::COLOR_SCHEME_OPTION );

			//Bio Options
			register_setting( IHomefinderConstants::OPTION_GROUP_BIO, IHomefinderConstants::AGENT_PHOTO_OPTION );
		 	register_setting( IHomefinderConstants::OPTION_GROUP_BIO, IHomefinderConstants::OFFICE_LOGO_OPTION );
		 	register_setting( IHomefinderConstants::OPTION_GROUP_BIO, IHomefinderConstants::AGENT_TEXT_OPTION );
		 	register_setting( IHomefinderConstants::OPTION_GROUP_BIO, IHomefinderConstants::AGENT_DISPLAY_TITLE_OPTION );
		 	register_setting( IHomefinderConstants::OPTION_GROUP_BIO, IHomefinderConstants::AGENT_LICENSE_INFO_OPTION );

		 	register_setting( IHomefinderConstants::OPTION_GROUP_BIO, IHomefinderConstants::AGENT_DESIGNATIONS_OPTION );
		 	register_setting( IHomefinderConstants::OPTION_GROUP_BIO, IHomefinderConstants::CONTACT_PHONE_OPTION );
		 	register_setting( IHomefinderConstants::OPTION_GROUP_BIO, IHomefinderConstants::CONTACT_EMAIL_OPTION );

		 	//Social Options
		 	register_setting( IHomefinderConstants::OPTION_GROUP_SOCIAL, IHomefinderConstants::FACEBOOK_URL_OPTION );
		 	register_setting( IHomefinderConstants::OPTION_GROUP_SOCIAL, IHomefinderConstants::LINKEDIN_URL_OPTION );
		 	register_setting( IHomefinderConstants::OPTION_GROUP_SOCIAL, IHomefinderConstants::TWITTER_URL_OPTION );

		 	//Email Display Options
		 	register_setting( IHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY, IHomefinderConstants::EMAIL_HEADER_OPTION );
		 	register_setting( IHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY, IHomefinderConstants::EMAIL_FOOTER_OPTION );
		 	register_setting( IHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY, IHomefinderConstants::EMAIL_PHOTO_OPTION );
		 	register_setting( IHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY, IHomefinderConstants::EMAIL_LOGO_OPTION  );
			register_setting( IHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY, IHomefinderConstants::EMAIL_NAME_OPTION  );
			register_setting( IHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY, IHomefinderConstants::EMAIL_COMPANY_OPTION  );
			register_setting( IHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY, IHomefinderConstants::EMAIL_ADDRESS_LINE1_OPTION  );
			register_setting( IHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY, IHomefinderConstants::EMAIL_ADDRESS_LINE2_OPTION  );
			register_setting( IHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY, IHomefinderConstants::EMAIL_PHONE_OPTION  );
			register_setting( IHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY, IHomefinderConstants::EMAIL_DISPLAY_TYPE_OPTION  );

		 	//SEO City Links Options
		 	register_setting(IHomefinderConstants::OPTION_GROUP_SEO_CITY_LINKS, IHomefinderConstants::SE0_CITY_LINKS_SETTINGS);
		 	register_setting(IHomefinderConstants::OPTION_GROUP_SEO_CITY_LINKS, IHomefinderConstants::SE0_CITY_LINK_WIDTH);

			//Compatibility Check Options
		 	register_setting(IHomefinderConstants::OPTION_GROUP_COMPATIBILITY_CHECK, IHomefinderConstants::COMPATIBILITY_CHECK_ENABLED);

			//Register Virtual Page related groups and options
			IHomefinderVirtualPageHelper::getInstance()->registerOptions() ;

		}

		//Check if an options form has been updated.
		private function isUpdated(){
			//When new options are updated, the paramerter "updated" is set to true
			$isUpdated = ( array_key_exists('updated', $_REQUEST) && $_REQUEST["updated"] ) ;
			if(!$isUpdated){register_setting( IHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY, IHomefinderConstants::EMAIL_DISPLAY_TYPE_OPTION );
				//version 3.1 sets this value, rather than "updated"
				$isUpdated = ( array_key_exists('settings-updated', $_REQUEST) && $_REQUEST["settings-updated"] ) ;
			}
			return $isUpdated ;
		}

		public function adminOptionsActivateForm(){
			
			?>		
						
			<div class="wrap">
			
			<?php
			if (!current_user_can('manage_options'))  {
				wp_die( __('You do not have sufficient permissions to access this page.') );
			}
			
			if( $_GET['reg'] ) {
				$regKey = $_GET['reg'];
				update_option( IHomefinderConstants::ACTIVATION_TOKEN_OPTION, $regKey );
				$this->updateAuthenticationToken();
				?>
				<h2>Thanks For Signing Up</h2>
				<div class="updated">
					<p>Your Optima Express plugin has been registered.</p>
				</div>
				<p>You will receive an email from us with IDX paperwork for your MLS. Please complete the paperwork and return it to iHomefinder promptly. Listings from your MLS will appear in Optima Express as soon as your MLS approves your IDX paperwork.</p>
				<?php

			} elseif($this->isUpdated()){
				//call function here to pass the activation key to ihf and get
				//an authentication token
				$this->updateAuthenticationToken();
			}
			
			if( $_GET['section'] == 'enter-reg-key' ) {
				
				?>
				
				<h2>Add Registration Key</h2>
				
				<?php
				if( get_option( IHomefinderConstants::ACTIVATION_TOKEN_OPTION ) == '' ) {
					?>
					<div class="error">
						<p>Add your Registration Key and click "Save Changes" to get started with Optima Express.</p>
					</div>
					<?php
				} elseif( get_option( IHomefinderConstants::AUTHENTICATION_TOKEN_CACHE ) != '' ){
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
				<?php settings_fields( IHomefinderConstants::OPTION_ACTIVATE ); ?>
				<table class="form-table">
					<tr valign="top">
						<th scope="row">
							<b>Registration Key:</b>
						</th>
						<td>
							<input type="text" size="45" name="<?php echo IHomefinderConstants::ACTIVATION_TOKEN_OPTION ?>" value="<?php echo get_option(IHomefinderConstants::ACTIVATION_TOKEN_OPTION); ?>" />
						</td>
					</tr>
				</table>
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</p>
				</form>
				
			<?php
				
			} elseif( $_GET['section'] == 'free-trial' ) {
				?>
			
				<h2>Free Trial Sign-Up</h2>
				
				<?php
				
				$Email = $_POST['Email'];
				$AccountType = $_POST['AccountType'];
				
				$errors = array();
				
				if( filter_var( $Email, FILTER_VALIDATE_EMAIL ) == FALSE ) {
					$errors[] = '<p>Email address is not valid.</p>';
				}
								
				if( $AccountType == '' ) {
					$errors[] = '<p>Select type of trial account.</p>';
				}
				
				if( count( $errors ) == 0 ) {
					
					$params = array();
					$params['plugin'] = 'true';
					$params['clientfirstname'] = 'Trial User';
					$params['clientlastname'] = 'Account';
					if( $AccountType == 'Broker' ) {
						$params['companyname'] = 'Many Homes Realty';
					} else {
						$params['companyname'] = 'Jamie Agent';
					}
					$params['companyemail'] = $Email;
					$params['password'] = substr( str_shuffle( 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890' ), 0, 6 );
					$params['companyphone'] = '800-555-1212';
					$params['companyaddress'] = '123 Main St.';
					$params['companycity'] = 'Anytown';
					$params['companystate'] = 'CA';
					$params['companyzip'] = '12345';
					$params['account_type'] = $AccountType;
					$params['product'] = 'Optima Express';
					$params['lead_source'] = 'Plugin';
					$params['lead_source_description'] = 'Optima Express Trial Form';
					$params['ad_code'] = '';
					$params['ip_address'] = $_SERVER['REMOTE_ADDR'];
					
					$requestUrl = 'http://www.ihomefinder.com/store/optima-express-trial.php';
					
					set_time_limit(90);
					$requestArgs = array( 'timeout' => '90', 'body' => $params );
					$response = wp_remote_post($requestUrl, $requestArgs);
					if( !is_wp_error($response)){
						$responseBody = wp_remote_retrieve_body( $response );
						$responseBody = json_decode($responseBody, true);
						
						$clientID = $responseBody['clientID'];
						$regKey = $responseBody['regKey'];
						$username = $responseBody['username'];
						$password = $responseBody['password'];
						
						update_option( IHomefinderConstants::ACTIVATION_TOKEN_OPTION, $regKey );
						$this->updateAuthenticationToken();
						
						?>
						<div class="updated">
							<p>Your Optima Express plugin has been registered.</p>
						</div>
						<p>Thank you for evaluating Optima Express!</p>
						<p>Your trial account uses sample listing data from Northern California. For search and listings in your MLS, <a href="http://www.ihomefinder.com/store/convert.php?cid=<?php echo $clientID ?>" target="_blank">upgrade to a paid account</a>.</p>
						<p>Visit our <a href="http://www.ihomefinder.com/support/optima-express-kb/" target="_blank">knowledge base</a> for assistance setting up IDX on your site.</p>
						<p>Don't hesitate to <a href="http://www.ihomefinder.com/forms/contact-us/" target="_blank">contact us</a> if you have any questions.</p>
						
						<?php
						
					} else {
						?>
						<div class="error">
							<p>Error creating your account.</p>
						</div>
						<?php
					}
					
				} else {
				
					if( $_POST ) {
						echo "<div class='error'>";
						foreach ($errors as $Error) {
							echo $Error;
						}
						echo "</div>";
					}
					
					?>
					
					<form method="post">
						<table class="form-table" style="width: 300px">
							<tr>
								<td>
									<b>
										<label for="Email">Email:</label>
									</b>
								</td>
								<td>
									<br />
									<input id="Email" style="width: 250px;" name="Email" type="text" value="<?php echo $Email ?>" />
									<small>This will be your username.</small>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<p>
										<b>Account Type:</b>
									</p>
									<input id="AccountType_Agent" name="AccountType" type="radio" value="Agent" <?php if($AccountType == 'Agent') {echo 'checked';} ?>>
									<label for="AccountType_Agent">Individual Agent</label>
									<br />
									<input id="AccountType_Broker" name="AccountType" type="radio" value="Broker" <?php if($AccountType == 'Broker') {echo 'checked';} ?>>
									<label for="AccountType_Broker">Office with Multiple Agents</label>
								</td>
							</tr>
						</table>
						<p class="submit">
							<input type="submit" class="button-primary" value="Start Trial" />
							<span>&nbsp;&nbsp;&nbsp;Creating your trial account can take up to 60 seconds to complete. Please do not refresh the page or press the back button.</span>
						</p>
					</form>
					<?php
				
				}
				
			} else {
			
				if( get_option( IHomefinderConstants::ACTIVATION_TOKEN_OPTION ) == '' ) {
					
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
					<a href="admin.php?page=<?php echo IHomefinderConstants::OPTION_ACTIVATE ?>&section=enter-reg-key">I already have a registration key</a>
					<br />
					<br />
					<a href="admin.php?page=<?php echo IHomefinderConstants::OPTION_ACTIVATE ?>&section=free-trial" class="button button-primary button-large-ihf" >Get a Free Trial<br />IDX Account</a>
					<a href="http://www.ihomefinder.com/product/optima-express/optima-express-agent-pricing/?plugin=true&redirectURL=<?php echo urlencode ( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ); ?>" class="button button-primary button-large-ihf">Sign Up for IDX<br />in Your MLS</a>
					<br />
					<br />
					<p>Optima Express from iHomefinder adds MLS/IDX search and listings directly into your WordPress site.</p>
					<p>A free trial account uses sample IDX listings from Northern California.</p>
					<p>Signing up for IDX in your MLS provides access to all listings in your MLS and full support from iHomefinder. Plans start at $39.95 per month. You must be a member of an MLS to qualify for IDX service. <a target="_blank" href="http://www.ihomefinder.com/mls-coverage/">Learn More</a></p>
					<?php
					
				} elseif( $_GET['reg'] == FALSE ) {
					?>
					<h2>Unregister Optima Express</h2>
					<p>Optima Express is currently registered. Clicking the below button will unregister the IDX plugin.<p>
					<form method="post" action="options.php">
						<?php settings_fields( IHomefinderConstants::OPTION_ACTIVATE ); ?>
						<input type="hidden" name="<?php echo IHomefinderConstants::ACTIVATION_TOKEN_OPTION ?>" value="" />
						<p class="submit">
							<input type="submit" class="button-primary" value="<?php _e('Unregister') ?>" onclick="return confirm('Are you sure you want to unregister Optima Express?');" />
						</p>
					</form>
					<form method="post" action="options.php" name="refreshRegistration">
						<?php settings_fields( IHomefinderConstants::OPTION_ACTIVATE ); ?>
						<input type="hidden" name="<?php echo IHomefinderConstants::ACTIVATION_TOKEN_OPTION ?>" value="<?php echo get_option(IHomefinderConstants::ACTIVATION_TOKEN_OPTION); ?>" />
						<a href="#" onclick="document.refreshRegistration.submit();">Refresh Registration</a>
					</form>
					<?php
					
				}
			}
			
			?>
			
			</div>			
		
			<?php			
			
		}
		
		public function adminIdxControlPanelForm(){
			?>
			<style type="text/css">
				#contentFrame {
					width: 100%;
					height: 800px;
					border: none;
				}
			</style>
			<?php
			if( get_option( IHomefinderConstants::ACTIVATION_TOKEN_OPTION ) != '' ) {
				?>
				<iframe id="contentFrame" src="<?php echo IHomefinderConstants::CONTROL_PANEL_EXTERNAL_URL; ?>/z.cfm?w=<?php echo get_option( IHomefinderConstants::ACTIVATION_TOKEN_OPTION ) ?>"></iframe>
				<?php
			}
				
		}
	
		public function addScripts(){
			//Used for the Bio Page for image uploads
			if (isset($_GET['page']) && ($_GET['page'] == IHomefinderConstants::BIO_PAGE || $_GET['page'] == IHomefinderConstants::EMAIL_BRANDING_PAGE ) ){
	     		wp_enqueue_script('jquery'); // include jQuery
    	 		wp_register_script('bioInformation', plugins_url("/optima-express/js/bioInformation.js"), array('jquery','editor','media-upload','thickbox'));
    	 		wp_enqueue_style('thickbox');
     			wp_enqueue_script('bioInformation');  // include script.js
			}
		}

		public function bioInformationForm(){

			?>
			<div class="wrap">
			<h2>Bio Widget Setup</h2>

			<p/>
			Configure and edit the Optima Express Bio Widget display here.
			<p/>

			<form method="post" action="options.php">
			    <?php settings_fields( IHomefinderConstants::OPTION_GROUP_BIO  ); ?>

			    <p class="submit">
			    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			    </p>

			    <h3>Agent Photo</h3>
			    <?php if( get_option( IHomefinderConstants::AGENT_PHOTO_OPTION)){
			    	?>
			    	<img id="ihf_upload_agent_photo_image" src="<?php echo(get_option(IHomefinderConstants::AGENT_PHOTO_OPTION))?>"
			    		<?php if( !get_option( IHomefinderConstants::AGENT_PHOTO_OPTION)){echo(" style='display:none'");}?>/><br/>
			    	<?php
			    }
			    ?>
				<input id="ihf_upload_agent_photo" type="text" size="36" name="<?php echo(IHomefinderConstants::AGENT_PHOTO_OPTION)?>" value="<?php echo(get_option(IHomefinderConstants::AGENT_PHOTO_OPTION))?>" />
          		<input id="ihf_upload_agent_photo_button" type="button" value="Upload Agent Photo" class="button-secondary"/>
			    <br/>
			    Enter an image URL or use an image from the Media Library
			    <br/><br/><br/>

			    <div style="float:left;width:100px;">Display Name:</div>
			    <input type="text" size="36" name="<?php echo(IHomefinderConstants::AGENT_DISPLAY_TITLE_OPTION)?>" value="<?php echo(get_option(IHomefinderConstants::AGENT_DISPLAY_TITLE_OPTION))?>" />
			    <div style="clear:both;"></div>


			    <div style="float:left;width:100px;">Contact Phone:</div>
			    <input type="text" size="36" name="<?php echo(IHomefinderConstants::CONTACT_PHONE_OPTION)?>" value="<?php echo(get_option(IHomefinderConstants::CONTACT_PHONE_OPTION))?>" />
			    <div style="clear:both;"></div>

			    <div style="float:left;width:100px;">Contact Email:</div>
			    <input type="text" size="36" name="<?php echo(IHomefinderConstants::CONTACT_EMAIL_OPTION)?>" value="<?php echo(get_option(IHomefinderConstants::CONTACT_EMAIL_OPTION))?>" />
			    <div style="clear:both;"></div>

				<div style="float:left;width:100px;">Designations:</div>
			    <input type="text" size="36" name="<?php echo(IHomefinderConstants::AGENT_DESIGNATIONS_OPTION)?>" value="<?php echo(get_option(IHomefinderConstants::AGENT_DESIGNATIONS_OPTION))?>" />
			    <div style="clear:both;"></div>

				<div style="float:left;width:100px;">License Info:</div>
			    <input type="text" size="36" name="<?php echo(IHomefinderConstants::AGENT_LICENSE_INFO_OPTION)?>" value="<?php echo(get_option(IHomefinderConstants::AGENT_LICENSE_INFO_OPTION))?>" />
			    <div style="clear:both;"></div>
			    
			    <br/><br/>

				<h3>Agent Bio Text</h3>
				<?php
					$agent_bio_editor_settings =  array (
            			'textarea_rows' => 15,
            			'media_buttons' => TRUE,
            			'teeny'         => TRUE,
						'tinymce'       => TRUE,
						'textarea_name' => IHomefinderConstants::AGENT_TEXT_OPTION
        			);
					wp_editor( get_option(IHomefinderConstants::AGENT_TEXT_OPTION), 'agentbiotextid', $agent_bio_editor_settings );

				?>
				<br/><br/>

			    <p class="submit">
			    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			    </p>

			</form>
			</div>

			<?php
		}

		public function socialInformationForm(){
			?>
			<div class="wrap">
			<h2>Social Widget Setup</h2>

			Enter your social media addresses for the Optima Express Social Media Widget.
			<p/>

			<form method="post" action="options.php">
			    <?php settings_fields( IHomefinderConstants::OPTION_GROUP_SOCIAL  ); ?>

			    <h3>Facebook</h3>
				http://www.facebook.com/
				<input type="text" size="36" name="<?php echo(IHomefinderConstants::FACEBOOK_URL_OPTION)?>" value="<?php echo(get_option(IHomefinderConstants::FACEBOOK_URL_OPTION))?>" />

				<br/>
				<h3>LinkedIn</h3>
				http://www.linkedin.com/
				<input type="text" size="36" name="<?php echo(IHomefinderConstants::LINKEDIN_URL_OPTION)?>" value="<?php echo(get_option(IHomefinderConstants::LINKEDIN_URL_OPTION))?>" />

				<br/>
				<h3>Twitter</h3>
				http://www.twitter.com/
				<input type="text" size="36" name="<?php echo(IHomefinderConstants::TWITTER_URL_OPTION)?>" value="<?php echo(get_option(IHomefinderConstants::TWITTER_URL_OPTION))?>" />

			    <p class="submit">
			    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			    </p>

			</form>
			</div>

			<?php
		}

		public function seoCityLinksForm(){
			$galleryFormData = IHomefinderSearchFormFieldsUtility::getInstance()->getFormData() ;
            $propertyTypesList=$galleryFormData->getPropertyTypesList() ;
            $cityZipList=$galleryFormData->getCityZipList();
            $cityZipListJson=json_encode($cityZipList);

  			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-autocomplete', '', array('jquery-ui-widget', 'jquery-ui-position'), '1.8.6');
			wp_enqueue_style( 'jquery-ui-autocomplete', plugins_url( 'css/jquery-ui-1.8.18.custom.css', __FILE__ ) );
			?>
			<script type="text/JavaScript">
			function ihfRemoveSeoLink( button ){
				//debugger;
				var theButton=jQuery(button);
				var theForm=theButton.closest("form");
				theButton.parent().remove();
				theForm.submit();
			}
			jQuery(document).ready(function() {
				jQuery("input#ihfSeoLinksAutoComplete").focus(function(){jQuery("input#ihfSeoLinksAutoComplete").val("");});
				jQuery("input#ihfSeoLinksAutoComplete").autocomplete({
					autoFocus: true,
					source: function(request,response){
						var data=<?php echo($cityZipListJson);?>;
						var searchTerm=request.term ;
						searchTerm=searchTerm.toLowerCase();
						var results=new Array();
						for(var i=0; i<data.length;i++){
							//debugger;
							var oneTerm=data[i];
							//appending '' converts numbers to strings for the indexOf function call
							var value=oneTerm.value + '';
							value=value.toLowerCase();
							if( value && value != null && value.indexOf( searchTerm ) == 0 ){
								results.push(oneTerm);
							}
						}
						response(results);
					},
					select: function(event, ui){
						//When an item is selected, set the text value for the link
						jQuery('#ihfSeoLinksText').val(ui.item.label);
					}
				});

			});
			</script>
			<div class="wrap">
			<h2>SEO City Links Setup</h2>
			<p/>
			Add city links for display in the SEO City Links widget.
			<p/>
			<form method="post" action="options.php" id="ihfSeoLinksForm">
			    <?php settings_fields( IHomefinderConstants::OPTION_GROUP_SEO_CITY_LINKS  ); ?>


				<div style="float:left;width:220px;padding-right:15px;">
				<div>
	    	     	<label>Location:</label>
	            </div>
            	<input type="text" id="ihfSeoLinksAutoComplete"
            		name="<?php echo IHomefinderConstants::SE0_CITY_LINKS_SETTINGS . '[0][' . IHomefinderConstants::SE0_CITY_LINKS_CITY_ZIP . ']'?>"
            		value="Enter City - OR - Postal Code"
            		size="30"/>

				</div>

				<div style="float:left;width:220px;padding-right:15px;">
				<div>
	    	     	<label>Property Type:</label>
	            </div>
            	<select name="<?php echo IHomefinderConstants::SE0_CITY_LINKS_SETTINGS . '[0][' . IHomefinderConstants::SE0_CITY_LINKS_PROPERTY_TYPE . ']' ?>">
             	<?php
	    			foreach ($propertyTypesList as $i => $value) {
    					echo "<option value='" . $propertyTypesList[$i]->propertyTypeCode . "'>" . $propertyTypesList[$i]->displayName . "</option>" ;
					}
				?>
				</select>
				</div>
				<div style="clear:both;"></div>
				<p/>
	            <div style="float:left;width:220px;padding-right:15px;">
		            <div style="float:left;width:80px;">
	    	        	<label>Min Price:</label>
	        	    </div>
	            	<div style="float:left">
	            		<input name="<?php echo IHomefinderConstants::SE0_CITY_LINKS_SETTINGS . '[0][' . IHomefinderConstants::SE0_CITY_LINKS_MIN_PRICE . ']'?>" type="text" size="10"/>
					</div>
				</div>
				<div style="clear:both;"></div>
				<div style="float:left;width:220px;padding-right:15px;">

		            <div style="float:left;width:80px;">
	    	        	<label>Max Price:</label>
	        	    </div>
	            	<div style="float:left">
	            		<input name="<?php echo IHomefinderConstants::SE0_CITY_LINKS_SETTINGS . '[0][' . IHomefinderConstants::SE0_CITY_LINKS_MAX_PRICE . ']'?>" type="text" size="10"/>
					</div>
					<div style="clear:both;"></div>
        		</div>
        		<div style="clear:both;"></div>
				<p/>
			    <div style="float:left;width:80px;">
	    	     	<label>Link Text:</label>
	            </div>
	           	<div style="float:left">
	           		<input id="ihfSeoLinksText" name="<?php echo IHomefinderConstants::SE0_CITY_LINKS_SETTINGS . '[0][' . IHomefinderConstants::SE0_CITY_LINKS_TEXT. ']'?>" type="text" size="30"/>
				</div>
				<div style="clear:both;"></div>

				<div style="padding-top: 20px">Link Configuration</div>
	            <div style="float:left;width:80px;">
	    	    	<label>Link Width:</label>
	           	</div>
	           	<div style="float:left;width:80px;">
	           		<input name="<?php echo(IHomefinderConstants::SE0_CITY_LINK_WIDTH)?>" type="text" size="3" value="<?php echo(get_option(IHomefinderConstants::SE0_CITY_LINK_WIDTH, '80'))?>"/>
				</div>


				<div style="clear:both;"></div>
			    <p class="submit">
			    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			    </p/>

				<p/>
				The following links will display in the SEO City Links widget.  Click the X to remove an entry.
				<p/>
			    <?php

			    	$seoCityLinksSettings =get_option(IHomefinderConstants::SE0_CITY_LINKS_SETTINGS);
			    	if( $seoCityLinksSettings && is_array($seoCityLinksSettings)){
		    			sort($seoCityLinksSettings);
		    			//save sorted array
		    			update_option(IHomefinderConstants::SE0_CITY_LINKS_SETTINGS, $seoCityLinksSettings);
		    			foreach ($seoCityLinksSettings as $i => $value) {
		    				$index=$value[IHomefinderConstants::SE0_CITY_LINKS_TEXT ];
		    				if($index){
			    				echo("<div>");
			    				echo("<input type='button' class='button-secondary' value='X' onclick='ihfRemoveSeoLink(this);'/>");
			    				echo("&nbsp;&nbsp;" . $index );
			    				echo "<input type='hidden' name='" . IHomefinderConstants::SE0_CITY_LINKS_SETTINGS . '[' . $index . '][' . IHomefinderConstants::SE0_CITY_LINKS_TEXT . "]' value='" . $value[IHomefinderConstants::SE0_CITY_LINKS_TEXT ] . "'>" ;
	   							echo "<input type='hidden' name='" . IHomefinderConstants::SE0_CITY_LINKS_SETTINGS . '[' . $index . '][' . IHomefinderConstants::SE0_CITY_LINKS_CITY_ZIP . "]' value='" . $value[IHomefinderConstants::SE0_CITY_LINKS_CITY_ZIP ] . "'>" ;
	   							echo "<input type='hidden' name='" . IHomefinderConstants::SE0_CITY_LINKS_SETTINGS . '[' . $index . '][' . IHomefinderConstants::SE0_CITY_LINKS_PROPERTY_TYPE . "]' value='" . $value[IHomefinderConstants::SE0_CITY_LINKS_PROPERTY_TYPE ] . "'>" ;
	   							echo "<input type='hidden' name='" . IHomefinderConstants::SE0_CITY_LINKS_SETTINGS . '[' . $index . '][' . IHomefinderConstants::SE0_CITY_LINKS_MIN_PRICE . "]' value='" . $value[IHomefinderConstants::SE0_CITY_LINKS_MIN_PRICE ] . "'>" ;
	   							echo "<input type='hidden' name='" . IHomefinderConstants::SE0_CITY_LINKS_SETTINGS . '[' . $index . '][' . IHomefinderConstants::SE0_CITY_LINKS_MAX_PRICE . "]' value='" . $value[IHomefinderConstants::SE0_CITY_LINKS_MAX_PRICE ] . "'>" ;
	   							echo("</div>");
		    				}
		    			}
			    	}

			    ?>
			</form>

			<p/>

			</div>
			<?php
		}

		public function emailDisplayForm(){

			$emailDisplayType=get_option(IHomefinderConstants::EMAIL_DISPLAY_TYPE_OPTION);
			//On Update, push the CSS_OVERRIDE_OPTION to iHomefinder
			if($this->isUpdated()){
				IHomefinderAdminEmailDisplay::getInstance()->setHeaderAndFooter() ;
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
			    <?php settings_fields( IHomefinderConstants::OPTION_GROUP_EMAIL_DISPLAY  ); ?>

			    <p class="submit">
			    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			    </p>

			    <?php
			    	//Check if we support default display which uses the logo and photo
			    	//previously uploaded.
			    	if(IHomefinderAdminEmailDisplay::getInstance()->includeDefaultDisplay() ){
			    		echo('Default Logo ' . IHomefinderAdminEmailDisplay::getInstance()->getDefaultLogo() );
			    	?>
			    	
			    	<input type="radio" name="<?php echo(IHomefinderConstants::EMAIL_DISPLAY_TYPE_OPTION)?>"
			    	    <?php if( IHomefinderAdminEmailDisplay::EMAIL_DISPLAY_TYPE_DEFAULT_VALUE == $emailDisplayType ){echo(" checked ");}?>
			    		value="<?php echo(IHomefinderAdminEmailDisplay::EMAIL_DISPLAY_TYPE_DEFAULT_VALUE)?>"/>Use Agent Bio photo & Header logo<br/>
			    	<p/>
			    	<?php }//end if ?>

			    <p/>

				<input type="radio" name="<?php echo(IHomefinderConstants::EMAIL_DISPLAY_TYPE_OPTION)?>"
					<?php if( IHomefinderAdminEmailDisplay::EMAIL_DISPLAY_TYPE_CUSTOM_IMAGES_VALUE == $emailDisplayType ){echo(" checked ");}?>
					value="<?php echo(IHomefinderAdminEmailDisplay::EMAIL_DISPLAY_TYPE_CUSTOM_IMAGES_VALUE)?>"/>&nbsp;Basic Branding<br/>
					
				<p/>
				Add the logo, photo and business information you would like displayed in your email branding.

			    <h3>Agent Photo</h3>
			    <?php if( get_option( IHomefinderConstants::EMAIL_PHOTO_OPTION)){
			    	?>
			    	<img id="ihf_upload_agent_photo_image" src="<?php echo(get_option(IHomefinderConstants::EMAIL_PHOTO_OPTION))?>"
			    		<?php if( !get_option( IHomefinderConstants::EMAIL_PHOTO_OPTION)){echo(" style='display:none'");}?>/><br/>
			    	<?php
			    }
			    ?>
				<input id="ihf_upload_agent_photo" type="text" size="36" name="<?php echo(IHomefinderConstants::EMAIL_PHOTO_OPTION)?>" value="<?php echo(get_option(IHomefinderConstants::EMAIL_PHOTO_OPTION))?>" />
          		<input id="ihf_upload_agent_photo_button" type="button" value="Upload Agent Photo" class="button-secondary"/>
			    <br/>
			    Enter an image URL or use an image from the Media Library
			    <br/><br/>

				<h3>Logo</h3>
			    <?php if( get_option( IHomefinderConstants::EMAIL_LOGO_OPTION)){
			    	?>
			    	<img id="ihf_upload_email_logo_image" src="<?php echo(get_option(IHomefinderConstants::EMAIL_LOGO_OPTION))?>"
			    		<?php if( !get_option( IHomefinderConstants::EMAIL_LOGO_OPTION)){echo(" style='display:none'");}?>/><br/>
			    	<?php
			    }
			    ?>
				<input id="ihf_upload_email_logo" type="text" size="36" name="<?php echo(IHomefinderConstants::EMAIL_LOGO_OPTION)?>" value="<?php echo(get_option(IHomefinderConstants::EMAIL_LOGO_OPTION))?>" />
          		<input id="ihf_upload_email_logo_button" type="button" value="Upload Logo" class="button-secondary"/>
			    <br/>
			    Enter an image URL or use an image from the Media Library
			    <br/><br/>
			    
			    <h3>Business Information</h3>
			    <div style="float:left;width:320px;">
				    <div style="float:left;width:90px;font-family: sans-serif;font-size: 12px;">Name:</div>
			    	<input type="text" size="36" name="<?php echo(IHomefinderConstants::EMAIL_NAME_OPTION)?>" value="<?php echo(get_option(IHomefinderConstants::EMAIL_NAME_OPTION))?>" />
		    	</div>
		    	<div style="float:left;width:320px;">
		    		<div style="float:left;width:90px;font-family: sans-serif;font-size: 12px;"">Company:</div>
		    		<input type="text" size="36" name="<?php echo(IHomefinderConstants::EMAIL_COMPANY_OPTION)?>" value="<?php echo(get_option(IHomefinderConstants::EMAIL_COMPANY_OPTION))?>" />
		    	</div>
		    	<div style="clear:both"></div>	

		    	<div style="float:left;width:320px;">
		    		<div style="float:left;width:90px;font-family: sans-serif;font-size: 12px;"">Address Line 1:</div>
		    		<input type="text" size="36" name="<?php echo(IHomefinderConstants::EMAIL_ADDRESS_LINE1_OPTION)?>" value="<?php echo(get_option(IHomefinderConstants::EMAIL_ADDRESS_LINE1_OPTION))?>" />
		    	</div>
		    	<div style="float:left;width:320px;">
		    		<div style="float:left;width:90px;font-family: sans-serif;font-size: 12px;"">Address Line 2:</div>
		    		<input type="text" size="36" name="<?php echo(IHomefinderConstants::EMAIL_ADDRESS_LINE2_OPTION)?>" value="<?php echo(get_option(IHomefinderConstants::EMAIL_ADDRESS_LINE2_OPTION))?>" />
		    	</div>		    	
		    	
		    	<div style="clear:both"></div>	
		    	
		    	<div style="float:left;width:320px;">
		    		<div style="float:left;width:90px;font-family: sans-serif;font-size: 12px;"">Phone:</div>
		    		<input type="text" size="36" name="<?php echo(IHomefinderConstants::EMAIL_PHONE_OPTION)?>" value="<?php echo(get_option(IHomefinderConstants::EMAIL_PHONE_OPTION))?>" />
		    	</div>

		    	
		    	<div style="clear:both"></div>	 	
		    		
			    <p class="submit">
			    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			    </p>
			    			    		    
			    <br/>
			    <input type="radio" name="<?php echo(IHomefinderConstants::EMAIL_DISPLAY_TYPE_OPTION)?>"
					<?php if( IHomefinderAdminEmailDisplay::EMAIL_DISPLAY_TYPE_CUSTOM_HTML_VALUE == $emailDisplayType ){echo(" checked ");}?>
			    	value="<?php echo(IHomefinderAdminEmailDisplay::EMAIL_DISPLAY_TYPE_CUSTOM_HTML_VALUE)?>">&nbsp;Custom HTML
			    <br/>
			    
				<p/>
				Insert custom HTML for your email header and footer.
			    
			    <h3>Email Header</h3>
				<?php
					$email_header_editor_settings =  array (
            			'textarea_rows' => 15,
            			'media_buttons' => TRUE,
            			'teeny'         => TRUE,
						'tinymce'       => TRUE,
						'textarea_name' => IHomefinderConstants::EMAIL_HEADER_OPTION
        			);
        			$emailHeaderContent='';
        			if($emailDisplayType == IHomefinderAdminEmailDisplay::EMAIL_DISPLAY_TYPE_CUSTOM_HTML_VALUE){
        				$emailHeaderContent=get_option(IHomefinderConstants::EMAIL_HEADER_OPTION);
        			}
					wp_editor( $emailHeaderContent, 'emailheaderid', $email_header_editor_settings );
				?>

				<br/>
				<h3>Email Footer</h3>
				<?php
					$email_footer_editor_settings =  array (
            			'textarea_rows' => 15,
            			'media_buttons' => TRUE,
            			'teeny'         => TRUE,
						'tinymce'       => TRUE,
						'textarea_name' => IHomefinderConstants::EMAIL_FOOTER_OPTION
        			);
		       		$emailFooterContent='';
        			if($emailDisplayType == IHomefinderAdminEmailDisplay::EMAIL_DISPLAY_TYPE_CUSTOM_HTML_VALUE){
        				$emailFooterContent=get_option(IHomefinderConstants::EMAIL_FOOTER_OPTION);
        			}
					wp_editor( $emailFooterContent, 'emailfooterid', $email_footer_editor_settings );
				?>

			    <p class="submit">
			    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			    </p>

			</form>
			</div>

			<?php
		}

		public function adminConfigurationForm(){
			if (!current_user_can('manage_options'))  {
				wp_die( __('You do not have sufficient permissions to access this page.') );
			}

			//On Update, push the CSS_OVERRIDE_OPTION to iHomefinder
			if($this->isUpdated()){
				//call function here to pass the activation key to ihf and update the CSS Override value
				$this->updateAuthenticationToken();
			}
			?>

			<div class="wrap">
				<?php $responsive=IHomefinderLayoutManager::getInstance()->isResponsive(); ?>
				<h2>Configuration</h2>
				<form method="post" action="options.php">
					<?php settings_fields( IHomefinderConstants::OPTION_CONFIG_PAGE ); ?>
					<table class="form-table">
						<?php if(!IHomefinderPermissions::getInstance()->isOfficeEnabled() && !IHomefinderPermissions::getInstance()->isOmnipressSite()){?>
						<tr valign="top">
							<th scope="row">Layout Style</th>
							<td>
								<select name="<?php echo IHomefinderConstants::OPTION_LAYOUT_TYPE ?>" onchange="if(this.value == '<?php echo IHomefinderConstants::OPTION_LAYOUT_TYPE_RESPONSIVE ?>'){alert('Please note that this is a beta version of Optima Express which is still undergoing final testing before its official release. You may encounter errors or bugs - if you do, feel free to email us at support@ihomefinder.com')}">
									<option value="<?php echo IHomefinderConstants::OPTION_LAYOUT_TYPE_LEGACY ?>" <?php if(!$responsive){?>selected <?php }?>>Legacy</option>
									<option value="<?php echo IHomefinderConstants::OPTION_LAYOUT_TYPE_RESPONSIVE ?>" <?php if($responsive){?>selected <?php }?>>Beta - Responsive</option>
								</select>
							</td>
						</tr>
						<?php }?>
						<?php
						if( IHomefinderLayoutManager::getInstance()->supportsColorScheme() ) {
						?>
						<tr valign="top">
							<th scope="row">Button Color</th>
							<td>
								<?php $colorScheme=get_option(IHomefinderConstants::COLOR_SCHEME_OPTION) ?>
								<select name="<?php echo IHomefinderConstants::COLOR_SCHEME_OPTION ?>">
									<option value="gray" <?php if($colorScheme=='gray'){?>selected <?php }?>>Gray</option>
									<option value="red" <?php if($colorScheme=='red'){?>selected <?php }?>>Red</option>
									<option value="green" <?php if($colorScheme=='green'){?>selected <?php }?>>Green</option>
                  <option value="orange" <?php if($colorScheme=='orange'){?>selected <?php }?>>Orange</option>
									<option value="blue" <?php if($colorScheme=='blue'){?>selected <?php }?>>Blue</option>
									<option value="light_blue" <?php if($colorScheme=='light_blue'){?>selected <?php }?>>Light Blue</option>
									<option value="blue_gradient" <?php if($colorScheme=='blue_gradient'){?>selected <?php }?>>Blue Gradient</option>
								</select>
							</td>
						</tr>
						<?php
						}
						?>
						<tr valign="top">
							<th scope="row">CSS Override</th>
							<td>
								<p>To redefine an Optima Express style, paste the edited style below.</p>
								<textarea name="<?php echo IHomefinderConstants::CSS_OVERRIDE_OPTION ?>" style="width: 100%; height: 300px; "><?php echo get_option(IHomefinderConstants::CSS_OVERRIDE_OPTION); ?></textarea>
							</td>
						</tr>
					</table>
					<p class="submit">
						<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
					</p>
				</form>
			</div>
			<?php
		}

		private function updateCommunityPages($title, $cityZip, $propertyType, $bed, $bath, $minPrice, $maxPrice ){
			$errorMessage="";
			if( $cityZip == null ||  $cityZip == ''){
				$errorMessage .= 'Please select a location<br/>' ;
			}
			if( $title == null ||  $title == ''){
				$errorMessage .=  'Please enter a title' ;
			}
			if($errorMessage == ""){
				$shortCode=IHomefinderShortcodeDispatcher::getInstance()->buildSearchResultsShortCode($cityZip, $propertyType, $bed, $bath, $minPrice, $maxPrice);

				$post = array(
				  'comment_status' => 'closed' ,// 'closed' means no comments.
				  'ping_status'    => 'closed', // 'closed' means pingbacks or trackbacks turned off
				  'post_content'   =>  $shortCode, //The full text of the post.
				  'post_name'      => $title, // The name (slug) for your post
				  'post_status'    => 'publish',  //Set the status of the new post.
				  'post_title'     => $title, //The title of your post.
				  'post_type'      => 'page' //You may want to insert a regular post, page, link, a menu item or some custom post type
				);

				$postId = wp_insert_post( $post );
				IHomefinderMenu::getInstance()->addPageToCommunityPages($postId);

			}

			return $errorMessage ;
		}

		public function communityPagesForm(){

			$errorMessage=false;

			//On Update, push the CSS_OVERRIDE_OPTION to iHomefinder
			if($this->isUpdated()){
				//call function here to pass the activation key to ihf and update the CSS Override value
				$title=$_REQUEST["ihfPageTitle"] ;
				$cityZip=$_REQUEST["cityZip"]  ;
				$propertyType=$_REQUEST["propertyType"]  ;
				$bed=$_REQUEST["bed"]  ;
				$bath=$_REQUEST["bath"]  ;
				$minPrice=$_REQUEST["minPrice"]  ;
				$maxPrice=$_REQUEST["maxPrice"]  ;

				$errorMessage=$this->updateCommunityPages($title, $cityZip, $propertyType, $bed, $bath, $minPrice, $maxPrice );
			}


?>
			<div class="wrap">
			<h2>Community Pages</h2>
			<div style="float:left; padding-right: 40px;">
				<h3>Create a new Community Page</h3>
				<div>Enter search criteria to create a new page under the Community Pages menu.</div>

				<?php
					if($errorMessage){
						echo('<br/>' . $errorMessage . '<br/>');
					}
				?>
				<form method="post">
					<input type="hidden" name="updated" value="true"/>
					<?php settings_fields( IHomefinderConstants::COMMUNITY_PAGES ); ?>

				    <div style="margin: 10px;">

				    </div>

				    <div  style="float:left; margin: 10px;">
				    	<b>Location:</b><br/>
				    	<div style="padding-bottom: 9px;"><?php $this->createCityZipAutoComplete()?></div>
						<b>Page Title:</b><br/>
				    	<div style="padding-bottom: 9px;"><input type="text" id="ihfPageTitle" name="ihfPageTitle"/></div>
				    	<b>Property Type:</b><br/>
				    	<div><?php $this->createPropertyTypeSelect()?></div>
				    </div>

				    <div  style="float:left; margin: 10px;">
						<b>Bed:</b><br/>
						<div><input type="text" name="bed" /></div>
						<b>Bath:</b><br/>
						<div><input type="text" name="bath" /></div>
						<b>Min Price:</b><br/>
						<div><input type="text" name="minPrice" /></div>
						<b>Max Price:</b><br/>
						<div><input type="text" name="maxPrice" /></div>

				    </div>
				    <div style="clear:both;"></div>
				    <p class="submit">
				    <input type="submit" class="button-primary" value="<?php _e('Save') ?>" />
				    </p>

				</form>
			</div>
			<div style="float: left">
				<h3>Existing Community Pages</h3>
				<div style="padding-bottom: 9px;">Click the page name to edit Community Page content.</div>
				<div style="padding-bottom: 9px;">Change or edit the links that appear within the <a href="<?php echo(site_url())?>/wp-admin/nav-menus.php">Menus</a> section.</div>
				<?php

					$communityPageMenuItems=IHomefinderMenu::getInstance()->getCommunityPagesMenuItems();
					echo('<ul>');
					foreach((array) $communityPageMenuItems as $key => $menu_item){
						echo('<li>');
						echo('<a href="post.php?post=' . $menu_item->object_id . '&action=edit">');
						echo( $menu_item->title );
						echo('</a>');
						echo('</li>');
					}
					echo('</ul>');
				?>

			</div>

			<div>

			</div>
			</div>
			<?php
		}

		public function adminOptionsPagesForm(){
			$permissions=IHomefinderPermissions::getInstance();
			if($this->isUpdated()){
				//call function here will re-activate the plugin and re-register the new URL patterns
				$this->updateAuthenticationToken();
			}
			$pageConfig=IHomefinderAdminPageConfig::getInstance() ;
			?>
			<div class="wrap">
				<h2>IDX Pages</h2>
				<br/>
				<div>

				<form method="post" action="options.php">
					<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
				    <?php settings_fields( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG ); ?>

					<?php

						$pageConfig->getDetailPageSetup();
						echo('<p/>');

						$pageConfig->getSearchPageSetup();
						echo('<p/>');
            
						if( $permissions->isMapSearchEnabled()){
							$pageConfig->getMapSearchPageSetup() ;
							echo('<p/>');
						}

						$pageConfig->getAdvSearchPageSetup() ;
						echo('<p/>');

						if( $permissions->isOrganizerEnabled()){
							$pageConfig->getOrganizerLoginPageSetup();
							echo('<p/>');
						}

						if( $permissions->isEmailUpdatesEnabled()){
							$pageConfig->getEmailAlertsPageSetup() ;
							echo('<p/>');
						}

						if( $permissions->isFeaturedPropertiesEnabled()){
							$pageConfig->getFeaturedPageSetup() ;
							echo('<p/>');
						}

						if( $permissions->isHotSheetEnabled()){
							$pageConfig->getHotsheetPageSetup() ;
							echo('<p/>');
						}

						$pageConfig->getContactFormPageSetup();
						echo('<p/>');

						$pageConfig->getValuationFormPageSetup();
						echo('<p/>');

						$pageConfig->getOpenHomeSearchFormPageSetup();
						echo('<p/>');

						if( $permissions->isSupplementalListingsEnabled()){
							$pageConfig->getSupplementalListingPageSetup();
							echo('<p/>');
						}

						if( $permissions->isSoldPendingEnabled()){
							$pageConfig->getSoldFeaturedListingPageSetup() ;
							echo('<p/>');

							$pageConfig->getSoldDetailPageSetup();
							echo('<p/>');
						}

						if( $permissions->isOfficeEnabled()){
							$pageConfig->getOfficeListPageSetup();
							echo('<p/>');

							$pageConfig->getOfficeDetailPageSetup();
							echo('<p/>');
						}

						if( $permissions->isAgentBioEnabled()){
							$pageConfig->getAgentListPageSetup();
							echo('<p/>');

							$pageConfig->getAgentDetailPageSetup();
							echo('<p/>');
						}

						$pageConfig->getDefaultPageSetup();

					?>

					<div>* Template selection is compatible only with select themes.</div>
					<p class="submit">
				    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
				    </p>
				</form>
				</div>
			</div>
			<?php
		}


		private function createCityZipAutoComplete(){
			$galleryFormData=IHomefinderShortcodeDispatcher::getInstance()->getGalleryFormData();
			$cityZipList=$galleryFormData->getCityZipList();
            $cityZipListJson=json_encode($cityZipList);

  			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-ui-core');
			wp_enqueue_script('jquery-ui-autocomplete', '', array('jquery-ui-widget', 'jquery-ui-position'), '1.8.6');
			wp_enqueue_style( 'jquery-ui-autocomplete', plugins_url( 'css/jquery-ui-1.8.18.custom.css', __FILE__ ) );
			?>

			<script type="text/JavaScript">
				jQuery(document).ready(function() {
					jQuery("input#ihfCommunityPagesAutoComplete").focus(function(){jQuery("input#ihfCommunityPagesAutoComplete").val("");});
					jQuery("input#ihfCommunityPagesAutoComplete").autocomplete({
						autoFocus: true,
						source: function(request,response){
							var data=<?php echo($cityZipListJson);?>;
							var searchTerm=request.term ;
							searchTerm=searchTerm.toLowerCase();
							var results=new Array();
							for(var i=0; i<data.length;i++){
								var oneTerm=data[i];
								var value=oneTerm.value + '';
								value=value.toLowerCase();
								if( value && value != null && value.indexOf( searchTerm ) == 0 ){
									results.push(oneTerm);
								}
							}
							response(results);
						},
						select: function(event, ui){
							//When an item is selected, set the text value for the link
							jQuery('#ihfPageTitle').val(ui.item.label);
						},
						selectFirst: true
					});
				});
			</script>
			<input type="text" id="ihfCommunityPagesAutoComplete"
            		name="cityZip"
            		value="Enter City - OR - Zipcode"
            		size="30"/>
			<?php
		}

		private function createPropertyTypeSelect(){
			$formData=IHomefinderShortcodeDispatcher::getInstance()->getGalleryFormData();
			if( isset( $formData) && isset( $formData->propertyTypesList)){
				$propertyTypesList=$formData->propertyTypesList ;
				$selectText = "<SELECT id='propertyType' name='propertyType'>";
				foreach ($propertyTypesList as $i => $value) {
					$selectText .= "<option value='" . $propertyTypesList[$i]->propertyTypeCode . "'>";
					$selectText .=  $propertyTypesList[$i]->displayName ;
					$selectText .=  "</option>" ;
				}
				$selectText .= "</SELECT>";
				echo($selectText);
			}
		}
	}
}//end if class_exists('IHomefinderAdmin')




?>