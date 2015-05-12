<?php

class iHomefinderAdminSocial extends iHomefinderAdminAbstractPage {
	
	private static $instance;
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	protected function getContent() {
		?>
		<h2>Social Widget Setup</h2>
		<p>Enter your social media addresses for the Optima Express Social Media Widget.</p>
		<form method="post" action="options.php">
			<?php settings_fields(iHomefinderConstants::OPTION_GROUP_SOCIAL); ?>
			<table class="form-table">
				<tbody>
					<tr>
						<th style="padding-bottom: 0px;">
							<h3 style="margin: 0px">Facebook</h3>
						</th>
					</tr>
					<tr>
						<th>
							<label for="<?php echo iHomefinderConstants::FACEBOOK_URL_OPTION ?>">http://www.facebook.com/</label>
						</th>
						<td>
							<input id="<?php echo iHomefinderConstants::FACEBOOK_URL_OPTION ?>" class="regular-text" type="text" name="<?php echo iHomefinderConstants::FACEBOOK_URL_OPTION ?>" value="<?php echo get_option(iHomefinderConstants::FACEBOOK_URL_OPTION) ?>" />
						</td>
					</tr>
					<tr>
						<th style="padding-bottom: 0px;">
							<h3 style="margin: 0px">LinkedIn</h3>
						</th>
					</tr>
					<tr>
						<th>
							<label for="<?php echo iHomefinderConstants::LINKEDIN_URL_OPTION ?>">http://www.linkedin.com/</label>
						</th>
						<td>
							<input id="<?php echo iHomefinderConstants::LINKEDIN_URL_OPTION ?>" class="regular-text" type="text" name="<?php echo iHomefinderConstants::LINKEDIN_URL_OPTION ?>" value="<?php echo get_option(iHomefinderConstants::LINKEDIN_URL_OPTION) ?>" />
						</td>
					</tr>
					<tr>
						<th style="padding-bottom: 0px;">
							<h3 style="margin: 0px">Twitter</h3>
						</th>
					</tr>
					<tr>
						<th>
							<label for="<?php echo iHomefinderConstants::TWITTER_URL_OPTION ?>">http://www.twitter.com/</label>
						</th>
						<td>
							<input id="<?php echo iHomefinderConstants::TWITTER_URL_OPTION ?>" class="regular-text" type="text" name="<?php echo iHomefinderConstants::TWITTER_URL_OPTION ?>" value="<?php echo get_option(iHomefinderConstants::TWITTER_URL_OPTION) ?>" />
						</td>
					</tr>
				</tbody>
			</table>
			<p class="submit">
				<button type="submit" class="button-primary">Save Changes</button>
			</p>
		</form>
		<?php
	}
	
}