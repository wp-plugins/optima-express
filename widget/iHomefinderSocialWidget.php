<?php
if( !class_exists('iHomefinderSocialWidget')) {
	/**
	 * iHomefinderSocialWidget Class
	 */
	class iHomefinderSocialWidget extends WP_Widget {
	    
		/** constructor */
	    function iHomefinderSocialWidget() {
	    	$options=array('description'=>'Displays an social links.');
	        parent::WP_Widget( false,
	                           $name = 'Optima Express Social',
	                           $widget_options=$options );
	    }

	    /**
	     * Used to create the widget for display in the blog.
	     *
	     * @see WP_Widget::widget
	     */
	    function widget($args, $instance) {
	    	
	    	$facebookUrl= get_option( IHomefinderConstants::FACEBOOK_URL_OPTION );
	    	$twitterUrl=  get_option( IHomefinderConstants::TWITTER_URL_OPTION );
	    	$linkedinUrl= get_option( IHomefinderConstants::LINKEDIN_URL_OPTION );
	    	
	    	$baseUrl=plugins_url("/optima-express");
	    	

		    echo $before_widget;			
		
		    // WIDGET CODE GOES HERE
    		echo("<div id='social-icons'>");
			if($facebookUrl) {
	       		echo '<a href="http://www.facebook.com/' . $facebookUrl . '"><img src="' . $baseUrl . '/images/fbicon.png" width="24" height="24" style="width:24px;" /></a> ';
			}
			if($twitterUrl) {
    	   		echo '<a href="http://www.twitter.com/' . $twitterUrl . '"><img src="' . $baseUrl . '/images/twittericon.png" width="24" height="24" style="width:24px;" /></a> ';
			}
			if($linkedinUrl) {
       			echo '<a href="http://www.linkedin.com/' . $linkedinUrl . '"><img src="' . $baseUrl . '/images/linkedinicon.png" width="24" height="24" style="width:24px;" /></a> ';
			}
      		echo("</div>");
    		echo $after_widget;	    	
	    	
	    }

	    /**
	     *  Processes form submission in the admin area for configuring
	     *  the widget.
	     *
	     *  @see WP_Widget::update
	     */
	    function update($new_instance, $old_instance) {
			$instance = $new_instance;
	        return $instance;
	    }

	    /**
	     * Create the admin form, for adding the Widget to the blog.
	     *
	     *  @see WP_Widget::form
	     */
	    function form($instance) {
			$socialConfigurationUrl=site_url();
	    	$socialConfigurationUrl .= "/wp-admin/admin.php?page=ihf-social-page";
	    	echo("<a href='" . $socialConfigurationUrl . "'>Configure Social Links</a>");	    	
	    }
	} // class iHomefinderSocialWidget
}//end if( !class_exists('iHomefinderSocialWidget'))

?>
