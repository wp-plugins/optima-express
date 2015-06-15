<?php

class iHomefinderPropertiesGallery extends WP_Widget {
	
	private $contextUtility;
	
	public function __construct() {
		parent::__construct(
			"iHomefinderPropertiesGallery",
			"IDX: Property Gallery",
			array(
				"description" => "Display a list of properties."
			)
		);
		$this->contextUtility = iHomefinderWidgetContextUtility::getInstance();
	}
	
	public function widget($args, $instance) {
		if($this->contextUtility->isEnabled($instance)) {
			$galleryType = $instance["galleryType"];
			switch ($galleryType) {
				case "hotSheet":
					$this->hotSheet($args, $instance);
					break;
				case "featuredListing":
					$this->featuredListing($args, $instance);
					break;
				case "namedSearch":
					$this->namedSearch($args, $instance);
					break;
				case "linkSearch":
					$this->linkSearch($args, $instance);
					break;
			}
		}
	}
	
	private function hotSheet($args, $instance) {
		if(iHomefinderPermissions::getInstance()->isHotSheetEnabled()) {
			extract($args);
			$title = apply_filters("widget_title", $instance["name"]);
			$numListingsLimit = empty($instance["propertiesShown"]) ? "5" : $instance["propertiesShown"];
			$hotSheetId = esc_attr($instance["hotSheetId"]);
			$linkText = esc_attr($instance["linkText"]);
			//link to all listings in the hotsheet
			$nameInUrl = preg_replace("[^A-Za-z0-9-]", "-", $title);
			$nameInUrl = str_replace(" ", "-", $nameInUrl);
			$linkUrl = iHomefinderUrlFactory::getInstance()->getHotsheetSearchResultsUrl(true) . "/" . $nameInUrl . "/" . $hotSheetId;
			$remoteRequest = new iHomefinderRequestor();
			$remoteRequest
				->addParameter("method", "handleRequest")
				->addParameter("viewType", "json")
				->addParameter("requestType", "hotsheet-results")
				->addParameter("startRowNumber", 1)
				->addParameter("numListingsLimit", $numListingsLimit)
				->addParameter("hotSheetId", $hotSheetId)
				->addParameter("smallView", true)
			;
			$remoteRequest->setCacheExpiration(60*30);
			$contentInfo = $remoteRequest->remoteGetRequest();
			$content = $remoteRequest->getContent($contentInfo);
			iHomefinderEnqueueResource::getInstance()->addToFooter($contentInfo->head);
			echo $before_widget;
			if($title) {
				echo $before_title . $title . $after_title;
			}
			if(iHomefinderLayoutManager::getInstance()->hasExtraLineBreaksInWidget()) {
				echo "<br />";	
				echo $content;
				echo "<br />";
			} else {
				echo $content;
			}
			echo "<a href='" . $linkUrl. "'>" . $linkText . "</a>";
			echo $after_widget;
		}
	 }

	 private function featuredListing($args, $instance) {
		 if(iHomefinderPermissions::getInstance()->isFeaturedPropertiesEnabled()) {
			extract($args);
			$title = apply_filters("widget_title", $instance["name"]);
			$numListingsLimit  = empty($instance["propertiesShown"]) ? "5" : $instance["propertiesShown"];
			$linkText = esc_attr($instance["linkText"]);
			//link to all featured properties
			$linkUrl = iHomefinderUrlFactory::getInstance()->getFeaturedSearchResultsUrl(true);
			$remoteRequest = new iHomefinderRequestor();
			$remoteRequest
				->addParameter("method", "handleRequest")
				->addParameter("viewType", "json")
				->addParameter("requestType", "featured-search")
				->addParameter("startRowNumber", 1)
				->addParameter("numListingsLimit", $numListingsLimit)
				->addParameter("smallView", "true")
			;
			$remoteRequest->setCacheExpiration(60*30);
			$contentInfo = $remoteRequest->remoteGetRequest();
			$content = $remoteRequest->getContent($contentInfo);
			iHomefinderEnqueueResource::getInstance()->addToFooter($contentInfo->head);
			echo $before_widget;
			if($title) {
				echo $before_title . $title . $after_title;
			}
			echo "<br />" . $content . "<br />";
			echo "<a href='" . $linkUrl. "'>" . $linkText . "</a>";
			echo $after_widget;
		 }
	 }

