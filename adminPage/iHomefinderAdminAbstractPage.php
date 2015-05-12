<?php

abstract class iHomefinderAdminAbstractPage implements iHomefinderAdminPageInterface {
	
	protected $admin;
	
	protected function __construct() {
		$this->admin = iHomefinderAdmin::getInstance();
	}
	
	public function getPage() {
		if(!current_user_can("manage_options")) {
			wp_die("You do not have sufficient permissions to access this page.");
		}
		if($this->isUpdated()) {
			$this->admin->updateAuthenticationToken();
		}
		?>
		<style type="text/css">
			select.regular-text {
				width: 25em;
			}
			.form-table.condensed td,
			.form-table.condensed th {
				padding: 10px 0px 10px 0px;
			}
			.button-large-ihf {
				height: 54px !important;
				text-align: center;
				font: 14px arial !important;
				padding-top: 10px !important;
				margin-right: 15px !important;
			}
		</style>
		<div class="wrap">
			<?php
			$this->getContent();
			?>
		</div>
		<?php
	}
	
	protected function getContent() {
		?>
		nothing
		<?php
	}
	
	//Check if an options form has been updated.
	//When new options are updated, the parameter "updated" is set to true
	public function isUpdated() {
		$isUpdated = (array_key_exists("settings-updated", $_REQUEST) && $_REQUEST["settings-updated"] === "true");
		return $isUpdated;
	}
	
	protected function showErrorMessages($errors) {
		if($this->hasErrors($errors)) {
			?>
			<div class="error">
				<?php foreach($errors as $error) { ?>
					<p>
						<?php echo $error; ?>
					</p>
				<?php } ?>
			</div>
			<?php
		}
	}
	
	protected function hasErrors($errors) {
		$hasErrors = $errors !== null && count($errors) > 0;
		return $hasErrors;
	}
	
}