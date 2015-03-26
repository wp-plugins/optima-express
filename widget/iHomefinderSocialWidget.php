<?php

/**
 * iHomefinderSocialWidget Class
 */
class iHomefinderSocialWidget extends WP_Widget {
	
	public function __construct() {
		$options=array('description'=>'Displays an social links.');
		parent::WP_Widget(false,
						   $name = 'IDX: Social',
						   $widget_options=$options);
	}

	/**
	 * Used to create the widget for display in the blog.
	 *
	 * @see WP_Widget::widget
	 */
	public function widget($args, $instance) {
		
		$facebookUrl= get_option(iHomefinderConstants::FACEBOOK_URL_OPTION);
		$twitterUrl=  get_option(iHomefinderConstants::TWITTER_URL_OPTION);
		$linkedinUrl= get_option(iHomefinderConstants::LINKEDIN_URL_OPTION);
		
		$baseUrl=plugins_url("/optima-express");
		
		//sets vars like $before_widget from $args
		extract($args);
			
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
	public function update($new_instance, $old_instance) {
		$instance = $new_instance;
		return $instance;
	}

	/**
	 * Create the admin form, for adding the Widget to the blog.
	 *
	 *  @see WP_Widget::form
	 */
	public function form($instance) {
		$socialConfigurationUrl=site_url();
		$socialConfigurationUrl .= "/wp-admin/admin.php?page=ihf-social-page";
		echo("<a href='" . $socialConfigurationUrl . "'>Configure Social Links</a>");	    	
	}
}
