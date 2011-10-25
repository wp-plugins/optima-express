<?php
if( !class_exists('IHomefinderAdmin')) {
	class IHomefinderAdmin {
		
		private static $instance ;
		
		private function __construct(){
		}
		
		public static function getInstance(){
			if( !isset(self::$instance)){
				self::$instance = new IHomefinderAdmin();
			}
			return self::$instance;		
		}
		
		public function createAdminMenu(){
                    add_menu_page('Optima Express Information', 'Optima Express Information', 'manage_options', 'ihf_idx', array( $this, 'adminOptionsForm' ));
                    add_submenu_page( 'ihf_idx', 'Register', 'Register', 'manage_options', IHomefinderConstants::OPTION_ACTIVATE, array( &$this, 'adminOptionsActivateForm'));
                    add_submenu_page( 'ihf_idx', 'Links', 'Links', 'manage_options', IHomefinderConstants::OPTION_PAGES, array( &$this, 'adminOptionsPagesForm'));
                    add_submenu_page( 'ihf_idx', 'Configuration', 'Configuration', 'manage_options', IHomefinderConstants::OPTION_CONFIG_PAGE, array( &$this, 'adminConfigurationForm'));
		}
                
		public function adminOptionsForm(){
                    if (!current_user_can('manage_options'))  {
                        wp_die( __('You do not have sufficient permissions to access this page.') );
                    }    
                    ?>

                    <div class="wrap">
                        <h2>Optima Express IDX Plugin</h2>
                        <br/>
                        <div>	

                            <b>Version <?php echo IHomefinderConstants::VERSION ?></b>
                            <br/><br/>                        
                            <b>Register:</b> This option is used to enter the Registration Key.  The Registration Key must be obtained through iHomefinder.
                            <br/><br/>
                            <b>Links:</b> This option is used to view the links for the various pages used by the Optima Express plugin.
                            <br/><br/>
                            <b>Configuration:</b> This page provides customization features.
                            <br/><br/>
                            
                        </div>
                    </div>
                    <?php
		}    
		
		public function updateAuthenticationToken(){
			$activationToken=get_option(IHomefinderConstants::ACTIVATION_TOKEN_OPTION);
			$authenticationInfo=$this->activate($activationToken);

			$authenticationToken = $authenticationInfo->authenticationToken;
			$permissions = $authenticationInfo->permissions;

			IHomefinderLogger::getInstance()->debug( 'authenticationToken' . $authenticationToken ) ;
			set_transient(IHomefinderConstants::AUTHENTICATION_TOKEN_CACHE, $authenticationToken, IHomefinderConstants::AUTHENTICATION_TOKEN_CACHE_TIMEOUT);	 	
			IHomefinderPermissions::getInstance()->initialize( $permissions );
			
			if( !$this->previouslyActivated()){	
				$this->createLinks();
				update_option(IHomefinderConstants::IS_ACTIVATED_OPTION,'true');
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
			if( false === $authenticationToken  )	{
				$this->updateAuthenticationToken();	
				$authenticationToken = get_transient(IHomefinderConstants::AUTHENTICATION_TOKEN_CACHE);
			}						
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
		
		private function createLinks(){
			$urlFactory=IHomefinderUrlFactory::getInstance();
			$permissions=IHomefinderPermissions::getInstance();
			$this->createOneLink('Home Search', $urlFactory->getListingsSearchFormUrl(true), 'Find your home today.');
			$this->createOneLink('Advanced Home Search', $urlFactory->getListingsAdvancedSearchFormUrl(true), 'Find your home today with advanced search parameters.');
			
			if($permissions->isFeaturedPropertiesEnabled()){
				$this->createOneLink('Featured Listings', $urlFactory->getFeaturedSearchResultsUrl(true), 'Offers a list of featured properties.');
			}
			if( $permissions->isHotSheetEnabled()){
				$this->createOneLink('Top Picks', $urlFactory->getHotsheetSearchResultsUrl(true), 'Top Picks provides a list of custom searches.');
			}			
			if( $permissions->isEmailUpdatesEnabled()){
				$this->createOneLink('Email Updates', $urlFactory->getOrganizerEditSavedSearchUrl(true), 'Sign up to receive email alerts for properties in your area.');
			}
			if( $permissions->isOrganizerEnabled()){
				$this->createOneLink('Organizer Login', $urlFactory->getOrganizerLoginUrl(true), 'Login to manage your email alerts.');
			}			
		}
		
		private function activate($activationToken){
			
			$urlFactory=IHomefinderUrlFactory::getInstance();
			$ajaxBaseUrl                          = urlencode($urlFactory->getAjaxBaseUrl());
			$listingsSearchResultsUrl             = urlencode($urlFactory->getListingsSearchResultsUrl(true));
			$listingsSearchFormUrl                = urlencode($urlFactory->getListingsSearchFormUrl(true));
			$listingDetailUrl                     = urlencode($urlFactory->getListingDetailUrl(true));
			$featuredSearchResultsUrl             = urlencode($urlFactory->getFeaturedSearchResultsUrl(true));
			$hotseetSearchResultsUrl              = urlencode($urlFactory->getHotsheetSearchResultsUrl(true));
			$organizerLoginUrl                    = urlencode($urlFactory->getOrganizerLoginUrl(true));
			$organizerLogoutUrl                   = urlencode($urlFactory->getOrganizerLogoutUrl(true));
			$organizerLoginSubmitUrl              = urlencode($urlFactory->getOrganizerLoginSubmitUrl(true));
			$organizerSavedSearchesUrl            = urlencode($urlFactory->getOrganizerSavedSearchesUrl(true));
			$organizerEditSavedSearchUrl          = urlencode($urlFactory->getOrganizerEditSavedSearchUrl(true));
			$organizerEditSavedSearchSubmitUrl    = urlencode($urlFactory->getOrganizerEditSavedSearchSubmitUrl(true));
			$organizerDeleteSavedSearchSubmitUrl  = urlencode($urlFactory->getOrganizerDeleteSavedSearchSubmitUrl(true));
			$organizerViewSavedSearchUrl          = urlencode($urlFactory->getOrganizerViewSavedSearchUrl(true));
			$organizerViewSavedSearchListUrl      = urlencode($urlFactory->getOrganizerViewSavedSearchListUrl(true));
			$organizerViewSavedListingListUrl     = urlencode($urlFactory->getOrganizerViewSavedListingListUrl(true));
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
				'hotseetSearchResultsUrl'=> $hotseetSearchResultsUrl,
				'organizerLoginUrl'=> $organizerLoginUrl,
				'organizerLogoutUrl'=> $organizerLogoutUrl,
				'organizerLoginSubmitUrl'=> $organizerLoginSubmitUrl,
				'organizerSavedSearchesUrl'=> $organizerSavedSearchesUrl,
				'organizerEditSavedSearchUrl'=> $organizerEditSavedSearchUrl,
				'organizerEditSavedSearchSubmitUrl'=> $organizerEditSavedSearchSubmitUrl,
				'organizerDeleteSavedSearchSubmitUrl'=> $organizerDeleteSavedSearchSubmitUrl,
				'organizerViewSavedSearchUrl'=> $organizerViewSavedSearchUrl,
				'organizerViewSavedSearchListUrl'=> $organizerViewSavedSearchListUrl,
				'organizerViewSavedListingListUrl'=> $organizerViewSavedListingListUrl,
				'organizerResendConfirmationEmailUrl'=> $organizerResendConfirmationEmailUrl,
				'organizerActivateSubscriberUrl'=> $organizerActivateSubscriberUrl,
				'organizerSendSubscriberPasswordUrl'=> $organizerSendSubscriberPasswordUrl,
				'listingAdvancedSearchFormUrl'=> $listingsAdvancedSearchFormUrl
			);
			
			IHomefinderLogger::getInstance()->debug( '$ihfUrl:::' . $ihfUrl ) ;
			$authenticationInfo = IHomefinderRequestor::remotePostRequest( $ihfUrl, $postData ) ;
			IHomefinderLogger::getInstance()->debugDumpVar($authenticationInfo);
			return $authenticationInfo ;
		}		
		
		public function registerSettings(){
			register_setting( IHomefinderConstants::OPTION_ACTIVATE, IHomefinderConstants::ACTIVATION_TOKEN_OPTION );
			register_setting( IHomefinderConstants::OPTION_ACTIVATE, IHomefinderConstants::ACTIVATION_DATE_OPTION );
			register_setting( IHomefinderConstants::OPTION_CONFIG_PAGE, IHomefinderConstants::CSS_OVERRIDE_OPTION );
		}
		
		public function adminOptionsActivateForm(){
			if (!current_user_can('manage_options'))  {
				wp_die( __('You do not have sufficient permissions to access this page.') );
			}

			//When new options are updated, the paramerter "updated" is set to true
			$isUpdated = ( array_key_exists('updated', $_REQUEST) && $_REQUEST["updated"] ) ;
			if(!$isUpdated){
				//version 3.1 sets this value, rather than "updated"
				$isUpdated = ( array_key_exists('settings-updated', $_REQUEST) && $_REQUEST["settings-updated"] ) ;
			}
			
			if($isUpdated){
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
				<h2>Optima Express IDX Plugin</h2>

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
				        			<?php if( $isUpdated ){?>
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
				    
				    <div>CSS Override</div>
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
			$urlFactory = IHomefinderUrlFactory::getInstance() ;
			$permissions = IHomefinderPermissions::getInstance() ;
                    ?>
			<div class="wrap">
				<h2>Pages</h2>
				<br/>
				<div>	
					<h3>Search Form</h3>
					<b><?php echo $urlFactory->getListingsSearchFormUrl(true)  ;?></b> 
					<br/>Main property search form.  Prospective customers can search for properties
					by price, city, baths, and bedrooms.
					<br/><br/>

					<h3>Advanced Search Form</h3>
					<b><?php echo $urlFactory->getListingsAdvancedSearchFormUrl(true)  ;?></b> 
					<br/>Property search form with MLS specific advanced search fields.  Prospective customers can search for properties
					by price, city, baths, bedrooms, and other MLS specific fields.
					<br/><br/>					
					
					<h3>Organizer Login</h3>
					<b><?php echo $urlFactory->getOrganizerLoginUrl(true);?></b> 
					<br/>Login page for the Property Organizer.  Subscribers can login and view, edit or delete Email Alerts.
					An Email Alert sends daily emails of properties that match the desired search criteria.  			
					<br/><br/>
					
					<?php if( $permissions->isEmailUpdatesEnabled()){?>
						<h3>Email Alerts</h3>
						<b><?php echo $urlFactory->getOrganizerEditSavedSearchUrl(true);?></b> 
						<br/>This link provides a short cut to create an Email Alert.  If the propective customer 
						is not logged in, this page will prompt for subscriber information such as name and email address.  
						After the Email Alert form is submitted, a new subscriber is created (or an existing subscriber is 
						logged in).
						<br/><br/>
					<?php }?>

 					<?php if( $permissions->isFeaturedPropertiesEnabled()){?>
	 					<h3>Featured Properties</h3>
						<b><?php echo $urlFactory->getFeaturedSearchResultsUrl(true);?></b> 
						<br/> Your Featured Properties.  This page will display your properties for sale or 
						will default to properties from your office, if you do not currently have properties for sale.
						<br/><br/>
					<?php }?>
					
					<?php if( $permissions->isHotSheetEnabled()){?>
	 					<h3>Top Picks</h3>
						<b><?php echo $urlFactory->getHotsheetSearchResultsUrl(true);?></b> 
						<br/> Your Top Picks.  This page will display a list of your Top Picks saved searches.
						<br/><br/>		
					<?php }?>			
					
				</div>
			</div>
                    <?php
		}

	}
}//end if class_exists('IHomefinderAdmin')
?>