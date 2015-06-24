<?php

class iHomefinderSearchByListingIdWidget extends WP_Widget {
	
	private $widgetUtility;
	private $widgetType = iHomefinderWidgetUtility::SEARCH_OTHER_WIDGET_TYPE;
	
	public function __construct() {
		parent::__construct(
			"iHomefinderSearchByListingIdWidget",
			"IDX: Listing ID Search",
			array(
				"description" => "Search by Listing ID form."
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
				->addParameter("requestType", "search-by-listing-id-form")
				->addParameter("smallView", true)
				->addParameter("phpStyle", true)
			;
			if(array_key_exists("style", $instance)) {
				$remoteRequest->addParameter("style", $instance["style"]);
			}
			$remoteRequest->setCacheExpiration(60*60*24);
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
		$instance = $this->widgetUtility->updateContext($newInstance, $instance);
		return $instance;
	}
	
	public function form($instance) {
		$title = esc_attr($instance["title"]);
		?>
		<p>
			<label>
				Title:
				<input class="widefat" id="<?php echo $this->get_field_id("title"); ?>" name="<?php echo $this->get_field_name("title"); ?>" type="text" value="<?php echo $title; ?>" />
			</label>
		</p>
		<?php
		$this->widgetUtility->getPageSelector($this, $instance, $this->widgetType);
		?>
		<br />
		<?php
	}
	
}