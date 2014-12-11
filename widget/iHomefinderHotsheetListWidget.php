<?php
if( !class_exists('iHomefinderHotsheetListWidget') ) {
	/**
	 * iHomefinderHotsheetListWidget Class
	 */
	class iHomefinderHotsheetListWidget extends WP_Widget {

    private $contextUtility;
    private $cacheUtility;
	
    public function __construct() {
		$options=array('description'=>'List of Saved Search Pages');
		parent::WP_Widget( false,
					 $name = 'IDX: Saved Search Page List',
					 $widget_options=$options );
		$this->contextUtility=IHomefinderWidgetContextUtility::getInstance();
		$this->cacheUtility = new IHomefinderCacheUtility();
    }

    /**
     * Used to create the widget for display in the blog.
     *
     * @see WP_Widget::widget
     */
    public function widget($args, $instance) {

      global $blog_id;
      global $post;

      if( $this->contextUtility->isEnabled( $instance )) {

        extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
		
		
		$content = $this->cacheUtility->getItem($this->id);
		if( empty($content)){
			$authenticationToken=IHomefinderAdmin::getInstance()->getAuthenticationToken();
			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=hotsheet-list' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "smallView", "true" );
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "phpStyle", "true" );
			iHomefinderLogger::getInstance()->debug("url: " . $ihfUrl);
			$contentInfo = iHomefinderRequestor::remoteRequest($ihfUrl);
			$content = (string) $contentInfo->view;
			
			$this->cacheUtility->updateItem( $this->id, $content, 3600 );
		}
		
        echo $before_widget;
        if ( $title ) {
          echo $before_title . $title . $after_title;
        }
        
        if( IHomefinderLayoutManager::getInstance()->hasExtraLineBreaksInWidget()){
          echo "<br/>" ;	
          echo $content;
          echo "<br/>" ;
        } else {
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
	  
      //delete the cached item
      $this->cacheUtility->deleteItem( $this->id );
	
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
        $this->contextUtility->getPageSelector($this, $instance, IHomefinderConstants::SEARCH_OTHER_WIDGET_TYPE );
    }
  } // class iHomefinderHotsheetListWidget
}//end if( !class_exists('iHomefinderHotsheetListWidget'))
?>