<?php

class iHomefinderHotsheetListWidget extends WP_Widget {

	private $widgetUtility;
	private $widgetType = iHomefinderWidgetUtility::SEARCH_OTHER_WIDGET_TYPE;

	public function __construct() {
		parent::__construct(
			"iHomefinderHotsheetListWidget",
			"IDX: Saved Search Page List",
			array(
				"description" => "List of Saved Search Pages."
			)
		);
		$this->widgetUtility = iHomefinderWidgetUtility::getInstance();
	}
	
	public function widget($args, $instance) {
		if($this->widgetUtility->isEnabled($this, $instance, $this->widgetType)) {
			$includeAll = filter_var($instance["includeAll"], FILTER_VALIDATE_BOOLEAN);
			$beforeWidget = $args["before_widget"];
			$afterWidget = $args["after_widget"];
			$beforeTitle = $args["before_title"];
			$afterTitle = $args["after_title"];
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
			$remoteResponse = $remoteRequest->remoteGetRequest();
			$content = $remoteResponse->getBody();
			iHomefinderEnqueueResource::getInstance()->addToFooter($remoteResponse->getHead());
			echo $beforeWidget;
			if(!empty($title)) {
				echo $beforeTitle . $title . $afterTitle;
			}
			if(iHomefinderLayoutManager::getInstance()->hasExtraLineBreaksInWidget()) {
				echo "<br />";	
				echo $content;
				echo "<br />";
			} else {
				echo $content;
			}
			echo $afterWidget;
		}
	}
	
	public function update($newInstance, $oldInstance) {
		$instance = $oldInstance;
		$instance["title"] = strip_tags(stripslashes($newInstance["title"]));
		$instance["hotsheetIds"] = $newInstance["hotsheetIds"];
		$instance["includeAll"] = $newInstance["includeAll"];
		$instance = $this->widgetUtility->updateContext($newInstance, $instance);
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
		$this->widgetUtility->getPageSelector($this, $instance, $this->widgetType);
		?>
		<br />
		<?php
	}

}