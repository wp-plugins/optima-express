<?php

class iHomefinderSearchByListingIdWidget extends WP_Widget {
	
	private $contextUtility;

	public function __construct() {
		$options=array("description"=>"Search by Listing ID form.");
		parent::WP_Widget(false, $name = "IDX: Listing ID Search", $widget_options = $options);
		$this->contextUtility=iHomefinderWidgetContextUtility::getInstance();
	}
	
	public function widget($args, $instance) {
		if($this->contextUtility->isEnabled($instance)) {
			extract($args);
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
		
		//Add context related values.
		$instance = $this->contextUtility->updateContext($new_instance, $instance);

		return $instance;
	}
	
	public function form($instance) {
		$title = esc_attr($instance["title"]);
		?>
		<p>
			<?php _e("Title:"); ?>
			<input class="widefat" id="<?php echo $this->get_field_id("title"); ?>" name="<?php echo $this->get_field_name("title"); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<?php
		$this->contextUtility->getPageSelector($this, $instance, iHomefinderConstants::SEARCH_OTHER_WIDGET_TYPE);
	}
	
}