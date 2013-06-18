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

		public function createAdminMenu(){
			$permissions=IHomefinderPermissions::getInstance() ;
			add_menu_page('Optima Express', 'Optima Express', 'manage_options', 'ihf_idx', array( $this, 'adminOptionsForm' ));
            add_submenu_page( 'ihf_idx', 'Information', 'Information', 'manage_options', 'ihf_idx', array( &$this, 'adminOptionsForm'));
            add_submenu_page( 'ihf_idx', 'Register', 'Register', 'manage_options', IHomefinderConstants::OPTION_ACTIVATE, array( &$this, 'adminOptionsActivateForm'));
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
                            Enter your Registration Key to register your plugin on this page. You must obtain a Registration Key through iHomefinder.

                            <h3>IDX Pages</h3>
                            View and configure your Optima Express IDX pages here. Change permalinks, page titles and templates.

                            <h3>Configuration</h3>
                            This page provides customization features including the ability to override default styles for Optima Express.

                            <h3>Bio Widget</h3>
                            Setup your Bio informmation.  Upload a photo and insert contact information.

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
				$authenticationInfo=$this->activate($activationToken);

				$authenticationToken = '';
				if( $authenticationInfo->authenticationToken ){
					$authenticationToken = $authenticationInfo->authenticationToken;
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

			$officeListUrl                        = urlencode($urlFactory->getOfficeListUrl(true));
			$officeDetailUrl                      = urlencode($urlFactory->getOfficeDetailUrl(true));
			$agentBioListUrl                      = urlencode($urlFactory->getAgentListUrl(true));
			$agentBioDetailUrl                    = urlencode($urlFactory->getAgentDetailUrl(true));

			//Push CSS Override to iHomefinder
			$cssOverride = get_option(IHomefinderConstants::CSS_OVERRIDE_OPTION);
			$cssOverride = urlencode( $cssOverride);

			$emailHeader=IHomefinderAdminEmailDisplay::getInstance()->getHeader() ;
			$emailHeader = urlencode( $emailHeader);

			$emailFooter=IHomefinderAdminEmailDisplay::getInstance()->getFooter() ;
			$emailFooter = urlencode( $emailFooter);

			$ihfUrl = IHomefinderConstants::EXTERNAL_URL  ;
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
				'officeListUrl'=> $officeListUrl,
				'officeDetailUrl'=> $officeDetailUrl,
				'agentBioListUrl'=> $agentBioListUrl,
				'agentBioDetailUrl'=> $agentBioDetailUrl,
				'cssOverride'=> $cssOverride,
				'emailHeader'=> $emailHeader,
				'emailFooter'=> $emailFooter
			);

			IHomefinderLogger::getInstance()->debug( '$ihfUrl:::' . $ihfUrl ) ;
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
			register_setting( IHomefinderConstants::OPTION_CONFIG_PAGE, IHomefinderConstants::CSS_OVERRIDE_OPTION );

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
			if (!current_user_can('manage_options'))  {
				wp_die( __('You do not have sufficient permissions to access this page.') );
			}

			if($this->isUpdated()){
				//call function here to pass the activation key to ihf and get
				//an authentication token
				$this->updateAuthenticationToken();
			}
			$now = time();
			//expire the authentication token after a week.
			//Then we need to request a new authentication token, using the same activation token
			$expireTime = $now + (7 * 24 * 60 * 60);
	?>
				<div class="wrap">
				<h2>Register</h2>

				<form method="post" action="options.php">
				    <?php settings_fields( IHomefinderConstants::OPTION_ACTIVATE ); ?>

				    <table class="form-table">
				        <tr valign="top">
				        <th scope="row">Registration Key</th>
				        <td>
				        	<input type="text" name="<?php echo IHomefinderConstants::ACTIVATION_TOKEN_OPTION ?>" value="<?php echo get_option(IHomefinderConstants::ACTIVATION_TOKEN_OPTION); ?>" />
				        	<input type="hidden" name="<?php echo IHomefinderConstants::ACTIVATION_DATE_OPTION ?>" value="<?php echo $now?>" />
				        </td>
				        </tr>

				        <tr valign="top">
				            <td></td>
				        	<td>
				        		<?php if( false !== $this->getAuthenticationToken() ){   ?>
				        			<?php if( $this->isUpdated() ){?>
				        				Your Optima Express plugin has been updated.
				        			<?php } else  {?>
				        				Your Optima Express plugin has been registered.
				        			<?php } ?>
				        		<?php } else  {?>
				        			Add your Registration Key and click "Save Changes" to get started with Optima Express.
				        		<?php } ?>
				        	</td>
				        </tr>

				    </table>

				    <p class="submit">
				    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
				    </p>

				</form>
				</div>
	<?php 	}

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
            $propertyTypesList=$galleryFormData->propertyTypesList ;
            $cityZipList=$galleryFormData->cityZipList;
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
            		value="Enter City - OR - Zipcode"
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
			<h2>Configuration</h2>
			<form method="post" action="options.php">
			    <?php settings_fields( IHomefinderConstants::OPTION_CONFIG_PAGE ); ?>

			    <div><b>CSS Override</b></div>
			    <div>
			    To redefine an Optima Express style, paste the edited style below.
			    </div>
			    <div>
			    	<textarea name="<?php echo IHomefinderConstants::CSS_OVERRIDE_OPTION ?>" rows="15" cols="100"><?php echo get_option(IHomefinderConstants::CSS_OVERRIDE_OPTION); ?></textarea>
			    </div>

			    <p class="submit">
			    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			    </p>

			</form>
			</div>
<?php 	}

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
<?php 	}

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

						$pageConfig->getSearchPageSetup() ;
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
			$cityZipList=$galleryFormData->cityZipList;
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
								//debugger;
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