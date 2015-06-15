<?php

class iHomefinderSearchByAddressWidget extends WP_Widget {

	private $widgetUtility;
	private $widgetType = iHomefinderWidgetUtility::SEARCH_OTHER_WIDGET_TYPE;

	public function __construct() {
		parent::__construct(
			"iHomefinderSearchByAddressWidget",
			"IDX: Address Search",
			array(
				"description" => "Search by Address form."
			)
		);
		$this->widgetUtility = iHomefinderWidgetUtility::getInstance();
	}
	
	public function widget($args, $instance) {
		if($this->widgetUtility->isEnabled($this, $instance, $this->widgetType)) {
			$beforeWidget = $args["before_widget"];
			$afterWidget = $args["after_widget"];
			$beforeTitle = $args["before_title"];
			$afterTitle = $args["after_title"];
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
			$remoteRequest->setCacheExpiration(60*60*24);
			$remoteResponse = $remoteRequest->remoteGetRequest();
			$content = $remoteResponse->getBody();
			iHomefinderEnqueueResource::getInstance()->addToFooter($remoteResponse->getHead());
			echo $beforeWidget;
			if(!empty($title)) {
				echo $beforeTitle . $title . $afterTitle;
			}
			echo $content;
			echo $afterWidget;
		}
	}
	
	public function update($newInstance, $oldInstance) {
		$instance = $oldInstance;
		$instance["title"] = strip_tags(stripslashes($newInstance["title"]));
		$instance["style"] = strip_tags(stripslashes($newInstance["style"]));
		$instance = $this->widgetUtility->updateContext($newInstance, $instance);
		return $instance;
	}
	
	public function form($instance) {
		$title = esc_attr($instance["title"]);
		$style = esc_attr($instance["style"]);
		?>
		<p>
			<label>
				Title:
				<input class="widefat" id="<?php echo $this->get_field_id("title"); ?>" name="<?php echo $this->get_field_name("title"); ?>" type="text" value="<?php echo $title; ?>" />
			</label>
		</p>
		<p>
			<label>
				Layout:
				<select class="widefat" id="<?php echo $this->get_field_id("style"); ?>" name="<?php echo $this->get_field_name("style"); ?>">
					<option value="vertical" <?php if($style=="vertical") {echo "selected";} ?>>Vertical</option>
					<option value="horizontal" <?php if($style=="horizontal") {echo "selected";} ?>>Horizontal</option>
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