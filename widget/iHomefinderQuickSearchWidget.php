<?php

/**
 * iHomefinderQuickSearchWidget Class
 */
class iHomefinderQuickSearchWidget extends WP_Widget {

	private $contextUtility;
	
	public function __construct() {
		$options=array('description'=>'Property Search form.');
		parent::WP_Widget(false,
						   $name = 'IDX: Quick Search',
						   $widget_options=$options);
		$this->contextUtility=iHomefinderWidgetContextUtility::getInstance();
	}

	/**
	 * Used to create the widget for display in the blog.
	 *
	 * @see WP_Widget::widget
	 */
	public function widget($args, $instance) {
		//Do not display the search widget on the search form page
		
		$type = get_query_var(iHomefinderConstants::IHF_TYPE_URL_VAR);
		if(!iHomefinderStateManager::getInstance()->isSearchContext()) {
			if($this->contextUtility->isEnabled($instance)) {
				extract($args);
				$title = apply_filters('widget_title', $instance['title']);

				$requestData = 'method=handleRequest&viewType=json&requestType=listing-search-form';
				$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "smallView", "true");
				$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "phpStyle", "true");
				$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "style", $instance['style']);
				$requestData = iHomefinderRequestor::getInstance()->appendQueryVarIfNotEmpty($requestData, "showPropertyType", $instance['showPropertyType']);
				
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
		$instance['style'] = strip_tags(stripslashes($new_instance['style']));
		$instance['showPropertyType'] = $new_instance['showPropertyType'];
		
		//Add context related values.
		$instance = $this->contextUtility->updateContext($new_instance, $instance);
		
		return $instance;
	}
	
	/**
	 * Create the admin form, for adding the Widget to the blog.
	 *
	 *  @see WP_Widget::form
	 */
	function form($instance) {
		$title = esc_attr($instance['title']);
		$style = esc_attr($instance['style']);
		$showPropertyType = $instance['showPropertyType'];
	?>
			<p>
				<?php _e('Title:'); ?>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
			</p>
			
			<?php if(iHomefinderLayoutManager::getInstance()->supportsMultipleQuickSearchLayouts()) {?>
			<p>
				<?php _e('Style:'); ?>
				<select class="widefat" id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>">
					<option value="vertical" <?php if($style=="vertical") {echo(' selected');}?>>Vertical</option>
					<option value="horizontal" <?php if($style=="horizontal") {echo(' selected');}?>>Horizontal</option>
					<option value="twoline" <?php if($style=="twoline") {echo(' selected');}?>>Two Line</option>
				</select>	         		
			</p>
			<?php }?>
			<?php if(iHomefinderLayoutManager::getInstance()->supportsQuickSearchPropertyType()) {?>
			<p>
				<label>
					<input type="checkbox" name="<?php echo $this->get_field_name('showPropertyType'); ?>" value="true" <?php if($showPropertyType === "true") {echo "checked";} ?> />
					<span><?php _e('Show Property Type'); ?></span>
				</label>         		
			</p>
			<?php }?>
	<?php 
		$this->contextUtility->getPageSelector($this, $instance, iHomefinderConstants::SEARCH_WIDGET_TYPE);
			
	}
	
}