	 private function linkSearch($args, $instance) {
		if(iHomefinderPermissions::getInstance()->isLinkSearchEnabled()) {
			$title = apply_filters("widget_title", $instance["name"]);
			extract($args);
			$cityId = esc_attr($instance["cityId"]);
			$bed = esc_attr($instance["bed"]);
			$bath = esc_attr($instance["bath"]);
			$minPrice = esc_attr($instance["minPrice"]);
			$maxPrice = esc_attr($instance["maxPrice"]);
			$propertyType = esc_attr($instance["propertyType"]);
			$numListingsLimit  = empty($instance["propertiesShown"]) ? "5" : $instance["propertiesShown"];
			$linkText = esc_attr($instance["linkText"]);
			$resultsUrl = iHomefinderUrlFactory::getInstance()->getListingsSearchResultsUrl(true);
			$searchParams = array(
				"cityId" => $cityId,
				"propertyType" => $propertyType,
				"bedrooms" => $bed,
				"bathCount" => $bath,
				"minListPrice" => $minPrice,
				"maxListPrice" => $maxPrice
			);
			$linkUrl = iHomefinderUtility::getInstance()->buildUrl($resultsUrl, $searchParams);
			echo $before_widget;
			echo $before_title;
			echo "<a href='" . $linkUrl. "'>" . $linkText . "</a>";
			echo $after_title;
			echo $after_widget;
		}
	}


	 private function namedSearch($args, $instance) {
		if(iHomefinderPermissions::getInstance()->isNamedSearchEnabled()) {
			$title = apply_filters("widget_title", $instance["name"]);
			extract($args);
			$cityId = esc_attr($instance["cityId"]);
			$bed = esc_attr($instance["bed"]);
			$bath = esc_attr($instance["bath"]);
			$minPrice = esc_attr($instance["minPrice"]);
			$maxPrice = esc_attr($instance["maxPrice"]);
			$propertyType = esc_attr($instance["propertyType"]);
			$numListingsLimit = empty($instance["propertiesShown"]) ? "5" : $instance["propertiesShown"];
			$linkText = esc_attr($instance["linkText"]);
			$resultsUrl = iHomefinderUrlFactory::getInstance()->getListingsSearchResultsUrl(true);
			$searchParams = array(
				"cityId" => $cityId,
				"propertyType" => $propertyType,
				"bedrooms" => $bed,
				"bathCount" => $bath,
				"minListPrice" => $minPrice,
				"maxListPrice" => $maxPrice
			);
			$linkUrl = iHomefinderUtility::getInstance()->buildUrl($resultsUrl, $searchParams);
			$remoteRequest = new iHomefinderRequestor();
			$remoteRequest
				->addParameter("method", "handleRequest")
				->addParameter("viewType", "json")
				->addParameter("requestType", "listing-search-results")
				->addParameter("cityId", $cityId)
				->addParameter("bedrooms", $bed)
				->addParameter("bathcount", $bath)
				->addParameter("minListPrice", $minPrice)
				->addParameter("maxListPrice", $maxPrice)
				->addParameter("propertyType", $propertyType)
				->addParameter("numListingsLimit", $numListingsLimit)
				->addParameter("smallView", true)
			;
			$remoteRequest->setCacheExpiration(60*30);
			$contentInfo = $remoteRequest->remoteGetRequest();
			$content = $remoteRequest->getContent($contentInfo);
			iHomefinderEnqueueResource::getInstance()->addToFooter($contentInfo->head);
			echo $before_widget;
			if($title) {
				echo $before_title . $title . $after_title;
			}
			echo "<br />" . $content . "<br />";
			echo "<a href='" . $linkUrl. "'>" . $linkText . "</a>";
			echo $after_widget;
		}
	}
	 
