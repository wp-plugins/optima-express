<?php

class iHomefinderMoreInfoWidget extends WP_Widget {

	public function __construct() {
		$options = array("description"=>"Displays a More Information form on listing detail virtual pages.");
		parent::WP_Widget(false,  $name = "IDX: More Info",  $widget_options=$options);
	}
	
	function widget($args, $instance) {
		if(iHomefinderStateManager::getInstance()->hasListingInfo()) {
			
			//sets vars like $before_widget from $args
			extract($args);
			
			$listingInfo = iHomefinderStateManager::getInstance()->getCurrentListingInfo();
			
			$remoteRequest = new iHomefinderRequestor();
			
			$remoteRequest
				->addParameter("method", "handleRequest")
				->addParameter("viewType", "json")
				->addParameter("requestType", "request-more-info-widget")
				->addParameter("smallView", true)
				->addParameter("phpStyle", true)
				->addParameter("boardId", $listingInfo->getBoardId())
				->addParameter("listingNumber", $listingInfo->getListingNumber())
				->addParameter("listingAddress", $listingInfo->getAddress())
				->addParameter("clientPropertyId", $listingInfo->getClientPropertyId())
				->addParameter("sold", $listingInfo->getSold())
			;
			
			$contentInfo = $remoteRequest->remoteGetRequest();
			$content = $remoteRequest->getContent($contentInfo);
			iHomefinderEnqueueResource::getInstance()->addToFooter($contentInfo->head);
			
			$title = apply_filters("widget_title", $instance["title"]);
			
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
	}
	
}
