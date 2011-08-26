<?php
if( !class_exists('iHomefinderQuickSearchWidget')) {
	/**
	 * iHomefinderQuickSearchWidget Class
	 */
	class iHomefinderQuickSearchWidget extends WP_Widget {
	    /** constructor */
	    function iHomefinderQuickSearchWidget() {
	    	$options=array('description'=>'Property Search form.');
	        parent::WP_Widget( false,
	                           $name = 'Optima Express Quick Search',
	                           $widget_options=$options );
	    }

	    /**
	     * Used to create the widget for display in the blog.
	     *
	     * @see WP_Widget::widget
	     */
	    function widget($args, $instance) {
	    	global $blog_id;
	    	global $post;

	    	//Do not display the search widget on the search form page
	    	$type = get_query_var(IHomefinderConstants::IHF_TYPE_URL_VAR );
	    	if( $type && $type==IHomefinderFilterFactory::LISTING_SEARCH_FORM ){
	    		return;
	    	}

	        extract( $args );
	        $title = apply_filters('widget_title', $instance['title']);

            $propertyGalleryContent = $this->getCachedVersion($instance);
            if( empty($propertyGalleryContent)){
                $authenticationToken=IHomefinderAdmin::getInstance()->getAuthenticationToken();
                $ihfUrl = iHomefinderConstants::EXTERNAL_URL . '?method=handleRequest&viewType=json&requestType=listing-search-form' ;
                $ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "authenticationToken", $authenticationToken);
                $ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "smallView", "true" );
                $ihfUrl = iHomefinderRequestor::appendQueryVarIfNotEmpty($ihfUrl, "phpStyle", "true" );
                $contentInfo = iHomefinderRequestor::remoteRequest($ihfUrl);
                $propertyGalleryContent = $contentInfo->view;
                //$propertyGalleryContent=IHomefinderRequestor::cleanURLs( $propertyGalleryContent );
                $this->updateCache($propertyGalleryContent);
           }
           echo $before_widget;
           if ( $title ){
               echo $before_title . $title . $after_title;
           }
           echo "<br/>" . $propertyGalleryContent . "<br/>";
           echo $after_widget;
	    }

	    /**
	     *  Processes form submission in the admin area for configuring
	     *  the widget.
	     *
	     *  @see WP_Widget::update
	     */
	    function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['title'] = strip_tags(stripslashes($new_instance['title']));
			delete_transient($cacheKey);
	        return $instance;
	    }

	     /**
          * Get a cached version of the widget output.
          * @param $instance
          */
         function getCachedVersion($instance){
             $cacheKey=$this->getCacheKey();
             // Fetch a saved transient
             $propertyGalleryContent = get_transient($cacheKey);
             return $propertyGalleryContent   ;
         }

		 function getCacheKey( ){
	    	$widgetId=$this->id;
        	$cacheKey=iHomefinderConstants::PROPERTY_GALLERY_CACHE . "_" .  $widgetId;
        	return $cacheKey;
        }

         function updateCache( $propertyGalleryContent ){
         	$cacheKey=$this->getCacheKey();
			set_transient($cacheKey, $propertyGalleryContent, IHomefinderConstants::PROPERTY_GALLERY_CACHE_TIMEOUT);
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

	} // class iHomefinderQuickSearchWidget
}//end if( !class_exists('iHomefinderQuickSearchWidget'))
?>
