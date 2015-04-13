<?php

class iHomefinderContactFormWidget extends WP_Widget {

	private $contextUtility;

	public function __construct() {
		$options = array("description" => "Contact form.");
		parent::WP_Widget(false, $name = "IDX: Contact Form", $widget_options = $options);
		$this->contextUtility = iHomefinderWidgetContextUtility::getInstance();
	}
	
	public function widget($args, $instance) {
		if($this->contextUtility->isEnabled($instance)) {
			
			extract($args);
			$title = apply_filters("widget_title", $instance["title"]);
			
			$remoteRequest = new iHomefinderRequestor();
			
			$remoteRequest
				->addParameter("method", "handleRequest")
				->addParameter("viewType", "json")
				->addParameter("requestType", "FeatureContactForm")
				->addParameter("smallView", true)
				->addParameter("phpStyle", true)
			;
			
			$contentInfo = $remoteRequest->remoteGetRequest(3600);
			$content = $remoteRequest->getContent($contentInfo);
			iHomefinderEnqueueResource::getInstance()->addToFooter($contentInfo->head);
			
			echo $before_widget;
			
			if ($title) {
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
		$this->contextUtility->getPageSelector($this, $instance, iHomefinderConstants::CONTACT_WIDGET_TYPE);
	}
}