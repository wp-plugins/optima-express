<?php

class iHomefinderSearchByAddressWidget extends WP_Widget {

	private $contextUtility;

	public function __construct() {
		$options=array("description" => "Search by Address form.");
		parent::WP_Widget(false, $name = "IDX: Address Search", $widget_options = $options);
		$this->contextUtility = iHomefinderWidgetContextUtility::getInstance();
	}
	
	public function widget($args, $instance) {
		if($this->contextUtility->isEnabled($instance)) {
			//sets vars like $before_widget from $args
			extract($args);
			$title = apply_filters("widget_title", $instance["title"]);
		
			$remoteRequest = new iHomefinderRequestor();
			
			$remoteRequest
				->addParameter("method", "handleRequest")
				->addParameter("viewType", "json")
				->addParameter("requestType", "search-by-address-form")
				->addParameter("smallView", true)
				->addParameter("phpStyle", true)
				->addParameter("style", $instance["style"])
			;
		
			$contentInfo = $remoteRequest->remoteGetRequest(86400);
			$content = $remoteRequest->getContent($contentInfo);
			iHomefinderEnqueueResource::getInstance()->addToFooter($contentInfo->head);
		
			echo $before_widget;
			if ($title) {
				echo $before_title . $title . $after_title;
			}
			echo $content;
			echo $after_widget;
		}
	}
	
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance["title"] = strip_tags(stripslashes($new_instance["title"]));
		$instance["style"] = strip_tags(stripslashes($new_instance["style"]));

		//Add context related values.
		$instance = $this->contextUtility->updateContext($new_instance, $instance);

		return $instance;
	}
	
	public function form($instance) {
		$title = esc_attr($instance["title"]);
		$style = esc_attr($instance["style"]);
		?>
		<p>
			<?php _e("Title:"); ?>
			<input class="widefat" id="<?php echo $this->get_field_id("title"); ?>" name="<?php echo $this->get_field_name("title"); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<?php _e("Layout:"); ?>
			<select class="widefat" id="<?php echo $this->get_field_id("style"); ?>" name="<?php echo $this->get_field_name("style"); ?>">
			<option value="vertical" <?php if($style=="vertical") {echo "selected";} ?>>Vertical</option>
			<option value="horizontal" <?php if($style=="horizontal") {echo "selected";} ?>>Horizontal</option>
			</select>
		</p>
		<?php
		$this->contextUtility->getPageSelector($this, $instance, iHomefinderConstants::SEARCH_OTHER_WIDGET_TYPE);
	}
	
}