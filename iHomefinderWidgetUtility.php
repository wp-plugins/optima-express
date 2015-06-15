<?php

/**
 * This singleton utility class is used to support hiding or displaying of widgets.
 * 
 * It defines a list of pages that can be enabled for a given widget. When an Optima Express page is viewed, this utility is used to  determine if the widget should display or not.
 * 
 * This is a helper class used by widgets to display form information, perform instance updates and determine if the widget is enabled for a given page context.
 * 
 * Rather than extending class WP_Widget, this functionality has been added as a separate Utility class that can be added to a widget using composition.
 */
class iHomefinderWidgetUtility {
	
	const SEARCH_WIDGET_TYPE = "searchWidget";
	const GALLERY_WIDGET_TYPE = "galleryWidget";
	const CONTACT_WIDGET_TYPE = "contactWidget";
	const SEARCH_OTHER_WIDGET_TYPE = "searchOtherWidget";
	
	private static $instance;
	
	private function __construct() {
	}
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function isEnabled($widget, $instance, $widgetType) {
		$result = false;
		//type is only defined for ihomefinder page. 
		//If not set, then always display the widget.
		$virtualPageType = get_query_var(iHomefinderConstants::IHF_TYPE_URL_VAR);			
		if(empty($virtualPageType)) {
			//always display the widget for non OE virtual pages
			$result = true;	
		} elseif(!array_key_exists(iHomefinderVirtualPageFactory::LISTING_DETAIL, $instance)) {
			//If the widget instance does not have the listing detail key, then we have a plugin
			//That has been upgraded, but the user did not update the widget. In this case
			//we default to the previous behavior of displaying the widget on all pages.
			$result = true;				
		} elseif(array_key_exists($virtualPageType, $instance) && $instance[$virtualPageType] === "true") {
			//We have enabled the type for this widget see iHomefinderVirtualPageFactory for valid types
			$result = true;				
		} else {
			//Special cases that are not covered specifically by type
			if($instance[iHomefinderVirtualPageFactory::HOTSHEET_SEARCH_RESULTS] === "true") {	
				//If set to display with Hotsheet, then also display in the Hotsheet list.
				if($virtualPageType === iHomefinderVirtualPageFactory::HOTSHEET_LIST) {
					$result = true;
				}
			} elseif($instance[iHomefinderVirtualPageFactory::ORGANIZER_LOGIN] === "true") {
				//If set to display for Organizer, then enabled for saved listings and search
				if($virtualPageType === iHomefinderVirtualPageFactory::ORGANIZER_VIEW_SAVED_LISTING_LIST) {
					$result = true;
				} elseif($virtualPageType === iHomefinderVirtualPageFactory::ORGANIZER_EDIT_SAVED_SEARCH) {
					$result = true;
				}
			} elseif($instance[iHomefinderVirtualPageFactory::ORGANIZER_EDIT_SAVED_SEARCH] === "true") {
				//Email Alerts page
				if($virtualPageType === iHomefinderVirtualPageFactory::ORGANIZER_EMAIL_UPDATES_CONFIRMATION) {
					$result = true;
				}
			}
		}
		return $result;
	}
	
