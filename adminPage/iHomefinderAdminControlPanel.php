<?php

class iHomefinderAdminControlPanel extends iHomefinderAdminAbstractPage {
	
	private static $instance;
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	protected function getContent() {
		if(get_option(iHomefinderConstants::AUTHENTICATION_TOKEN_OPTION) != "") {
			?>
			<h2>Your IDX Control Panel will open in a new window.</h2>
			<p>If a new window does not open, please enable pop-ups for this site.</p>
			<script type="text/javascript">
				window.open("<?php echo iHomefinderConstants::CONTROL_PANEL_EXTERNAL_URL; ?>/z.cfm?w=<?php echo get_option(iHomefinderConstants::ACTIVATION_TOKEN_OPTION) ?>");
			</script>
			<?php
		}
	}
	
}