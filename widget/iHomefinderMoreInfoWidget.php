<?php
if( !class_exists('iHomefinderMoreInfoWidget')) {
	/**
	 * iHomefinderMoreInfoWidget Class
	 */
	class iHomefinderMoreInfoWidget extends WP_Widget {
	
		/** constructor */
	    function iHomefinderMoreInfoWidget() {
	    	$options=array('description'=>'Displays a More Information form on listing detail virtual pages.');
	        parent::WP_Widget( false,
	                           $name = 'IDX: More Info',
	                           $widget_options=$options );
	    }

	    /**
	     * Used to create the widget for display in the blog.
	     *
	     * @see WP_Widget::widget
	     */
	    function widget($args, $instance) {
	    	if( IHomefinderStateManager::getInstance()->hasListingInfo() ){

	    		//sets vars like $before_widget from $args
	    		extract( $args );
	    		
				$authenticationToken=IHomefinderAdmin::getInstance()->getAuthenticationToken();
	    		$ihfUrl = IHomefinderLayoutManager::getInstance()->getExternalUrl() . '?method=handleRequest&viewType=json&requestType=request-more-info-widget' ;
	    		$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
	    		$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "smallView", "true" );
	    		$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "phpStyle", "true" );
	    		
	    		$listingInfo=IHomefinderStateManager::getInstance()->getCurrentListingInfo();
	    		$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "boardId", $listingInfo->getBoardId() );
	    		$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "listingNumber", $listingInfo->getListingNumber() );
	    		$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "listingAddress", $listingInfo->getAddress() );
	    		$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "clientPropertyId", $listingInfo->getClientPropertyId() );
	    		$ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "sold", $listingInfo->getSold() );
	    		
	    		$contentInfo = iHomefinderRequestor::remoteRequest($ihfUrl);
	    		
	    		$moreInfoContent = $contentInfo->view;
	    		$title = apply_filters('widget_title', $instance['title']);
	    		
				echo $before_widget;
		    	if ( $title ){
		    		echo $before_title . $title . $after_title;
		    	}
	    		echo $moreInfoContent ;
		    	echo $after_widget;	    		
	    	}
	    }

	    /**
	     *  Processes form submission in the admin area for configuring
	     *  the widget.
	     *
	     *  @see WP_Widget::update
	     */
	  function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));			
        return $instance;	  	
	  }



	  /**
	   * Create the admin form, for adding the Widget to the blog.
	   *
	   *  @see WP_Widget::form
	   */
	    function form($instance) {
	        $title = esc_attr($instance['title']);
	    ?>
	            <p>
	            	<?php _e('Title:'); ?>
	            	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
	            </p>
	    <?php 
	            
	    }
	  
	} // class iHomefinderMoreInfoWidget
}//end if( !class_exists('iHomefinderMoreInfoWidget'))

?>