	private function getVirtualPages($widgetType) {
		$virtualPages = array();
		$virtualPages["Open Home Search"] = iHomefinderVirtualPageFactory::OPEN_HOME_SEARCH_FORM;
		$virtualPages["Search Results"] = iHomefinderVirtualPageFactory::LISTING_SEARCH_RESULTS;			
		$virtualPages["Listing Details"] = iHomefinderVirtualPageFactory::LISTING_DETAIL;
		$virtualPages["Sold Property Details"] = iHomefinderVirtualPageFactory::LISTING_SOLD_DETAIL;
		$virtualPages["Sold Featured Listing"] = iHomefinderVirtualPageFactory::SOLD_FEATURED_LISTING;
		$virtualPages["Supplemental Listing"] = iHomefinderVirtualPageFactory::SUPPLEMENTAL_LISTING;
		$virtualPages["Featured Properties"] = iHomefinderVirtualPageFactory::FEATURED_SEARCH;			
		$virtualPages["Saved Search Pages"] = iHomefinderVirtualPageFactory::HOTSHEET_SEARCH_RESULTS;
		$virtualPages["Organizer Pages"] = iHomefinderVirtualPageFactory::ORGANIZER_LOGIN;
		$virtualPages["Valuation Request"] = iHomefinderVirtualPageFactory::VALUATION_FORM;
		if(iHomefinderPermissions::getInstance()->isAgentBioEnabled()) {
			$virtualPages["Agent Bio"] = iHomefinderVirtualPageFactory::AGENT_DETAIL;
			$virtualPages["Agent List"] = iHomefinderVirtualPageFactory::AGENT_LIST;
		}
		if(iHomefinderPermissions::getInstance()->isOfficeEnabled()) {
			$virtualPages["Office Detail"] = iHomefinderVirtualPageFactory::OFFICE_DETAIL;
			$virtualPages["Office List"] = iHomefinderVirtualPageFactory::OFFICE_LIST;
		}
		//Search pages are not valid for search widgets.
		if($widgetType !== self::SEARCH_WIDGET_TYPE) {
			$virtualPages["Search Form"] = iHomefinderVirtualPageFactory::LISTING_SEARCH_FORM;
			$virtualPages["Advanced Search Form"] = iHomefinderVirtualPageFactory::LISTING_ADVANCED_SEARCH_FORM;
			$virtualPages["Email Alerts"] = iHomefinderVirtualPageFactory::ORGANIZER_EDIT_SAVED_SEARCH;
			$virtualPages["Map Search"] = iHomefinderVirtualPageFactory::MAP_SEARCH_FORM;
		}
		//Contact page is not valid for contact form widget
		if($widgetType !== self::CONTACT_WIDGET_TYPE) {
			$virtualPages["Contact Form"] = iHomefinderVirtualPageFactory::CONTACT_FORM;
		}
		ksort($virtualPages);
		return $virtualPages;
	}
	
	public function updateContext($newInstance, $oldInstance) {
		$instance = $oldInstance;
		$virtualPages = $this->getVirtualPages();
		foreach($virtualPages as $key => $value) {
			$instance[$value] = empty($newInstance[$value]) ? "false" : "true";
		}
		return $instance;	
	}
	
	/**
	 * This function echos JavaScript and a set of checkboxes used to 
	 * restrict the pages that the widget displays on. For example, we
	 * can configure a Featured Listings widget to NOT diplay on the 
	 * Featured Listings page.
	 * 
	 * @param WP_Widget $widget The actual widget object.
	 * @param array $instance The settings for the particular instance of the widget
	 * @param String $widgetType Examples are search or gallery - @see iHomefinderWidgetUtility for gallery types
	 */
	public function getPageSelector($widget, $instance, $widgetType) {
		//cannot use $this->id in the function name, b/c it has characters that are not allowed for JavaScript functions
		$selectAllCheckbox = "selectAllCheckbox" . $widget->id;
		$selectAllCheckboxDiv = "selectAllContainer" . $widget->id;
		?>
		<p>Display widget on selected IDX pages:</p>
		<label>
			<input
				id="<?php echo $selectAllCheckbox ?>"
				type="checkbox"
				onclick="ihfSelectAllCheckboxes('<?php echo $selectAllCheckbox ?>', '<?php echo $selectAllCheckboxDiv ?>');"
			/>
			Select All
		</label>
		<div id="<?php echo $selectAllCheckboxDiv ?>">	
			<?php
			$virtualPages = $this->getVirtualPages($widgetType);
			foreach ($virtualPages as $label => $virtualPageType) {
				$fieldId = $widget->get_field_id($virtualPageType);
				$fieldName = $widget->get_field_name($virtualPageType);
				$fieldValue = true;
				if(array_key_exists($virtualPageType, $instance) && $instance[$virtualPageType] === "false") {
					$fieldValue = false;
				}
				?>
				<label>
					<input
						id="<?php echo $fieldId; ?>"
						name="<?php echo $fieldName; ?>"
						type="checkbox"
						value="true"
						onclick="ihfSelectAllCheckboxesReset('<?php echo $selectAllCheckbox; ?>', '<?php echo $selectAllCheckboxDiv; ?>')"
						<?php if($fieldValue) { ?>
							checked="checked"
						<?php } ?>
					/>
					<?php echo $label; ?>
				</label>
				<br />
			<?php } ?>
		</div>
		<?php
	}

}
