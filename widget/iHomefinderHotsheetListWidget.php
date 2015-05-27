<?php

class iHomefinderHotsheetListWidget extends WP_Widget {

	private $contextUtility;

	public function __construct() {
		parent::__construct(
			"iHomefinderHotsheetListWidget",
			"IDX: Saved Search Page List",
			array(
				"description" => "List of Saved Search Pages"
			)
		);
		$this->contextUtility = iHomefinderWidgetContextUtility::getInstance();
	}
	
	public function widget($args, $instance) {
		if($this->contextUtility->isEnabled($instance)) {
		
			$includeAll = filter_var($instance["includeAll"], FILTER_VALIDATE_BOOLEAN);
			
			$before_widget = $args["before_widget"];
			$after_widget = $args["after_widget"];
			$before_title = $args["before_title"];
			$after_title = $args["after_title"];
			
			$title = apply_filters("widget_title", $instance["title"]);
			
			$remoteRequest = new iHomefinderRequestor();
				
			$remoteRequest
				->addParameter("method", "handleRequest")
				->addParameter("viewType", "json")
				->addParameter("requestType", "hotsheet-list")
				->addParameter("smallView", true)
				->addParameter("phpStyle", true)
			;
			
			if($includeAll === false &&
				array_key_exists("hotsheetIds", $instance) &&
				is_array($instance["hotsheetIds"])
			) {
				$hotsheetIds = array();
				foreach($instance["hotsheetIds"] as $index => $hotsheetId) {
					$hotsheetIds[] = $hotsheetId;
				}
				$remoteRequest->addParameter("hotsheetIds", $hotsheetIds);
			}
			
			$remoteRequest->setCacheExpiration(60*60);
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
			
			echo $after_widget;
		}
	}
	
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		
		$instance["title"] = strip_tags(stripslashes($new_instance["title"]));
		$instance["hotsheetIds"] = $new_instance["hotsheetIds"];
		$instance["includeAll"] = $new_instance["includeAll"];
		
		//Add context related values.
		$instance = $this->contextUtility->updateContext($new_instance, $instance);
		
		return $instance;
	}
	
	public function form($instance) {
		
		$title = esc_attr($instance["title"]);
		$hotsheetIds = $instance["hotsheetIds"];
		
		$includeAll = true;
		if($instance["includeAll"] !== null) {
			$includeAll = filter_var($instance["includeAll"], FILTER_VALIDATE_BOOLEAN);
		}
		
		$galleryFormData = iHomefinderSearchFormFieldsUtility::getInstance()->getFormData();
		$clientHotsheets = $galleryFormData->getHotsheetList();
		
		?>
		<p>
			<label>
				Title:
				<input class="widefat" id="<?php echo $this->get_field_id("title"); ?>" name="<?php echo $this->get_field_name("title"); ?>" type="text" value="<?php echo $title; ?>" />
			</label>
		</p>
		<p>
			<?php
			$includeAllTrueChecked = "";
			$includeAllFalseChecked = "";
			if($includeAll === true) {
				$includeAllTrueChecked = "checked=\"checked\"";
			} else {
				$includeAllFalseChecked = "checked=\"checked\"";
			}
			?>
			<label>
				<input type="radio" name="<?php echo $this->get_field_name("includeAll"); ?>" value="true" onclick="jQuery(this).closest('form').find('.hotsheetList').hide()" <?php echo $includeAllTrueChecked ?> />
				Show all Saved Search Pages
			</label>
			<br />
			<label>
				<input type="radio" name="<?php echo $this->get_field_name("includeAll"); ?>" value="false" onclick="jQuery(this).closest('form').find('.hotsheetList').show()" <?php echo $includeAllFalseChecked ?> />
				Show Selected Saved Search Pages
			</label>
		</p>
		<?php
		$hotsheetListStyle = "";
		if($includeAll) {
			$hotsheetListStyle = "display: none;";
		}
		?>
		<p class="hotsheetList" style="<?php echo $hotsheetListStyle ?>">
			<label>
				Saved Search Pages:
				<select class="widefat" name="<?php echo $this->get_field_name("hotsheetIds"); ?>[]" multiple="multiple">
					<?php
					foreach($clientHotsheets as $index => $clientHotsheet) {
						$hotsheetIdSelected = "";
						if(is_array($hotsheetIds) && in_array($clientHotsheet->hotsheetId, $hotsheetIds)) {
							$hotsheetIdSelected = "selected=\"selected\"";
						}
						?>
						<option value="<?php echo $clientHotsheet->hotsheetId ?>" <?php echo $hotsheetIdSelected ?>>
							<?php echo $clientHotsheet->displayName ?>
						</option>
						<?php
					}
					?>
				</select>
			</label>
		</p>
		<?php
		$this->contextUtility->getPageSelector($this, $instance, iHomefinderConstants::SEARCH_OTHER_WIDGET_TYPE);
		?>
		<br />
		<?php
	}

}