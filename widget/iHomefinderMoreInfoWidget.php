<?php

class iHomefinderMoreInfoWidget extends WP_Widget {

	public function __construct() {
		parent::__construct(
			"iHomefinderMoreInfoWidget",
			"IDX: More Info",
			array(
				"description" => "Displays a More Information form on listing detail virtual pages."
			)
		);
	}
	
	function widget($args, $instance) {
		if(iHomefinderStateManager::getInstance()->hasListingInfo()) {
			$beforeWidget = $args["before_widget"];
			$afterWidget = $args["after_widget"];
			$beforeTitle = $args["before_title"];
			$afterTitle = $args["after_title"];
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
			$remoteResponse = $remoteRequest->remoteGetRequest();
			$content = $remoteResponse->getBody();
			iHomefinderEnqueueResource::getInstance()->addToFooter($remoteResponse->getHead());
			$title = apply_filters("widget_title", $instance["title"]);
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
	}
	
}
