<?php
if( !class_exists('iHomefinderAgentBioWidget')) {
	/**
	 * iHomefinderAgentBioWidget Class
	 */
	class iHomefinderAgentBioWidget extends WP_Widget {

		const STANDARD_DISPLAY_TYPE="standard";
		const NARROW_DISPLAY_TYPE="narrow";
		
		/** constructor */
	    function iHomefinderAgentBioWidget() {
	    	$options=array('description'=>'Displays an agent bio.');
	        parent::WP_Widget( false,
	                           $name = 'IDX: Agent Bio',
	                           $widget_options=$options );
	    }

	    /**
	     * Used to create the widget for display in the blog.
	     *
	     * @see WP_Widget::widget
	     */
	    function widget($args, $instance) {
	    	
	    	//sets vars like $before_widget from $args
	    	extract( $args );
	    	
	    	$displayType=$instance['displayType'];

	    	$agentPhotoUrl=get_option( IHomefinderConstants::AGENT_PHOTO_OPTION );
	    	$agentText=get_option( IHomefinderConstants::AGENT_TEXT_OPTION );
	    	$displayTitle=get_option( IHomefinderConstants::AGENT_DISPLAY_TITLE_OPTION );
	    	$contactPhone=get_option(IHomefinderConstants::CONTACT_PHONE_OPTION);
	    	$contactEmail=get_option(IHomefinderConstants::CONTACT_EMAIL_OPTION);
	    	$agentDesignations=get_option(IHomefinderConstants::AGENT_DESIGNATIONS_OPTION);
	    	$agentLicenseInfo=get_option(IHomefinderConstants::AGENT_LICENSE_INFO_OPTION);

	    	
			echo $before_widget;
			
			if(!empty( $displayTitle )) {
                echo $before_title . $displayTitle . $after_title;
			} 
	    	
		    // WIDGET CODE GOES HERE
			echo "<table><tr>" ;
			
			if( $agentPhotoUrl ){
				echo "<td style='vertical-align:top;'>";
				echo "<img src='" . $agentPhotoUrl .  "' alt='" . $displayTitle . "' " . "width='90' hspace='0' style='width:90px;margin-top:3px;margin-right:10px;' id='ihf-bio-img' />";
				echo "</td>";
			}
			
			if($displayType == iHomefinderAgentBioWidget::NARROW_DISPLAY_TYPE ){
				echo("</tr><tr>");
			}
			
			echo "<td><div class='ihf-about-info' style='font-size:12px;line-height:1.5em;'>" ;
			if( $agentText ){
		    	echo $agentText . "<br /><br />";				
			}
			if($contactPhone){
				echo $contactPhone . "<br />";	
			}
			if($contactEmail){
				echo $contactEmail . "<br />";	
			}
			if($agentDesignations){
				echo $agentDesignations . "<br />";
			}
			if($agentLicenseInfo){
				echo $agentLicenseInfo . "<br />";	
			}
			echo "</div></td></tr></table><p/>";
			
    		echo $after_widget;

	    }

	    /**
	     *  Processes form submission in the admin area for configuring
	     *  the widget.
	     *
	     *  @see WP_Widget::update
	     */
	  function update($new_instance, $old_instance){
 		$instance=$old_instance ;
 		$instance['displayType']=$new_instance['displayType'];
    	return $instance;
	  }



	  /**
	   * Create the admin form, for adding the Widget to the blog.
	   *
	   *  @see WP_Widget::form
	   */
	  function form($instance){
	  	
	 	$displayType = esc_attr($instance['displayType']);
	    ?>
	    
	    
	    	<p>
	        	<?php _e('Display Type:'); ?>
	        	<select name="<?php echo $this->get_field_name('displayType'); ?>">
	        		<option value="<?php echo(iHomefinderAgentBioWidget::STANDARD_DISPLAY_TYPE)?>">Standard</option>
	        		<option value="<?php echo(iHomefinderAgentBioWidget::NARROW_DISPLAY_TYPE)?>" <?php if( $displayType == iHomefinderAgentBioWidget::NARROW_DISPLAY_TYPE){echo(' selected=true ');}?>>Narrow</option>
	        	</select>
	        </p>
	    <?php 
    	$bioConfigurationUrl=site_url();
	    $bioConfigurationUrl .= "/wp-admin/admin.php?page=ihf-bio-page";
    	echo("<a href='" . $bioConfigurationUrl . "'>Configure Bio</a>");
  	  }
		} // class iHomefinderAgentBioWidget
}//end if( !class_exists('iHomefinderAgentBioWidget'))

?>
