<?php

/**
 * iHomefinderPropertiesGallery Class
 */
class iHomefinderPropertiesGallery extends WP_Widget {
	
	private $contextUtility;
	
	public function __construct() {
		$options=array('description'=>'Display a list of properties.');
		parent::WP_Widget(false,
						   $name = 'IDX: Property Gallery',
						   $widget_options=$options);
		$this->contextUtility=iHomefinderWidgetContextUtility::getInstance();
	}

	/**
	 * Used to create the widget for display in the blog.
	 *
	 * @see WP_Widget::widget
	 */
	public function widget($args, $instance) {

		if($this->contextUtility->isEnabled($instance)) {
			$galleryType = $instance['galleryType'];
			switch ($galleryType) {
				case 'hotSheet':
					$this->hotSheet($args, $instance);
					break;
				case 'featuredListing':
					$this->featuredListing($args, $instance);
					break;
				case 'namedSearch':
					$this->namedSearch($args, $instance);
					break;
				case 'linkSearch':
					$this->linkSearch($args, $instance);
					break;
			}
		}
	}
	
	 private function hotSheet($args, $instance) {
		global $blog_id;
		global $post;

		if(iHomefinderPermissions::getInstance()->isHotSheetEnabled()) {
			$currentPageId = $post->ID;
			extract($args);
			$title = apply_filters('widget_title', $instance['name']);
			$numberOfListingsToDisplay  = empty($instance['propertiesShown']) ? '5' : $instance['propertiesShown'];
			$hotSheetId  = esc_attr($instance['hotSheetId']);
			$linkText = esc_attr($instance['linkText']);

			//link to all listings in the hotsheet
			$nameInUrl = preg_replace("[^A-Za-z0-9-]", "-", $title);

			$nameInUrl = str_replace(" ", "-", $nameInUrl);

			$linkUrl = iHomefinderUrlFactory::getInstance()->getHotsheetSearchResultsUrl(true) . '/' . $nameInUrl . '/'.$hotSheetId;
			
			$requestData = 'method=handleRequest&viewType=json&requestType=hotsheet-results';
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "startRowNumber", 1);
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "numListingsLimit", $numberOfListingsToDisplay);
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "hotSheetId", $hotSheetId);
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "smallView", "true");
			
			$contentInfo = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData, 1800);
			$content = iHomefinderRequestor::getInstance()->getContent($contentInfo);
			iHomefinderEnqueueResource::getInstance()->addToFooter($contentInfo->head);
			
			echo $before_widget;
			if ($title) {
				echo $before_title . $title . $after_title;
			}
			if(iHomefinderLayoutManager::getInstance()->hasExtraLineBreaksInWidget()) {
				echo "<br/>";	
				echo $content;
				echo "<br/>";
			}
			else{
				echo $content;
			}
			echo "<a href='" . $linkUrl. "'>" . $linkText . "</a>";
			echo $after_widget;
		}
	 }

	 private function featuredListing($args, $instance) {
		 global $blog_id;
		 global $post;

		 if(iHomefinderPermissions::getInstance()->isFeaturedPropertiesEnabled()) {
			$currentPageId = $post->ID;
			extract($args);
			$title = apply_filters('widget_title', $instance['name']);
			$numberOfListingsToDisplay  = empty($instance['propertiesShown']) ? '5' : $instance['propertiesShown'];
			$linkText = esc_attr($instance['linkText']);

			//link to all featured properties
			$linkUrl = iHomefinderUrlFactory::getInstance()->getFeaturedSearchResultsUrl(true);

			$requestData = 'method=handleRequest&viewType=json&requestType=featured-search';
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "startRowNumber", 1);
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "numListingsLimit", $numberOfListingsToDisplay);
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "smallView", "true");
			$contentInfo = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData, 1800);
			$content = iHomefinderRequestor::getInstance()->getContent($contentInfo);
			iHomefinderEnqueueResource::getInstance()->addToFooter($contentInfo->head);

			echo $before_widget;
			if ($title) {
				echo $before_title . $title . $after_title;
			}
			echo "<br/>" . $content . "<br/>";
			echo "<a href='" . $linkUrl. "'>" . $linkText . "</a>";
			echo $after_widget;
		 }
	 }

	 private function linkSearch($args, $instance) {
	   global $blog_id;
	   global $post;

	   if(iHomefinderPermissions::getInstance()->isLinkSearchEnabled()) {
		$title = apply_filters('widget_title', $instance['name']);

		extract($args);
		$cityId = esc_attr($instance['cityId']);
		$bed = esc_attr($instance['bed']);
		$bath = esc_attr($instance['bath']);
		$minPrice = esc_attr($instance['minPrice']);
		$maxPrice = esc_attr($instance['maxPrice']);
		$propertyType = esc_attr($instance['propertyType']);
		$numberOfListingsToDisplay  = empty($instance['propertiesShown']) ? '5' : $instance['propertiesShown'];
		$linkText = esc_attr($instance['linkText']);

		//link to all featured listings
		$linkUrl = iHomefinderUrlFactory::getInstance()->getListingsSearchResultsUrl(true);
		$linkUrl = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($linkUrl, "cityId", $cityId);
		$linkUrl = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($linkUrl, "propertyType", $propertyType);
		$linkUrl = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($linkUrl, "bedrooms", $bed);
		$linkUrl = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($linkUrl, "bathCount", $bath);
		$linkUrl = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($linkUrl, "minListPrice", $minPrice);
		$linkUrl = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($linkUrl, "maxListPrice", $maxPrice);

		echo $before_widget;
		echo $before_title;
		echo "<a href='" . $linkUrl. "'>" . $linkText . "</a>";
		echo $after_title;
		echo $after_widget;
	   }
	 }


	 private function namedSearch($args, $instance) {
	   global $blog_id;
	   global $post;
		
	   if(iHomefinderPermissions::getInstance()->isNamedSearchEnabled()) {
		$title = apply_filters('widget_title', $instance['name']);
		
		extract($args);
		$cityId = esc_attr($instance['cityId']);
		$bed = esc_attr($instance['bed']);
		$bath = esc_attr($instance['bath']);
		$minPrice = esc_attr($instance['minPrice']);
		$maxPrice = esc_attr($instance['maxPrice']);
		$propertyType = esc_attr($instance['propertyType']);
		$numberOfListingsToDisplay  = empty($instance['propertiesShown']) ? '5' : $instance['propertiesShown'];
		$linkText = esc_attr($instance['linkText']);
		
		//link to all featured listings
		$linkUrl = iHomefinderUrlFactory::getInstance()->getListingsSearchResultsUrl(true);
		$linkUrl = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($linkUrl, "cityId", $cityId);
		$linkUrl = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($linkUrl, "propertyType", $propertyType);
		$linkUrl = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($linkUrl, "bedrooms", $bed);
		$linkUrl = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($linkUrl, "bathcount", $bath);
		$linkUrl = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($linkUrl, "minListPrice", $minPrice);
		$linkUrl = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($linkUrl, "maxListPrice", $maxPrice);
		
		$requestData = 'method=handleRequest&viewType=json&requestType=listing-search-results';
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "cityId", $cityId);
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "bedrooms", $bed);
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "bathcount", $bath);
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "minListPrice", $minPrice);
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "maxListPrice", $maxPrice);
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "propertyType", $propertyType);
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "numListingsLimit", $numberOfListingsToDisplay);
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "smallView", "true");
		
		$contentInfo = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData, 1800);
		$content = iHomefinderRequestor::getInstance()->getContent($contentInfo);
		iHomefinderEnqueueResource::getInstance()->addToFooter($contentInfo->head);

		echo $before_widget;
		if ($title) {
			echo $before_title . $title . $after_title;

		}
		echo "<br/>" . $content . "<br/>";
		echo "<a href='" . $linkUrl. "'>" . $linkText . "</a>";
		echo $after_widget;
	   }
	 }

	/**
	 *  Processes form submission in the admin area for configuring
	 *  the widget.
	 *
	 *  @see WP_Widget::update
	 */
	public function update($new_instance, $old_instance) {    	
			$instance = $old_instance;
			$instance['galleryType'] = strip_tags(stripslashes($new_instance['galleryType']));
			$instance['listingID'] = strip_tags(stripslashes($new_instance['listingID']));
			$instance['name'] = strip_tags(stripslashes($new_instance['name']));
			$instance['propertiesShown'] = strip_tags(stripslashes($new_instance['propertiesShown']));
			$instance['cityId'] = strip_tags(stripslashes($new_instance['cityId']));
			$instance['propertyType'] = strip_tags(stripslashes($new_instance['propertyType']));
			$instance['bed'] = strip_tags(stripslashes($new_instance['bed']));
			$instance['bath'] = strip_tags(stripslashes($new_instance['bath']));
			$instance['minPrice'] = strip_tags(stripslashes($new_instance['minPrice']));
			$instance['maxPrice'] = strip_tags(stripslashes($new_instance['maxPrice']));
			$instance['hotSheetId'] = strip_tags(stripslashes($new_instance['hotSheetId']));
			$instance['linkText'] = strip_tags(stripslashes($new_instance['linkText']));
			
			$instance = $this->contextUtility->updateContext($new_instance, $instance);

		return $instance;
	}

	/**
	 * Create the admin form, for adding the Widget to the blog.
	 *
	 *  @see WP_Widget::form
	 */
	public function form($instance) {
					
			$galleryType = ($instance) ? esc_attr($instance['galleryType']) : '';
			$listingID = ($instance) ? esc_attr($instance['listingID']) : '';
			$name = ($instance) ? esc_attr($instance['name']) : '';
			$propertiesShown = ($instance) ? esc_attr($instance['propertiesShown']) : '3';
			$cityId = ($instance) ? esc_attr($instance['cityId']) : '';
			$propertyType = ($instance) ? esc_attr($instance['propertyType']) : '';
			$bed = ($instance) ? esc_attr($instance['bed']) : '';
			$bath = ($instance) ? esc_attr($instance['bath']) : '';
			$minPrice = ($instance) ? esc_attr($instance['minPrice']) : '';
			$maxPrice = ($instance) ? esc_attr($instance['maxPrice']) : '';
			$hotSheetId = ($instance) ? esc_attr($instance['hotSheetId']) : '';
			$linkText = ($instance) ? esc_attr($instance['linkText']) : 'View all';

			$formData = iHomefinderSearchFormFieldsUtility::getInstance()->getFormData();
			$hotsheetsList=$formData->getHotsheetList();
			$citiesList=$formData->getCitiesList();
			$propertyTypesList=$formData->getPropertyTypesList();
			
	
		?>
		


	   <script type="text/javascript">
			function togglePropertyFormFields(current_radio) {
				if (current_radio == 'hotSheet') {
					jQuery('div.widgetName').show();
					jQuery('div.linkText').show();
					jQuery('div.hotSheet').show();
					jQuery('div.numberProperties').show();
					jQuery('div.namedSearch').hide();
				}
				else if (current_radio == 'namedSearch') {
					jQuery('div.widgetName').show();
					jQuery('div.linkText').show();
					jQuery('div.namedSearch').show();
					jQuery('div.hotSheet').hide();
					jQuery('div.numberProperties').show();
				}
				else if (current_radio == 'linkSearch') {
					jQuery('div.widgetName').hide();
					jQuery('div.linkText').show();
					jQuery('div.namedSearch').show();
					jQuery('div.hotSheet').hide();
					jQuery('div.numberProperties').hide();
				}
				else if (current_radio == 'featuredListing') {
					jQuery('div.numberProperties').show();
					jQuery('div.widgetName').show();
					jQuery('div.linkText').show();
					jQuery('div.namedSearch').hide();
					jQuery('div.hotSheet').hide();
				}
			}
		</script>


		<div>
			Gallery type:<br />
			<?php
				//set selected gallery type
				if($galleryType == null || $galleryType == "") {
					if(iHomefinderPermissions::getInstance()->isNamedSearchEnabled()) {
						$galleryType="namedSearch";
					}
					else if(iHomefinderPermissions::getInstance()->isLinkSearchEnabled()) {
						$galleryType="linkSearch";
					}
					else if(iHomefinderPermissions::getInstance()->isHotSheetEnabled()) {
						$galleryType="hotSheet";
					}
					else if(iHomefinderPermissions::getInstance()->isFeaturedPropertiesEnabled()) {
						$galleryType="featuredListing";
					}
					else{
						$galleryType="";
					}
				}
			?>


			<?php if(iHomefinderPermissions::getInstance()->isFeaturedPropertiesEnabled()) { ?>
				<label><input onclick="togglePropertyFormFields(this.value);" <?php if($galleryType == 'featuredListing') echo 'checked="checked"'; ?> class="galtype" type="radio" class="galtype" value="featuredListing" name="<?php echo $this->get_field_name('galleryType'); ?>" /> Featured Properties Gallery</label><br/>
			<?php }?>
			<?php if(iHomefinderPermissions::getInstance()->isHotSheetEnabled()) { ?>
				<label><input onclick="togglePropertyFormFields(this.value);" <?php if($galleryType == 'hotSheet') echo 'checked="checked"'; ?> class="galtype" type="radio" class="galtype" value="hotSheet" name="<?php echo $this->get_field_name('galleryType'); ?>" /> Saved Search Page Gallery</label><br />
			<?php }?>
			<?php if(iHomefinderPermissions::getInstance()->isNamedSearchEnabled()) { ?>
				<label><input onclick="togglePropertyFormFields(this.value);" <?php if($galleryType == 'namedSearch') echo 'checked="checked"'; ?> class="galtype" type="radio" class="galtype" value="namedSearch" name="<?php echo $this->get_field_name('galleryType'); ?>" /> Dynamic Search Gallery</label><br />
			<?php }?>
			<?php if(iHomefinderPermissions::getInstance()->isLinkSearchEnabled()) { ?>
				<label><input onclick="togglePropertyFormFields(this.value);" <?php if($galleryType == 'linkSearch') echo 'checked="checked"'; ?> class="galtype" type="radio" class="galtype" value="linkSearch" name="<?php echo $this->get_field_name('galleryType'); ?>" /> Dynamic Search Link</label>
			<?php }?>
		</div>

		<div id="widgetName" class="widgetName" <?php if($galleryType == 'linkSearch') echo 'style="display:none;"'; ?>>
			<label>Gallery Title:</label>
			<input class="widefat" type="text" value="<?php echo $name; ?>" name="<?php echo $this->get_field_name('name'); ?>" />
		</div>

		<div id="numberProperties" class="numberProperties" <?php if($galleryType == 'linkSearch') echo 'style="display:none;"'; ?>>
			<label>Number of Properties Shown:</label>
			<select name="<?php echo $this->get_field_name('propertiesShown'); ?>">
			<?php
				for ($i=1; $i<11; $i+=1) {
					echo "<option value='" . $i  . "'";
					if($propertiesShown == $i) {
						echo " selected='true'";
					}
					echo ">" . $i . "</option>";
			}
			?>
			</select>
		</div>
		<div id="linkText" class="linkText">
			<label>Link Text:</label>
			<input class="widefat" type="text" value="<?php echo $linkText; ?>" name="<?php echo $this->get_field_name('linkText'); ?>" />
		</div>

		<div id="hotSheet" class="hotSheet" <?php if($galleryType != 'hotSheet') echo 'style="display:none;"'; ?>>
			<label>Saved Search Pages:</label>
			
			<select name="<?php echo $this->get_field_name('hotSheetId'); ?>">
			<?php
				
			foreach ($hotsheetsList as $i => $value) {
							echo "<option value='" . (string) $hotsheetsList[$i]->hotsheetId . "'";
							if($hotsheetsList[$i]->hotsheetId == $hotSheetId) {
								echo " selected='true'";
							}
						echo ">" . (string) $hotsheetsList[$i]->displayName . "</option>";
			}
			?>
			</select>
		</div>

		<div id="namedSearch" class="namedSearch" <?php if($galleryType != 'namedSearch' && $galleryType != 'linkSearch') echo 'style="display:none;"'; ?>>
			<label>City:</label><br/>
			<select name="<?php echo $this->get_field_name('cityId'); ?>" size="5" style="height: 100px;">
			<?php
				foreach ($citiesList as $i => $value) {
					echo "<option value='" . $citiesList[$i]->cityId . "'";
					if($citiesList[$i]->cityId == $cityId) {
						echo " selected='true'";
					}
					echo ">" . $citiesList[$i]->displayName . "</option>";
			}
			?>
			</select>
			<br/>
			<label>Property Type:</label><br/>
			<select name="<?php echo $this->get_field_name('propertyType'); ?>" >
			<?php
			
				foreach ($propertyTypesList as $i => $value) {
					echo"<option value='" . (string) $propertyTypesList[$i]->propertyTypeCode . "'";
					if($propertyTypesList[$i]->propertyTypeCode == $propertyType) {
						echo " selected='true'";
					}
					echo ">" . (string) $propertyTypesList[$i]->displayName . "</option>";
			}
			?>
			</select>
			<br/>
			<label>Bed:</label><br/>
			<input class="widefat" type="text" value="<?php echo $bed; ?>" name="<?php echo $this->get_field_name('bed'); ?>" />
			<br/>
			<label>Bath:</label><br/>
			<input class="widefat" type="text" value="<?php echo $bath; ?>" name="<?php echo $this->get_field_name('bath'); ?>" />
			<br/>
			<label>Minimum Price:</label><br/>
			<input class="widefat" type="text" value="<?php echo $minPrice; ?>" name="<?php echo $this->get_field_name('minPrice'); ?>" />
			<br/>
			<label>Maximum Price:</label><br/>
			<input class="widefat" type="text" value="<?php echo $maxPrice; ?>" name="<?php echo $this->get_field_name('maxPrice'); ?>" />
		</div>
		<?php 
			//The following call echos a select context for pages to display.
			echo ($this->contextUtility->getPageSelector($this, $instance, iHomefinderConstants::GALLERY_WIDGET_TYPE));
		?>


		<?php
	}

}
