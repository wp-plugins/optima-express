<?php

class iHomefinderQuickSearchWidget extends WP_Widget {

	private $contextUtility;
	
	public function __construct() {
		$options=array("description"=>"Property Search form.");
		parent::WP_Widget(false,
						   $name = "IDX: Quick Search",
						   $widget_options=$options);
		$this->contextUtility=iHomefinderWidgetContextUtility::getInstance();
	}
	
	public function widget($args, $instance) {
		//Do not display the search widget on the search form page
		
		$type = get_query_var(iHomefinderConstants::IHF_TYPE_URL_VAR);
		if(!iHomefinderStateManager::getInstance()->isSearchContext()) {
			if($this->contextUtility->isEnabled($instance)) {
				extract($args);
				$title = apply_filters("widget_title", $instance["title"]);
				
				$remoteRequest = new iHomefinderRequestor();
					
				$remoteRequest
					->addParameter("method", "handleRequest")
					->addParameter("viewType", "json")
					->addParameter("requestType", "listing-search-form")
					->addParameter("smallView", true)
					->addParameter("phpStyle", true)
					->addParameter("style", $instance["style"])
					->addParameter("showPropertyType", $instance["showPropertyType"])
				;
				$remoteRequest->setCacheExpiration(60*60*24);
				$contentInfo = $remoteRequest->remoteGetRequest();
				$content = $remoteRequest->getContent($contentInfo);
				iHomefinderEnqueueResource::getInstance()->addToFooter($contentInfo->head);
				
				echo $before_widget;
				if($title) {
					echo $before_title . $title . $after_title;
				}
				
				if(iHomefinderLayoutManager::getInstance()->hasExtraLineBreaksInWidget()) {
					echo "<br/>";	
					echo $content;
					echo "<br/>";
				} else {
					echo $content;
				}
	
				echo $after_widget;
			}
		}
	}
	
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance["title"] = strip_tags(stripslashes($new_instance["title"]));
		$instance["style"] = strip_tags(stripslashes($new_instance["style"]));
		$instance["showPropertyType"] = $new_instance["showPropertyType"];
		
		//Add context related values.
		$instance = $this->contextUtility->updateContext($new_instance, $instance);
		
		return $instance;
	}
	
	function form($instance) {
		$title = esc_attr($instance["title"]);
		$style = esc_attr($instance["style"]);
		$showPropertyType = $instance["showPropertyType"];
		?>
		<p>
			<label>
				Title:
				<input class="widefat" id="<?php echo $this->get_field_id("title"); ?>" name="<?php echo $this->get_field_name("title"); ?>" type="text" value="<?php echo $title; ?>" />
			</label>
		</p>
			
		<?php if(iHomefinderLayoutManager::getInstance()->supportsMultipleQuickSearchLayouts()) { ?>
			<p>
				<label>
					Style:
					<select class="widefat" id="<?php echo $this->get_field_id("style"); ?>" name="<?php echo $this->get_field_name("style"); ?>">
						<option value="vertical" <?php if($style=="vertical") {echo(' selected');} ?>>Vertical</option>
						<option value="horizontal" <?php if($style=="horizontal") {echo(' selected');} ?>>Horizontal</option>
						<option value="twoline" <?php if($style=="twoline") {echo(' selected');} ?>>Two Line</option>
					</select>
				</label>	         		
			</p>
			<?php } ?>
			<?php if(iHomefinderLayoutManager::getInstance()->supportsQuickSearchPropertyType()) { ?>
			<p>
				<label>
					<input type="checkbox" name="<?php echo $this->get_field_name("showPropertyType"); ?>" value="true" <?php if($showPropertyType === "true") {echo "checked";} ?> />
					<span><?php _e("Show Property Type"); ?></span>
				</label>         		
			</p>
		<?php } ?>
		<?php 
		$this->contextUtility->getPageSelector($this, $instance, iHomefinderConstants::SEARCH_WIDGET_TYPE);
		?>
		<br />
		<?php
	}
	
}