	public function update($new_instance, $old_instance) {    	
		$instance = $old_instance;
		$instance["galleryType"] = strip_tags(stripslashes($new_instance["galleryType"]));
		$instance["listingID"] = strip_tags(stripslashes($new_instance["listingID"]));
		$instance["name"] = strip_tags(stripslashes($new_instance["name"]));
		$instance["propertiesShown"] = strip_tags(stripslashes($new_instance["propertiesShown"]));
		$instance["cityId"] = strip_tags(stripslashes($new_instance["cityId"]));
		$instance["propertyType"] = strip_tags(stripslashes($new_instance["propertyType"]));
		$instance["bed"] = strip_tags(stripslashes($new_instance["bed"]));
		$instance["bath"] = strip_tags(stripslashes($new_instance["bath"]));
		$instance["minPrice"] = strip_tags(stripslashes($new_instance["minPrice"]));
		$instance["maxPrice"] = strip_tags(stripslashes($new_instance["maxPrice"]));
		$instance["hotSheetId"] = strip_tags(stripslashes($new_instance["hotSheetId"]));
		$instance["linkText"] = strip_tags(stripslashes($new_instance["linkText"]));
		$instance = $this->contextUtility->updateContext($new_instance, $instance);
		return $instance;
	}
	
	public function form($instance) {
						
		$galleryType = ($instance) ? esc_attr($instance["galleryType"]) : "";
		$listingID = ($instance) ? esc_attr($instance["listingID"]) : "";
		$name = ($instance) ? esc_attr($instance["name"]) : "";
		$propertiesShown = ($instance) ? esc_attr($instance["propertiesShown"]) : "3";
		$cityId = ($instance) ? esc_attr($instance["cityId"]) : "";
		$propertyType = ($instance) ? esc_attr($instance["propertyType"]) : "";
		$bed = ($instance) ? esc_attr($instance["bed"]) : "";
		$bath = ($instance) ? esc_attr($instance["bath"]) : "";
		$minPrice = ($instance) ? esc_attr($instance["minPrice"]) : "";
		$maxPrice = ($instance) ? esc_attr($instance["maxPrice"]) : "";
		$hotSheetId = ($instance) ? esc_attr($instance["hotSheetId"]) : "";
		$linkText = ($instance) ? esc_attr($instance["linkText"]) : "View all";
		
		$formData = iHomefinderSearchFormFieldsUtility::getInstance()->getFormData();
		$hotsheetsList = $formData->getHotsheetList();
		$citiesList = $formData->getCitiesList();
		$propertyTypesList = $formData->getPropertyTypesList();
	
		?>
		<script type="text/javascript">
			function togglePropertyFormFields(current_radio) {
				if(current_radio == "hotSheet") {
					jQuery(".widgetName").show();
					jQuery(".linkText").show();
					jQuery(".hotSheet").show();
					jQuery(".numberProperties").show();
					jQuery(".namedSearch").hide();
				} else if(current_radio == "namedSearch") {
					jQuery(".widgetName").show();
					jQuery(".linkText").show();
					jQuery(".namedSearch").show();
					jQuery(".hotSheet").hide();
					jQuery(".numberProperties").show();
				} else if(current_radio == "linkSearch") {
					jQuery(".widgetName").hide();
					jQuery(".linkText").show();
					jQuery(".namedSearch").show();
					jQuery(".hotSheet").hide();
					jQuery(".numberProperties").hide();
				} else if(current_radio == "featuredListing") {
					jQuery(".numberProperties").show();
					jQuery(".widgetName").show();
					jQuery(".linkText").show();
					jQuery(".namedSearch").hide();
					jQuery(".hotSheet").hide();
				}
			}
		</script>
		<p>
			Gallery type:<br />
			<?php
				//set selected gallery type
				if($galleryType == null || $galleryType == "") {
					if(iHomefinderPermissions::getInstance()->isNamedSearchEnabled()) {
						$galleryType = "namedSearch";
					} elseif(iHomefinderPermissions::getInstance()->isLinkSearchEnabled()) {
						$galleryType = "linkSearch";
					} elseif(iHomefinderPermissions::getInstance()->isHotSheetEnabled()) {
						$galleryType = "hotSheet";
					} elseif(iHomefinderPermissions::getInstance()->isFeaturedPropertiesEnabled()) {
						$galleryType = "featuredListing";
					} else {
						$galleryType = "";
					}
				}
			?>
			<?php if(iHomefinderPermissions::getInstance()->isFeaturedPropertiesEnabled()) { ?>
				<label>
					<input onclick="togglePropertyFormFields(this.value);" <?php if($galleryType == 'featuredListing') echo 'checked="checked"'; ?> type="radio" value="featuredListing" name="<?php echo $this->get_field_name('galleryType'); ?>" />
					Featured Properties Gallery
				</label>
				<br />
			<?php } ?>
			<?php if(iHomefinderPermissions::getInstance()->isHotSheetEnabled()) { ?>
				<label>
					<input onclick="togglePropertyFormFields(this.value);" <?php if($galleryType == 'hotSheet') echo 'checked="checked"'; ?> type="radio" value="hotSheet" name="<?php echo $this->get_field_name('galleryType'); ?>" />
					Saved Search Page Gallery
				</label>
				<br />
			<?php } ?>
			<?php if(iHomefinderPermissions::getInstance()->isNamedSearchEnabled()) { ?>
				<label>
					<input onclick="togglePropertyFormFields(this.value);" <?php if($galleryType == 'namedSearch') echo 'checked="checked"'; ?> type="radio" value="namedSearch" name="<?php echo $this->get_field_name('galleryType'); ?>" />
					Dynamic Search Gallery
				</label>
				<br />
			<?php } ?>
			<?php if(iHomefinderPermissions::getInstance()->isLinkSearchEnabled()) { ?>
				<label>
					<input onclick="togglePropertyFormFields(this.value);" <?php if($galleryType == 'linkSearch') echo 'checked="checked"'; ?> type="radio" value="linkSearch" name="<?php echo $this->get_field_name('galleryType'); ?>" />
					Dynamic Search Link
				</label>
			<?php } ?>
		</p>
		<p id="widgetName" class="widgetName" <?php if($galleryType == 'linkSearch') echo 'style="display:none;"'; ?>>
			<label>
				Gallery Title:
				<input class="widefat" type="text" value="<?php echo $name; ?>" name="<?php echo $this->get_field_name('name'); ?>" />
			</label>
		</p>
		<p id="numberProperties" class="numberProperties" <?php if($galleryType == 'linkSearch') echo 'style="display:none;"'; ?>>
			<label>
				Number of Properties Shown:
				<select class="widefat" name="<?php echo $this->get_field_name('propertiesShown'); ?>">
					<?php for($i=1; $i<11; $i+=1) { ?>
						<option
							value="<?php echo $i; ?>"
							<?php if($propertiesShown == $i) {echo "selected";} ?>
						>
							<?php echo $i; ?>
						</option>
					<?php } ?>
				</select>
			</label>
		</p>
		<p id="linkText" class="linkText">
			<label>
				Link Text:
				<input class="widefat" type="text" value="<?php echo $linkText; ?>" name="<?php echo $this->get_field_name('linkText'); ?>" />
			</label>
		</p>
		<p id="hotSheet" class="hotSheet" <?php if($galleryType != 'hotSheet') echo 'style="display:none;"'; ?>>
			<label>
				Saved Search Pages:
				<select class="widefat" name="<?php echo $this->get_field_name('hotSheetId'); ?>">
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
			</label>
		</p>
		<div id="namedSearch" class="namedSearch" <?php if($galleryType != 'namedSearch' && $galleryType != 'linkSearch') echo 'style="display:none;"'; ?>>
			<p>
				<label>
					City:
					<select class="widefat" style="height: 100px;" name="<?php echo $this->get_field_name('cityId'); ?>" size="5">
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
				</label>
			</p>
			<p>
				<label>
					Property Type:
					<select class="widefat" name="<?php echo $this->get_field_name('propertyType'); ?>" >
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
				</label>
			</p>
			<p>
				<label>
					Bed:
					<input class="widefat" type="number" value="<?php echo $bed; ?>" name="<?php echo $this->get_field_name('bed'); ?>" />
				</label>
			</p>
			<p>
				<label>
					Bath:
					<input class="widefat" type="number" value="<?php echo $bath; ?>" name="<?php echo $this->get_field_name('bath'); ?>" />
				</label>
			</p>
			<p>
				<label>
					Minimum Price:
					<input class="widefat" type="number" value="<?php echo $minPrice; ?>" name="<?php echo $this->get_field_name('minPrice'); ?>" />
				</label>
			</p>
			<p>
				<label>
					Maximum Price:
					<input class="widefat" type="number" value="<?php echo $maxPrice; ?>" name="<?php echo $this->get_field_name('maxPrice'); ?>" />
				</label>
			</p>
		</div>
		<?php 
		//The following call echos a select context for pages to display.
		echo $this->contextUtility->getPageSelector($this, $instance, iHomefinderConstants::GALLERY_WIDGET_TYPE);
		?>
		<br />
		<?php
	}

}
