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
				set_transient(IHomefinderConstants::AUTHENTICATION_TOKEN_CACHE, $authenticationToken, IHomefinderConstants::AUTHENTICATION_TOKEN_CACHE_TIMEOUT);
			}
		}

		public function deleteAuthenticationToken(){
			//This forces reactivation of the plugin at next site visit.
		    delete_transient(IHomefinderConstants::AUTHENTICATION_TOKEN_CACHE);
		}

		/**
		 * If the authentication token has expired then generate a new authentication token
		 * from the activationToken.
		 */
		public function getAuthenticationToken(){
			$authenticationToken = get_transient(IHomefinderConstants::AUTHENTICATION_TOKEN_CACHE);
			return $authenticationToken ;
		}

		public function synchAuthenticationToken(){
			$authenticationToken = get_transient(IHomefinderConstants::AUTHENTICATION_TOKEN_CACHE);
			if( false === $authenticationToken  || '' === $authenticationToken)	{
				$this->updateAuthenticationToken();
			}
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
				'listingAdvancedSearchFormUrl'=> $listingsAdvancedSearchFormUrl
			);

			IHomefinderLogger::getInstance()->debug( '$ihfUrl:::' . $ihfUrl ) ;
			$authenticationInfo = IHomefinderRequestor::remotePostRequest( $ihfUrl, $postData ) ;

			//We need to flush the rewrite rules, if any permalinks have been updated.
			IHomefinderRewriteRules::getInstance()->flushRules() ;
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
				        		<?php if( false !== get_transient(IHomefinderConstants::AUTHENTICATION_TOKEN_CACHE) ){   ?>
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
							<option value=''><?php _e('Default Template'); ?></option>
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
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::LISTING_SEARCH_FORM );
			$permalinkId=IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_SEARCH ;
		?>

			<h3>Search Form</h3>
			<table>
				<tr>
					<td><b>Permalink:</b></td>
					<td>
					  <div id="<?php echo($permalinkId)?>Container">
					  	<?php echo $urlFactory->getBaseUrl()?>/<span id="<?php echo($permalinkId)?>Text"><?php echo $urlFactory->getListingsSearchFormUrl(false)?></span>/
					  	<input id="<?php echo($permalinkId)?>EditButton" type="button" value="Edit">
					  </div>
					  <div id="<?php echo($permalinkId)?>Edit" style="display: none;" >
						<?php echo $urlFactory->getBaseUrl()?>/
						<input size="40"
							type="text"
							id="<?php echo($permalinkId)?>"
							name="<?php echo($permalinkId)?>"
							value="<?php echo $urlFactory->getListingsSearchFormUrl(false)?>" />/
						<input id="<?php echo($permalinkId)?>DoneButton" type="button" value="Done">
					  </div>
					</td>
				</tr>
				<tr>
					<td><b>Title:</b></td>
					<td>
						<input type="text" name="<?php echo(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_SEARCH)?>" value="<?php echo($virtualPage->getTitle())?>" />
					</td>
				</tr>
				<tr>
					<td><b>Theme Template*:</b></td>
					<td>
						<select name="<?php echo(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_SEARCH)?>">
							<option value=''><?php _e('Default Template'); ?></option>
							<?php page_template_dropdown($virtualPage->getPageTemplate()); ?>
						</select>
					</td>
				</tr>
			</table>

		<?php
			$this->permalinkJavascript( $permalinkId, $urlFactory );
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
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::LISTING_ADVANCED_SEARCH_FORM );
			$permalinkId=IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_ADV_SEARCH ;
		?>
			<h3>Advanced Search Form</h3>
			<table>
				<tr>
					<td><b>Permalink:</b></td>
					<td>
					  <div id="<?php echo($permalinkId)?>Container">
					  	<?php echo $urlFactory->getBaseUrl()?>/<span id="<?php echo($permalinkId)?>Text"><?php echo $urlFactory->getListingsAdvancedSearchFormUrl(false)?></span>/
					  	<input id="<?php echo($permalinkId)?>EditButton" type="button" value="Edit">
					  </div>
					  <div id="<?php echo($permalinkId)?>Edit" style="display: none;" >
						<?php echo $urlFactory->getBaseUrl()?>/
						<input size="40"
							type="text"
							id="<?php echo($permalinkId)?>"
							name="<?php echo($permalinkId)?>"
							value="<?php echo $urlFactory->getListingsAdvancedSearchFormUrl(false)?>" />/
						<input id="<?php echo($permalinkId)?>DoneButton" type="button" value="Done">
					  </div>
					</td>
				</tr>
				<tr>
					<td><b>Title:</b></td>
					<td>
						<input type="text" name="<?php echo(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_ADV_SEARCH)?>" value="<?php echo($virtualPage->getTitle())?>" />
					</td>
				</tr>
				<tr>
					<td><b>Theme Template*:</b></td>
					<td>
						<select name="<?php echo(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_ADV_SEARCH)?>">
							<option value=''><?php _e('Default Template'); ?></option>
							<?php page_template_dropdown($virtualPage->getPageTemplate()); ?>
						</select>
					</td>
				</tr>
			</table>
		<?php
			$this->permalinkJavascript( $permalinkId, $urlFactory );
		}

		private function getOrganizerLoginPageSetup(){
			$urlFactory = IHomefinderUrlFactory::getInstance() ;
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::ORGANIZER_LOGIN );
			$permalinkId=IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_ORG_LOGIN ;
		?>
			<h3>Organizer Login</h3>
			<table>
				<tr>
					<td><b>Permalink:</b></td>
					<td>
					  <div id="<?php echo($permalinkId)?>Container">
					  	<?php echo $urlFactory->getBaseUrl()?>/<span id="<?php echo($permalinkId)?>Text"><?php echo $urlFactory->getOrganizerLoginUrl(false)?></span>/
					  	<input id="<?php echo($permalinkId)?>EditButton" type="button" value="Edit">
					  </div>
					  <div id="<?php echo($permalinkId)?>Edit" style="display: none;" >
						<?php echo $urlFactory->getBaseUrl()?>/
						<input size="40"
							type="text"
							id="<?php echo($permalinkId)?>"
							name="<?php echo($permalinkId)?>"
							value="<?php echo $urlFactory->getOrganizerLoginUrl(false)?>" />/
						<input id="<?php echo($permalinkId)?>DoneButton" type="button" value="Done">
					  </div>
					</td>
				</tr>
				<tr>
					<td><b>Title:</b></td>
					<td>
						<input type="text" name="<?php echo(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_ORG_LOGIN)?>" value="<?php echo($virtualPage->getTitle())?>" />
					</td>
				</tr>
				<tr>
					<td><b>Theme Template*:</b></td>
					<td>
						<select name="<?php echo(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_ORG_LOGIN)?>">
							<option value=''><?php _e('Default Template'); ?></option>
							<?php page_template_dropdown($virtualPage->getPageTemplate()); ?>
						</select>
					</td>
				</tr>
			</table>
		<?php
			$this->permalinkJavascript( $permalinkId, $urlFactory );
		}

		private function getEmailAlertsPageSetup(){
			$urlFactory = IHomefinderUrlFactory::getInstance() ;
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::ORGANIZER_EDIT_SAVED_SEARCH );
			$permalinkId=IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_EMAIL_UPDATES ;
		?>
			<h3>Email Alerts</h3>
			<table>
				<tr>
					<td><b>Permalink:</b></td>
					<td>
					  <div id="<?php echo($permalinkId)?>Container">
					  	<?php echo $urlFactory->getBaseUrl()?>/<span id="<?php echo($permalinkId)?>Text"><?php echo $urlFactory->getOrganizerEditSavedSearchUrl(false)?></span>/
					  	<input id="<?php echo($permalinkId)?>EditButton" type="button" value="Edit">
					  </div>
					  <div id="<?php echo($permalinkId)?>Edit" style="display: none;" >
						<?php echo $urlFactory->getBaseUrl()?>/
						<input size="40"
							type="text"
							id="<?php echo($permalinkId)?>"
							name="<?php echo($permalinkId)?>"
							value="<?php echo $urlFactory->getOrganizerEditSavedSearchUrl(false)?>" />/
						<input id="<?php echo($permalinkId)?>DoneButton" type="button" value="Done">
					  </div>
					</td>
				</tr>
				<tr>
					<td><b>Title:</b></td>
					<td>
						<input type="text" name="<?php echo(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_EMAIL_UPDATES)?>" value="<?php echo($virtualPage->getTitle())?>" />
					</td>
				</tr>
				<tr>
					<td><b>Theme Template*:</b></td>
					<td>
						<select name="<?php echo(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_ORG_LOGIN)?>">
							<option value=''><?php _e('Default Template'); ?></option>
							<?php page_template_dropdown($virtualPage->getPageTemplate()); ?>
						</select>
					</td>
				</tr>
			</table>
		<?php
			$this->permalinkJavascript( $permalinkId, $urlFactory );
		}

		private function getFeaturedPageSetup(){
			$urlFactory = IHomefinderUrlFactory::getInstance() ;
			$virtualPage = $this->virtualPageFactory->getVirtualPage( IHomefinderVirtualPageFactory::FEATURED_SEARCH );
			$permalinkId=IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_PERMALINK_TEXT_FEATURED ;
		?>
			<h3>Featured Properties</h3>
			<table>
				<tr>
					<td><b>Permalink:</b></td>
					<td>
					  <div id="<?php echo($permalinkId)?>Container">
					  	<?php echo $urlFactory->getBaseUrl()?>/<span id="<?php echo($permalinkId)?>Text"><?php echo $urlFactory->getFeaturedSearchResultsUrl(false)?></span>/
					  	<input id="<?php echo($permalinkId)?>EditButton" type="button" value="Edit">
					  </div>
					  <div id="<?php echo($permalinkId)?>Edit" style="display: none;" >
						<?php echo $urlFactory->getBaseUrl()?>/
						<input size="40"
							type="text"
							id="<?php echo($permalinkId)?>"
							name="<?php echo($permalinkId)?>"
							value="<?php echo $urlFactory->getFeaturedSearchResultsUrl(false)?>" />/
						<input id="<?php echo($permalinkId)?>DoneButton" type="button" value="Done">
					  </div>
					</td>
				</tr>
				<tr>
					<td><b>Title:</b></td>
					<td>
						<input type="text" name="<?php echo(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TITLE_FEATURED)?>" value="<?php echo($virtualPage->getTitle())?>" />
					</td>
				</tr>
				<tr>
					<td><b>Theme Template*:</b></td>
					<td>
						<select name="<?php echo(IHomefinderVirtualPageHelper::OPTION_VIRTUAL_PAGE_TEMPLATE_FEATURED)?>">
							<option value=''><?php _e('Default Template'); ?></option>
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
							<option value=''><?php _e('Default Template'); ?></option>
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