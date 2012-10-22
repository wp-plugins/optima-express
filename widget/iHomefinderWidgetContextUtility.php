<?php
if( !class_exists('IHomefinderWidgetContextUtility')) {
	/**
	 * IHomefinderWidgetContextUtility Class
	 * 
	 * This singleton utility class is used to support hiding or displaying of widgets.
	 * 
	 * It defines a list of pages that can be enabled for a given widget.
	 * When an Optima Express page is viewed, this utility is used to 
	 * determine if the widget should display or not.
	 * 
	 * This is a helper class used by widgets to display form information,
	 * perform instance updates and determine if the widget is enabled
	 * for a given page context.
	 * 
	 * Rather than extending class WP_Widget, this functionality has
	 * been added as a separate Utility class that can be added to 
	 * a widget using composition.
	 */
	class IHomefinderWidgetContextUtility {
		
		private $enabledContextField="enabledContext";
		private static $instance ;
		
		private function __construct(){
		}

		public static function getInstance(){
			if( !isset(self::$instance)){
				self::$instance = new IHomefinderWidgetContextUtility();
			}
			return self::$instance;
		}
		
		public function loadWidgetJavascript() {
		   wp_enqueue_script('widgetSupport', plugins_url('/js/widgetSupport.js', __FILE__) );
		}    		
		
		public function isEnabled( $widgetInstance ){
			$result=false;
			
			//type is only defined for ihomefinder page.  
			//If not set, then always display the widget.
			$type = get_query_var(IHomefinderConstants::IHF_TYPE_URL_VAR) ;			
			if( !isset( $type ) || trim($type) == ""){
				//always display the widget for non Optima Express pages
				//this will not work if using shortcodes
				$result=true;	
			}
			else if(!array_key_exists(IHomefinderVirtualPageFactory::LISTING_DETAIL, $widgetInstance)){
				//If the widget instance does not have the listing detail key, then we have a plugin
				//That has been upgraded, but the user did not update the widget.  In this case
				//we default to the previous behavior of displaying the widget on all pages.
				$result=true;				
			}
			else if(array_key_exists($type, $widgetInstance) && $widgetInstance[$type] == "true"){
				//We have enabled the type for this widget
				//see IHomefinderVirtualPageFactory for valid types
				$result=true;				
			}
			else{
				//Special cases that are not covered specifically by type
				if( $widgetInstance[IHomefinderVirtualPageFactory::HOTSHEET_SEARCH_RESULTS] == "true" ){	
					//If set to display with Hotsheet, then also display in the Hotsheet list.
					if( $type == IHomefinderVirtualPageFactory::HOTSHEET_LIST){
						$result="true" ;
					}
				}
				else if( $widgetInstance[IHomefinderVirtualPageFactory::ORGANIZER_LOGIN] == "true" ){
					//If set to display for Organizer, then enabled for saved listings and search
					if( $type == IHomefinderVirtualPageFactory::ORGANIZER_VIEW_SAVED_LISTING_LIST){
						$result="true" ;
					}
					else if( $type == IHomefinderVirtualPageFactory::ORGANIZER_EDIT_SAVED_SEARCH ){
						$result="true" ;
					}
				}
				//Email Alerts page
				else if( $widgetInstance[IHomefinderVirtualPageFactory::ORGANIZER_EDIT_SAVED_SEARCH] == "true" ){
					if( $type == IHomefinderVirtualPageFactory::ORGANIZER_EMAIL_UPDATES_CONFIRMATION ){
						$result="true" ;
					}
				}		
			}
				
			return $result ;
		}
		
		private function listOfPages($widgetType){
			$listOfPages=
			array(
			"Search Form" => IHomefinderVirtualPageFactory::LISTING_SEARCH_FORM,
			"Advanced Search Form" => IHomefinderVirtualPageFactory::LISTING_ADVANCED_SEARCH_FORM,
			"Open Home Search" => IHomefinderVirtualPageFactory::OPEN_HOME_SEARCH_FORM,
			"Search Results" => IHomefinderVirtualPageFactory::LISTING_SEARCH_RESULTS,			
			"Listing Details" => IHomefinderVirtualPageFactory::LISTING_DETAIL,
			"Sold Property Details" => IHomefinderVirtualPageFactory::LISTING_SOLD_DETAIL,
			"Sold Featured Listing" => 	IHomefinderVirtualPageFactory::SOLD_FEATURED_LISTING,
			"Supplemental Listing" => IHomefinderVirtualPageFactory::SUPPLEMENTAL_LISTING,
			"Featured Properties" => IHomefinderVirtualPageFactory::FEATURED_SEARCH,			
			"Top Picks" => IHomefinderVirtualPageFactory::HOTSHEET_SEARCH_RESULTS,			
			"Email Alerts" => IHomefinderVirtualPageFactory::ORGANIZER_EDIT_SAVED_SEARCH,
			"Organizer Pages" => IHomefinderVirtualPageFactory::ORGANIZER_LOGIN ,
			"Valuation Request" => 	IHomefinderVirtualPageFactory::VALUATION_FORM ,
			"Contact Form" => IHomefinderVirtualPageFactory::CONTACT_FORM
			);
			
			if( IHomefinderPermissions::getInstance()->isAgentBioEnabled()){
				$listOfPages['Agent Bio'] = IHomefinderVirtualPageFactory::AGENT_DETAIL ;
				$listOfPages['Agent List'] = IHomefinderVirtualPageFactory::AGENT_LIST ;
			}
			
			if( IHomefinderPermissions::getInstance()->isOfficeEnabled()){
				$listOfPages['Office Detail'] = IHomefinderVirtualPageFactory::OFFICE_DETAIL ;
				$listOfPages['Office List'] = IHomefinderVirtualPageFactory::OFFICE_LIST ;
				
			}
			//Search pages are not valid for search widgets.
			if( $widgetType == IHomefinderConstants::SEARCH_WIDGET_TYPE){
				unset( $listOfPages["Search Form"]);
				unset( $listOfPages["Advanced Search Form"]);
				unset( $listOfPages["Email Alerts"]);
			}
				
			return $listOfPages ;
		}
		
		public function updateContext( $new_widgetInstance, $old_widgetInstance ){
			$instance = $old_widgetInstance;
			$listOfPages=$this->listOfPages() ;
			foreach ( $listOfPages as $i => $value) {
				$instance[ $value ] = empty( $new_widgetInstance[ $value ] ) ? "false" : "true" ;
			}
			return $instance ;	
		}		
		
		/**
		 * This function echos JavaScript and a set of checkboxes used to 
		 * restrict the pages that the widget displays on.  For example, we
		 * can configure a Featured Listings widget to NOT diplay on the 
		 * Featured Lisitngs page.
		 * 
		 * @param WP_Widget $widget The actual widget object.
		 * @param unknown_type $instance The settings for the particular instance of the widget
		 * @param String $widgetType Examples are search or gallery - @see IHomefinderConstants for gallery types
		 */
	    public function getPageSelector($widget, $instance, $widgetType){
	        //cannot use $this->id in the function name, b/c it has characters
	        //that are not allowed for JavaScript functions
	        $uniqueId=uniqid();
	        $selectAllFunction=  'selectAll' . $uniqueId . 'Function';
	        $selectAllCheckbox=  'selectAllCheckbox' . $widget->id;
	        $selectAllCheckboxDiv='selectAllContainer' . $widget->id;
	        $selectAllCheckboxReset =  'selectAllCheckboxReset' . $uniqueId . "Function";	
	        
	        //this is false if the user has upgraded from 1.1.1 to 1.1.2
	        //because the widget instance does not have the listing detial field
	        $hasPageSelector = array_key_exists(IHomefinderVirtualPageFactory::LISTING_DETAIL, $instance) ;		
	        ?>   
	        
            <br/><br/>
							
		    <label>Display widget on selected IDX pages:</label>
		    <br/><br/>	
            <input id="<?php echo( $selectAllCheckbox )?>"
                   type="checkbox" 
                   <?php if(!$hasPageSelector){echo("checked='checked' ");}?>
                   onclick="selectAllCheckboxes('<?php echo( $selectAllCheckbox )?>', '<?php echo( $selectAllCheckboxDiv )?>');"/>
			Select All &nbsp;&nbsp;<br/>
			<div id='<?php echo( $selectAllCheckboxDiv )?>'>	
            <?php  
            //The following call adds for variables to setup a context for pages to display.
    			$listOfPages=$this->listOfPages($widgetType ) ;
		
				//The value is the type from IHomefinderVirtualPageFactory
		    	foreach ( $listOfPages as $label => $pageType) {
		    		$fieldName = $widget->get_field_name( $pageType ) ;
		    		$fieldId = $widget->get_field_id( $pageType ) ;
		    		//gets the saved checkbox value for this pageType
		    		//defaults to true, if instance does not have this
		    		//field.  This situation may occur when upgrading
		    		//this plugin from 1.1.1 to 1.1.2
		    		$fieldValue = "true";
		    		if($hasPageSelector){
		    			$fieldValue = $instance[ $pageType ];
		    		}
			?>
	    	<input id='<?php echo($fieldId)?>' 
	    	       name='<?php echo($fieldName)?>' 
	    	       type='checkbox' 
	    	       onclick="selectAllCheckboxesReset('<?php echo( $selectAllCheckbox )?>', '<?php echo( $selectAllCheckboxDiv )?>')"
	    	       <?php if( $fieldValue == "true"){echo("checked='checked' "); }?>
	    	       <?php if(!$hasPageSelector){echo("checked='checked' ");}?>
	    		   />&nbsp;<?php echo($label)?><br/>

			<?php }?>
	            
	        </div>

	        <?php	    	
	    }

	} // class IHomefinderWidgetUtility
}//end if( !class_exists('iHomefinderQuickSearchWidget'))
?>
