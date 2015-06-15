<?php

class iHomefinderSocialWidget extends WP_Widget {
	
	public function __construct() {
		parent::__construct(
			"iHomefinderSocialWidget",
			"IDX: Social",
			array(
				"description" => "Displays an social links."
			)
		);
	}
	
	public function widget($args, $instance) {
		
		$facebookUrl = get_option(iHomefinderConstants::FACEBOOK_URL_OPTION);
		$twitterUrl =  get_option(iHomefinderConstants::TWITTER_URL_OPTION);
		$linkedinUrl = get_option(iHomefinderConstants::LINKEDIN_URL_OPTION);
		
		$baseUrl = plugins_url("/optima-express");
		
		//sets vars like $before_widget from $args
		extract($args);
			
		echo $before_widget;
		
		?>
		<div id="social-icons">
			<?php if(!empty($facebookUrl)) { ?>
				<a href="https://www.facebook.com/<?php echo $facebookUrl ?>" target="_blank">
					<img src="<?php echo $baseUrl ?>/images/fbicon.png" style="width: 24px; height: 24px;" />
				</a>
			<?php } ?>
			<?php if(!empty($twitterUrl)) { ?>
				<a href="https://twitter.com/<?php echo $twitterUrl ?>" target="_blank">
					<img src="<?php echo $baseUrl ?>/images/twittericon.png" style="width:24px; height: 24px;" />
				</a>
			<?php } ?>
			<?php if(!empty($linkedinUrl)) { ?>
				<a href="https://www.linkedin.com/<?php echo $linkedinUrl ?>" target="_blank">
					<img src="<?php echo $baseUrl ?>/images/linkedinicon.png" style="width:24px; height: 24px;" />
				</a>
			<?php } ?>
		</div>
		<?php
		echo $after_widget;	    	
		
	}
	
	public function update($new_instance, $old_instance) {
		$instance = $new_instance;
		return $instance;
	}
	
	public function form($instance) {
		$configurationUrl = admin_url("admin.php?page=" . iHomefinderConstants::SOCIAL_PAGE);
		?>
		<p>
			<a href="<?php echo $configurationUrl ?>">Configure Social Links</a>
		</p>
		<?php
	}
	
}
