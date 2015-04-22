<?php

class iHomefinderAgentBioWidget extends WP_Widget {

	const STANDARD_DISPLAY_TYPE = "standard";
	const NARROW_DISPLAY_TYPE = "narrow";
	
	public function __construct() {
		$options = array("description" => "Displays an agent bio.");
		parent::WP_Widget(false, $name = "IDX: Agent Bio", $widget_options = $options);
	}
	
	public function widget($args, $instance) {
		
		//sets vars like $before_widget from $args
		extract($args);
		
		$displayType = $instance["displayType"];

		$agentPhotoUrl = get_option(iHomefinderConstants::AGENT_PHOTO_OPTION);
		$agentText = get_option(iHomefinderConstants::AGENT_TEXT_OPTION);
		$displayTitle = get_option(iHomefinderConstants::AGENT_DISPLAY_TITLE_OPTION);
		$contactPhone = get_option(iHomefinderConstants::CONTACT_PHONE_OPTION);
		$contactEmail = get_option(iHomefinderConstants::CONTACT_EMAIL_OPTION);
		$agentDesignations = get_option(iHomefinderConstants::AGENT_DESIGNATIONS_OPTION);
		$agentLicenseInfo = get_option(iHomefinderConstants::AGENT_LICENSE_INFO_OPTION);
		
		echo $before_widget;
		
		if(!empty($displayTitle)) {
			echo $before_title . $displayTitle . $after_title;
		} 
		
		?>
		<table>
			<tr>
				<?php if(!empty($agentPhotoUrl)) { ?>
					<td style="vertical-align: top;">
						<img src="<?php echo $agentPhotoUrl ?>" alt="<?php echo $displayTitle ?>" width="90" hspace="0" style="width: 90px; margin-top: 3px; margin-right: 10px;" id="ihf-bio-img" />
					</td>
				<?php } ?>
				<?php if($displayType == iHomefinderAgentBioWidget::NARROW_DISPLAY_TYPE) { ?>
					</tr><tr>
				<?php } ?>
				<td>
					<div class="ihf-about-info" style="font-size: 12px; line-height: 1.5em;">
						<?php if(!empty($agentText)) { ?>
							<?php echo $agentText ?>
							<br />
							<br />				
						<?php } ?>
						<?php if(!empty($contactPhone)) { ?>
							<?php echo $contactPhone ?>
							<br />
						<?php } ?>
						<?php if(!empty($contactEmail)) { ?>
							<?php echo $contactEmail ?>
							<br />
						<?php } ?>
						<?php if(!empty($agentDesignations)) { ?>
							<?php echo $agentDesignations ?>
							<br />
						<?php } ?>
						<?php if(!empty($agentLicenseInfo)) { ?>
							<?php echo $agentLicenseInfo ?>
							<br />
						<?php } ?>
					</div>
				</td>
			</tr>
		</table>
		<?php
		echo $after_widget;
	}
	
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance["displayType"] = $new_instance["displayType"];
		return $instance;
	}
  
	public function form($instance) {
		$displayType = esc_attr($instance["displayType"]);
		?>
		<p>
			<label>
				Display Type:
				<select class="widefat" name="<?php echo $this->get_field_name("displayType"); ?>">
					<option value="<?php echo self::STANDARD_DISPLAY_TYPE ?>">Standard</option>
					<option value="<?php echo self::NARROW_DISPLAY_TYPE ?>" <?php if($displayType == self::NARROW_DISPLAY_TYPE) {echo "selected";} ?>>Narrow</option>
				</select>
			</label>
		</p>
		<?php 
		$bioConfigurationUrl = site_url();
		$bioConfigurationUrl .= "/wp-admin/admin.php?page=ihf-bio-page";
		?>
		<p>
			<a href="<?php echo $bioConfigurationUrl ?>">Configure Bio</a>
		</p>
		<?php
	}
  
}