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
                    add_menu_page('Optima Express', 'Optima Express', 'manage_options', 'ihf_idx', array( $this, 'adminOptionsForm' ));
                    add_submenu_page( 'ihf_idx', 'Information', 'Information', 'manage_options', 'ihf_idx', array( &$this, 'adminOptionsForm'));
                    add_submenu_page( 'ihf_idx', 'Register', 'Register', 'manage_options', IHomefinderConstants::OPTION_ACTIVATE, array( &$this, 'adminOptionsActivateForm'));
                    add_submenu_page( 'ihf_idx', 'IDX Pages', 'IDX Pages', 'manage_options', IHomefinderConstants::OPTION_PAGES, array( &$this, 'adminOptionsPagesForm'));
                    add_submenu_page( 'ihf_idx', 'Configuration', 'Configuration', 'manage_options', IHomefinderConstants::OPTION_CONFIG_PAGE, array( &$this, 'adminConfigurationForm'));
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

                            <b>Version <?php echo IHomefinderConstants::VERSION ?></b>
                            <br/><br/>
                            <b>Register:</b>
                            Enter your Registration Key to register your plugin on this page. You must obtain a Registration Key through iHomefinder.
                            <br/><br/>
                            <b>IDX Pages:</b> View and configure your Optima Express IDX pages here. Change permalinks, page titles and templates.
                            <br/><br/>
                            <b>Configuration:</b>
                            This page provides customization features including the ability to override default styles for Optima Express.
                            <br/><br/>
                        </div>
                    </div>
                    <?php
		}

		public function updateAuthenticationToken(){
			$activationToken=get_option(IHomefinderConstants::ACTIVATION_TOKEN_OPTION);
			if($activationToken != null && "" != $activationToken){
				$authenticationInfo=$this->activate($activationToken);

				$authenticationToken = '';
				if( $authenticationInfo->authenticationToken ){
					$authenticationToken = $authenticationInfo->authenticationToken;
					$permissions = $authenticationInfo->permissions;

					IHomefinderLogger::getInstance()->debug( 'authenticationToken' . $authenticationToken ) ;
					IHomefinderPermissions::getInstance()->initialize( $permissions );

					if( !$this->previouslyActivated()){
						update_option(IHomefinderConstants::IS_ACTIVATED_OPTION,'true');
					}
				}
				update_option(IHomefinderConstants::AUTHENTICATION_TOKEN_CACHE, $authenticationToken);
			}
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

//		public function synchAuthenticationToken(){
//			$authenticationToken = get_transient(IHomefinderConstants::AUTHENTICATION_TOKEN_CACHE);
//			if( false === $authenticationToken  || '' === $authenticationToken)	{
//				$this->updateAuthenticationToken();
//			}
//		}

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
			$soldFeaturedListingUrl              = urlencode($urlFactory->getSoldFeaturedListingUrl(true));
			$supplementalListingUrl              = urlencode($urlFactory->getSupplementalListingUrl(true));
			
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
				'supplementalListingUrl'=> $supplementalListingUrl
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

			//Register Virtual Page related groups and options
			IHomefinderVirtualPageHelper::getInstance()->registerOptions() ;

		}

		//Check if an options form has been updated.
		private function isUpdated(){
			//When new options are updated, the paramerter "updated" is set to true
			$isUpdated = ( array_key_exists('updated', $_REQUEST) && $_REQUEST["updated"] ) ;
			if(!$isUpdated){
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

		public function adminConfigurationForm(){
			if (!current_user_can('manage_options'))  {
				wp_die( __('You do not have sufficient permissions to access this page.') );
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

		public function adminOptionsPagesForm(){
			$permissions=IHomefinderPermissions::getInstance();
			if($this->isUpdated()){
				//call function here will re-activate the plugin and re-register the new URL patterns
				$this->updateAuthenticationToken();
			}
        ?>
			<div class="wrap">
				<h2>IDX Pages</h2>
				<br/>
				<div>

				<form method="post" action="options.php">
					<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
				    <?php settings_fields( IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_CONFIG ); ?>

					<?php

						$this->getDetailPageSetup();
						echo('<p/>');

						$this->getSearchPageSetup() ;
						echo('<p/>');

						$this->getAdvSearchPageSetup() ;
						echo('<p/>');

						if( $permissions->isOrganizerEnabled()){
							$this->getOrganizerLoginPageSetup();
							echo('<p/>');
						}

						if( $permissions->isEmailUpdatesEnabled()){
							$this->getEmailAlertsPageSetup() ;
							echo('<p/>');
						}

						if( $permissions->isFeaturedPropertiesEnabled()){
							$this->getFeaturedPageSetup() ;
							echo('<p/>');
						}

						if( $permissions->isHotSheetEnabled()){
							$this->getHotsheetPageSetup() ;
							echo('<p/>');
						}

						$this->getContactFormPageSetup(); 	
						echo('<p/>');	
			
						$this->getValuationFormPageSetup();
						echo('<p/>');	

						$this->getOpenHomeSearchFormPageSetup();				
						echo('<p/>');							
						
						$this->getSupplementalListingPageSetup();
						echo('<p/>');	

						$this->getSoldFeaturedListingPageSetup() ;					
						echo('<p/>');
						
						$this->getSoldDetailPageSetup();
						echo('<p/>');
						
						$this->getDefaultPageSetup();

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

		private function getDefaultPageSetup(){
			$selectedTemplate=IHomefinderVirtualPageHelper::getInstance()->getDefaultTemplate() ;
			?>
			<h3>Other IDX Pages</h3>
			<table>
				<tr>
					<td><b>Theme Template*:</b></td>
					<td>
						<select name="<?php echo(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_DEFAULT)?>">
							<option value='default'><?php _e('Default Template'); ?></option>
							<?php page_template_dropdown( $selectedTemplate ); ?>
						</select>
					</td>
				</tr>
			</table>
		<?php
		}

		/**
		 * We do not use getPageSetup to display the detail page customization, because we require some 
		 * extra explanation of the permalink structure for detail pages.
		 * Otherwisse, this function is similar to getPageSetup.
		 */
		private function getDetailPageSetup(){
			$urlFactory = IHomefinderUrlFactory::getInstance() ;
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::LISTING_DETAIL );
			$permalinkId=IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_DETAIL ;
		?>
			<h3>Property Details</h3>
			<table>
				<tr>
					<td><b>Permalink:</b></td>
					<td>
					  <div id="<?php echo($permalinkId)?>Container">
					  	<?php echo $urlFactory->getBaseUrl()?>/<span id="<?php echo($permalinkId)?>Text"><?php echo $urlFactory->getListingDetailUrl(false)?></span>
					  	/%ADDRESS%/%LISTING_NUMBER%/%LISTING_PROVIDER%
					  	<input id="<?php echo($permalinkId)?>EditButton" type="button" value="Edit">
					  </div>
					  <div id="<?php echo($permalinkId)?>Edit" style="display: none;" >
						<?php echo $urlFactory->getBaseUrl()?>/
						<input size="40"
							type="text"
							id="<?php echo($permalinkId)?>"
							name="<?php echo($permalinkId)?>"
							value="<?php echo $urlFactory->getListingDetailUrl(false)?>" />
						/%ADDRESS%/%LISTING_NUMBER%/%LISTING_PROVIDER%
						<input id="<?php echo($permalinkId)?>DoneButton" type="button" value="Done">
					  </div>
					</td>
				</tr>
				<tr>
					<td><b>Title:</b></td>
					<td>
						<input type="text" name="<?php echo(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_DETAIL)?>" value="<?php echo($virtualPage->getTitle())?>" />
						(If empty, the property address will be the title)
					</td>
				</tr>
				<tr>
					<td><b>Theme Template*:</b></td>
					<td>
						<select name="<?php echo(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_DETAIL)?>">
							<option value='default'><?php _e('Default Template'); ?></option>
							<?php page_template_dropdown($virtualPage->getPageTemplate()); ?>
						</select>
					</td>
				</tr>
			</table>
		<?php
			$this->permalinkJavascript( $permalinkId, $urlFactory );
		}
		
		/**
		 * We do not use getPageSetup to display the detail page customization, because we require some 
		 * extra explanation of the permalink structure for detail pages.
		 * Otherwisse, this function is similar to getPageSetup.
		 */
		private function getSoldDetailPageSetup(){
			$urlFactory = IHomefinderUrlFactory::getInstance() ;
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::LISTING_SOLD_DETAIL );
			$permalinkId=IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SOLD_DETAIL ;
		?>
			<h3>Sold Property Details</h3>
			<table>
				<tr>
					<td><b>Permalink:</b></td>
					<td>
					  <div id="<?php echo($permalinkId)?>Container">
					  	<?php echo $urlFactory->getBaseUrl()?>/<span id="<?php echo($permalinkId)?>Text"><?php echo $urlFactory->getListingSoldDetailUrl(false)?></span>
					  	/%ADDRESS%/%LISTING_NUMBER%/%LISTING_PROVIDER%
					  	<input id="<?php echo($permalinkId)?>EditButton" type="button" value="Edit">
					  </div>
					  <div id="<?php echo($permalinkId)?>Edit" style="display: none;" >
						<?php echo $urlFactory->getBaseUrl()?>/
						<input size="40"
							type="text"
							id="<?php echo($permalinkId)?>"
							name="<?php echo($permalinkId)?>"
							value="<?php echo $urlFactory->getListingSoldDetailUrl(false)?>" />
						/%ADDRESS%/%LISTING_NUMBER%/%LISTING_PROVIDER%
						<input id="<?php echo($permalinkId)?>DoneButton" type="button" value="Done">
					  </div>
					</td>
				</tr>
				<tr>
					<td><b>Title:</b></td>
					<td>
						<input type="text" name="<?php echo(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_SOLD_DETAIL)?>" value="<?php echo($virtualPage->getTitle())?>" />
						(If empty, the property address will be the title)
					</td>
				</tr>
				<tr>
					<td><b>Theme Template*:</b></td>
					<td>
						<select name="<?php echo(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_SOLD_DETAIL)?>">
							<option value='default'><?php _e('Default Template'); ?></option>
							<?php page_template_dropdown($virtualPage->getPageTemplate()); ?>
						</select>
					</td>
				</tr>
			</table>
		<?php
			$this->permalinkJavascript( $permalinkId, $urlFactory );
		}		

		private function getSearchPageSetup(){
			$urlFactory = IHomefinderUrlFactory::getInstance() ;
			$this->getPageSetup("Search Form", IHomefinderVirtualPageFactory::LISTING_SEARCH_FORM, 
				IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SEARCH,
				$urlFactory->getListingsSearchFormUrl(false), 
				IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_SEARCH, 
				IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_SEARCH);		
							
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::LISTING_SEARCH_FORM );
			$permalinkId=IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SEARCH ;
		}

		private function permalinkJavascript( $permalinkId, $urlFactory ){
			?>
			<script>
				jQuery('#<?php echo($permalinkId)?>EditButton').click( function(){
					jQuery('#<?php echo($permalinkId)?>Edit').show();
					jQuery('#<?php echo($permalinkId)?>Container').hide();
				});
				jQuery('#<?php echo($permalinkId)?>DoneButton').click( function(){
					var inputObject=jQuery('#<?php echo($permalinkId)?>');
					var inputValue = inputObject.val();
					inputValue = inputValue.replace(/\s/g,"-");
					inputObject.val(inputValue);

					jQuery('#<?php echo($permalinkId)?>Text').text( jQuery('#<?php echo($permalinkId)?>').val() );
					jQuery('#<?php echo($permalinkId)?>Container').show();
					jQuery('#<?php echo($permalinkId)?>Edit').hide();
				});
			</script>
			<?php
		}

		private function getAdvSearchPageSetup(){
			$urlFactory = IHomefinderUrlFactory::getInstance() ;
			$this->getPageSetup("Advanced Search Form", IHomefinderVirtualPageFactory::LISTING_ADVANCED_SEARCH_FORM, 
				IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_ADV_SEARCH, 
				$urlFactory->getListingsAdvancedSearchFormUrl(false), 
				IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_ADV_SEARCH, 
				IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_ADV_SEARCH);		
		}

		private function getOrganizerLoginPageSetup(){
			$urlFactory = IHomefinderUrlFactory::getInstance() ;
			$this->getPageSetup("Organizer Login", IHomefinderVirtualPageFactory::ORGANIZER_LOGIN, 
				IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_ORG_LOGIN, 
				$urlFactory->getOrganizerLoginUrl(false), 
				IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_ORG_LOGIN, 
				IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_ORG_LOGIN);						
		}

		private function getEmailAlertsPageSetup(){
			$urlFactory = IHomefinderUrlFactory::getInstance() ;
			$this->getPageSetup("Email Alerts", IHomefinderVirtualPageFactory::ORGANIZER_EDIT_SAVED_SEARCH, 
				IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_EMAIL_UPDATES, 
				$urlFactory->getOrganizerEditSavedSearchUrl(false), 
				IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_EMAIL_UPDATES, 
				IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_EMAIL_UPDATES);							
		}

		private function getFeaturedPageSetup(){
			$urlFactory = IHomefinderUrlFactory::getInstance() ;
			$this->getPageSetup("Featured Properties", IHomefinderVirtualPageFactory::FEATURED_SEARCH, 
				IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_FEATURED, 
				$urlFactory->getFeaturedSearchResultsUrl(false), 
				IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_FEATURED, 
				IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_FEATURED);
		}		
		
		private function getContactFormPageSetup(){
			$urlFactory = IHomefinderUrlFactory::getInstance() ;
			$this->getPageSetup( "Contact Form",
				IHomefinderVirtualPageFactory::CONTACT_FORM, 
				IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_CONTACT_FORM, 
				$urlFactory->getContactFormUrl(false),
				IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_CONTACT_FORM,
				IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_CONTACT_FORM	);		
		}	
		
		private function getValuationFormPageSetup(){
			$urlFactory = IHomefinderUrlFactory::getInstance() ;
			$this->getPageSetup( "Valuation Request",
				IHomefinderVirtualPageFactory::VALUATION_FORM, 
				IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_VALUATION_FORM, 
				$urlFactory->getValuationFormUrl(false),
				IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_VALUATION_FORM,
				IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_VALUATION_FORM	); 				
		}
		
		private function getSupplementalListingPageSetup(){
			$urlFactory = IHomefinderUrlFactory::getInstance() ;
			$this->getPageSetup( "Supplemental Listing",
				IHomefinderVirtualPageFactory::SUPPLEMENTAL_LISTING, 
				IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SUPPLEMENTAL_LISTING, 
				$urlFactory->getSupplementalListingUrl(false),
				IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_SUPPLEMENTAL_LISTING,
				IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_SUPPLEMENTAL_LISTING	); 			
		}
		
		private function getSoldFeaturedListingPageSetup(){
			$urlFactory = IHomefinderUrlFactory::getInstance() ;
			$this->getPageSetup( "Sold Featured Listing",
				IHomefinderVirtualPageFactory::SOLD_FEATURED_LISTING, 
				IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SOLD_FEATURED, 
				$urlFactory->getSoldFeaturedListingUrl(false),
				IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_SOLD_FEATURED,
				IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_SOLD_FEATURED	); 			
		}
		
		private function getOpenHomeSearchFormPageSetup(){
			$urlFactory = IHomefinderUrlFactory::getInstance() ;
			$this->getPageSetup( "Open Home Search",
				IHomefinderVirtualPageFactory::OPEN_HOME_SEARCH_FORM, 
				IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_OPEN_HOME_SEARCH_FORM, 
				$urlFactory->getOpenHomeSearchFormUrl(false),
				IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_OPEN_HOME_SEARCH_FORM,
				IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_OPEN_HOME_SEARCH_FORM	); 				
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
		private function getPageSetup($pageTitle, $virtualPageKey, $permalinkId, $currentUrl, $titleOption, $templateOption) {
			$urlFactory = IHomefinderUrlFactory::getInstance() ;
			$virtualPage = $this->virtualPageFactory->getVirtualPage( $virtualPageKey  );
		?>

			<h3><?php echo($pageTitle)?></h3>
			<table>
				<tr>
					<td><b>Permalink:</b></td>
					<td>
					  <div id="<?php echo($permalinkId)?>Container">
					  	<?php echo $urlFactory->getBaseUrl()?>/<span id="<?php echo($permalinkId)?>Text"><?php echo $currentUrl?></span>/
					  	<input id="<?php echo($permalinkId)?>EditButton" type="button" value="Edit">
					  </div>
					  <div id="<?php echo($permalinkId)?>Edit" style="display: none;" >
						<?php echo $urlFactory->getBaseUrl()?>/
						<input size="40"
							type="text"
							id="<?php echo($permalinkId)?>"
							name="<?php echo($permalinkId)?>"
							value="<?php echo $currentUrl?>" />/
						<input id="<?php echo($permalinkId)?>DoneButton" type="button" value="Done">
					  </div>
					</td>
				</tr>
				<tr>
					<td><b>Title:</b></td>
					<td>
						<input type="text" name="<?php echo($titleOption)?>" value="<?php echo($virtualPage->getTitle())?>" />
					</td>
				</tr>
				<tr>
					<td><b>Theme Template*:</b></td>
					<td>
						<select name="<?php echo($templateOption)?>">
							<option value='default'><?php _e('Default Template'); ?></option>
							<?php page_template_dropdown($virtualPage->getPageTemplate()); ?>
						</select>
					</td>
				</tr>
			</table>

		<?php
			$this->permalinkJavascript( $permalinkId, $urlFactory );
		}		
			
		
		
		private function getHotsheetPageSetup(){
			$urlFactory = IHomefinderUrlFactory::getInstance() ;
			
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::HOTSHEET_SEARCH_RESULTS );
			$permalinkId=IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_HOTSHEET ;
			$hotsheetListVirtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::HOTSHEET_LIST );
		?>
			<h3>Top Picks Index</h3>
			<table>
				<tr>
					<td><b>Permalink:</b></td>
					<td>
					  <div id="<?php echo($permalinkId)?>Container">
					  	<?php echo($urlFactory->getHotsheetListUrl(true))?>
					  </div>
					</td>
				</tr>
				<tr>
					<td><b>Title:</b></td>
					<td>
					  <?php echo($hotsheetListVirtualPage->getTitle())?>
					</td>
				</tr>
			</table>

			<h3>Top Picks</h3>
			<table>
				<tr>
					<td><b>Permalink:</b></td>
					<td>
					  <div id="<?php echo($permalinkId)?>Container">
					  	<?php echo $urlFactory->getBaseUrl()?>/<span id="<?php echo($permalinkId)?>Text"><?php echo $urlFactory->getHotsheetSearchResultsUrl(false)?></span>/
					  	<input id="<?php echo($permalinkId)?>EditButton" type="button" value="Edit">
					  </div>
					  <div id="<?php echo($permalinkId)?>Edit" style="display: none;" >
						<?php echo $urlFactory->getBaseUrl()?>/
						<input size="40"
							type="text"
							id="<?php echo($permalinkId)?>"
							name="<?php echo($permalinkId)?>"
							value="<?php echo( $urlFactory->getHotsheetSearchResultsUrl(false))?>" />/
						<input id="<?php echo($permalinkId)?>DoneButton" type="button" value="Done">
					  </div>
					</td>
				</tr>
				<tr>
					<td><b>Title:</b></td>
					<td>
						<input type="text" name="<?php echo(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_HOTSHEET)?>" value="<?php echo($virtualPage->getTitle())?>" />
						(If empty, the name of the Top Picks list will be the title)
					</td>
				</tr>
				<tr>
					<td><b>Theme Template*:</b></td>
					<td>
						<select name="<?php echo(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_HOTSHEET)?>">
							<option value='default'><?php _e('Default Template'); ?></option>
							<?php page_template_dropdown($virtualPage->getPageTemplate()); ?>
						</select>
					</td>
				</tr>
			</table>

		<?php
			$this->permalinkJavascript( $permalinkId, $urlFactory );
		}




	}
}//end if class_exists('IHomefinderAdmin')
?>