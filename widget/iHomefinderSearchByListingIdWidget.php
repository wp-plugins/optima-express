<?php

/**
 * iHomefinderSearchByListingIdWidget Class
 */
class iHomefinderSearchByListingIdWidget extends WP_Widget {
	
	private $contextUtility;

	public function __construct() {
	  $options=array('description'=>'Search by Listing ID form.');
	  parent::WP_Widget(false,
						 $name = 'IDX: Listing ID Search',
						 $widget_options=$options);
	  $this->contextUtility=iHomefinderWidgetContextUtility::getInstance();
	}

	/**
	 * Used to create the widget for display in the blog.
	 *
	 * @see WP_Widget::widget
	 */
	public function widget($args, $instance) {

	  global $blog_id;
	  global $post;

	  if($this->contextUtility->isEnabled($instance)) {

		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		
		$requestData = 'method=handleRequest&viewType=json&requestType=search-by-listing-id-form';
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "smallView", "true");
		$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "phpStyle", "true");
		if(array_key_exists("style", $instance)) {
			$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "style", $instance['style']);
		}
		$contentInfo = iHomefinderRequestor::getInstance()->remoteGetRequest($requestData, 86400);
		$content = iHomefinderRequestor::getInstance()->getContent($contentInfo);
		iHomefinderEnqueueResource::getInstance()->addToFooter($contentInfo->head);
		
		echo $before_widget;
		if ($title) {
		  echo $before_title . $title . $after_title;
		}
		
		if(iHomefinderLayoutManager::getInstance()->hasExtraLineBreaksInWidget()) {
		  echo "<br/>";	
		  echo $content;
		  echo "<br/>";
		}
		else{
		  echo $content;
		}
		
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
	  
	  //Add context related values.
	  $instance = $this->contextUtility->updateContext($new_instance, $instance);

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
		$this->contextUtility->getPageSelector($this, $instance, iHomefinderConstants::SEARCH_OTHER_WIDGET_TYPE);
	}
}