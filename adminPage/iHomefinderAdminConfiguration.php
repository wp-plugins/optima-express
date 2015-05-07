<?php

class iHomefinderAdminConfiguration extends iHomefinderAdminAbstractPage {
	
	private static $instance;
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	protected function getContent() {
		?>
		<?php $responsive = iHomefinderLayoutManager::getInstance()->isResponsive(); ?>
		<h2>Configuration</h2>
		<form method="post" action="options.php">
			<?php settings_fields(iHomefinderConstants::OPTION_CONFIG_PAGE); ?>
			<table class="form-table">
				<?php if(!iHomefinderPermissions::getInstance()->isOmnipressSite()) { ?>
					<tr>
						<th>
							<label for="<?php echo iHomefinderConstants::OPTION_LAYOUT_TYPE; ?>">Layout Style</label>
						</th>
						<td>
							<select id="<?php echo iHomefinderConstants::OPTION_LAYOUT_TYPE; ?>" name="<?php echo iHomefinderConstants::OPTION_LAYOUT_TYPE; ?>">
								<option value="<?php echo iHomefinderConstants::OPTION_LAYOUT_TYPE_RESPONSIVE; ?>" <?php if($responsive) { ?>selected<?php } ?>>Responsive</option>
								<option value="<?php echo iHomefinderConstants::OPTION_LAYOUT_TYPE_LEGACY; ?>" <?php if(!$responsive) { ?>selected<?php } ?>>Fixed-width</option>
							</select>
						</td>
					</tr>
				<?php } else { ?>
					<input type="hidden" name="<?php echo iHomefinderConstants::OPTION_LAYOUT_TYPE; ?>" value="<?php echo get_option(iHomefinderConstants::OPTION_LAYOUT_TYPE); ?>" />
				<?php } ?>
				<?php if(iHomefinderLayoutManager::getInstance()->supportsColorScheme()) { ?>
					<tr>
						<th>
							<label for="<?php echo iHomefinderConstants::COLOR_SCHEME_OPTION; ?>">Button Color</label>
						</th>
						<td>
							<?php $colorScheme = get_option(iHomefinderConstants::COLOR_SCHEME_OPTION) ?>
							<select id="<?php echo iHomefinderConstants::COLOR_SCHEME_OPTION; ?>" name="<?php echo iHomefinderConstants::COLOR_SCHEME_OPTION ?>">
								<option value="gray" <?php if($colorScheme == "gray") { ?>selected<?php } ?>>Gray</option>
								<option value="red" <?php if($colorScheme == "red") { ?>selected<?php } ?>>Red</option>
								<option value="green" <?php if($colorScheme == "green") { ?>selected<?php } ?>>Green</option>
								<option value="orange" <?php if($colorScheme == "orange") { ?>selected<?php } ?>>Orange</option>
								<option value="blue" <?php if($colorScheme == "blue") { ?>selected<?php } ?>>Blue</option>
								<option value="light_blue" <?php if($colorScheme == "light_blue") { ?>selected<?php } ?>>Light Blue</option>
								<option value="blue_gradient" <?php if($colorScheme == "blue_gradient") { ?>selected<?php } ?>>Blue Gradient</option>
							</select>
						</td>
					</tr>
				<?php } ?>
				<tr>
					<th>
						<label for="<?php echo iHomefinderConstants::CSS_OVERRIDE_OPTION; ?>">CSS Override</label>
					</th>
					<td>
						<p>To redefine an Optima Express style, paste the edited style below.</p>
						<textarea id="<?php echo iHomefinderConstants::CSS_OVERRIDE_OPTION; ?>" name="<?php echo iHomefinderConstants::CSS_OVERRIDE_OPTION ?>" style="width: 100%; height: 300px; "><?php echo get_option(iHomefinderConstants::CSS_OVERRIDE_OPTION, null); ?></textarea>
					</td>
				</tr>
			</table>
			<p class="submit">
				<button type="submit" class="button-primary">Save Changes</button>
			</p>
		</form>
		<?php
	}
	
}