<?php
if( !class_exists('iHomefinderSearchByAddressWidget') ) {
	/**
	 * iHomefinderSearchByAddressWidget Class
	 */
	class iHomefinderSearchByAddressWidget extends WP_Widget {

    private $contextUtility;
	private $cacheUtility;

    public function __construct() {
      $options=array('description'=>'Search by Address form.');
      parent::WP_Widget( false,
                         $name = 'IDX: Address Search',
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

      	//sets vars like $before_widget from $args
	    extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
		
		$searchByAddressContent = $this->cacheUtility->getItem($this->id);
		if( empty($searchByAddressContent)){
			$authenticationToken=IHomefinderAdmin::getInstance()->getAuthenticationToken();
			$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=search-by-address-form' ;
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "smallView", "true" );
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "phpStyle", "true" );
			$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "style", $instance['style'] );
			
			$contentInfo = iHomefinderRequestor::remoteRequest($ihfUrl);
			
			$searchByAddressContent = (string) $contentInfo->view;
			$this->cacheUtility->updateItem( $this->id, $searchByAddressContent, 86400 );
		}
		
        echo $before_widget;
        if ( $title ) {
          echo $before_title . $title . $after_title;
        }
        echo $searchByAddressContent;        
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
      $instance['style'] = strip_tags(stripslashes($new_instance['style']));

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
      $style = esc_attr($instance['style']);
      ?>
      <p>
        <?php _e('Title:'); ?>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
      </p>

        <p>
          <?php _e('Layout:'); ?>
          <select class="widefat" id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>">
            <option value="vertical" <?php if($style=="vertical"){echo(' selected');}?>>Vertical</option>
            <option value="horizontal" <?php if($style=="horizontal"){echo(' selected');}?>>Horizontal</option>
          </select>
        </p>
      <?php
        $this->contextUtility->getPageSelector($this, $instance, IHomefinderConstants::SEARCH_OTHER_WIDGET_TYPE );
    }
  } // class iHomefinderSearchByAddressWidget
}//end if( !class_exists('iHomefinderSearchByAddressWidget'))
?>