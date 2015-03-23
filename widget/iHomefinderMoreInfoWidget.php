<?php

/**
 * iHomefinderMoreInfoWidget Class
 */
class iHomefinderMoreInfoWidget extends WP_Widget {

	public function __construct() {
		$options=array('description'=>'Displays a More Information form on listing detail virtual pages.');
		parent::WP_Widget(false,
						   $name = 'IDX: More Info',
						   $widget_options=$options);
	}

	/**
	 * Used to create the widget for display in the blog.
	 *
	 * @see WP_Widget::widget
	 */
	function widget($args, $instance) {
		if(iHomefinderStateManager::getInstance()->hasListingInfo()) {

			//sets vars like $before_widget from $args
			extract($args);
			
			$requestData = 'method=handleRequest&viewType=json&requestType=request-more-info-widget';
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "smallView", "true");
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "phpStyle", "true");
			
			$listingInfo=iHomefinderStateManager::getInstance()->getCurrentListingInfo();
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "boardId", $listingInfo->getBoardId());
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "listingNumber", $listingInfo->getListingNumber());
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "listingAddress", $listingInfo->getAddress());
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "clientPropertyId", $listingInfo->getClientPropertyId());
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "sold", $listingInfo->getSold());
			
			$contentInfo = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData);
			$content = iHomefinderRequestor::getInstance()->getContent($contentInfo);
			iHomefinderEnqueueResource::getInstance()->addToFooter($contentInfo->head);
			
			$title = apply_filters('widget_title', $instance['title']);
			
			echo $before_widget;
			if ($title) {
				echo $before_title . $title . $after_title;
			}
			echo $content;
			echo $after_widget;	    		
		}
	}

	/**
	 *  Processes form submission in the admin area for configuring
	 *  the widget.
	 *
	 *  @see WP_Widget::update
	 */
  public function update($new_instance, $old_instance) {
	$instance = $old_instance;
	$instance['title'] = strip_tags(stripslashes($new_instance['title']));			
	return $instance;	  	
  }
  
  /**
   * Create the admin form, for adding the Widget to the blog.
   *
   *  @see WP_Widget::form
   */
	public function form($instance) {
		$title = esc_attr($instance['title']);
		?>
		<p>
			<?php _e('Title:'); ?>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<?php      
	}
  
}
