<?php

class iHomefinderAdminActivate extends iHomefinderAdminAbstractPage {
	
	const IHOMEFINDER_NOTIFICATION = "By registering this plugin you consent to allow downloads of IDX listings that include images, attribution of iHomefinder as the IDX provider and other MLS-specified compliance requirements.";
	
	private static $instance;
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function registerSettings() {
		register_setting(iHomefinderConstants::OPTION_GROUP_ACTIVATE, iHomefinderConstants::ACTIVATION_TOKEN_OPTION);
		register_setting(iHomefinderConstants::OPTION_GROUP_ACTIVATE, iHomefinderConstants::AUTHENTICATION_TOKEN_OPTION);
	}
	
	protected function getContent() {
		$section = iHomefinderUtility::getInstance()->getRequestVar("section");
		//if the activationToken is passed in the url, we manually update the option
		$activationToken = iHomefinderUtility::getInstance()->getRequestVar("reg");
		if(!empty($activationToken)) {
			$this->admin->activateAuthenticationToken($activationToken);
			?>
			<h2>Thanks For Signing Up</h2>
			<div class="updated">
				<p>Your Optima Express plugin has been registered.</p>
			</div>
			<p>You will receive an email from us with IDX paperwork for your MLS. Please complete the paperwork and return it to iHomefinder promptly. Listings from your MLS will appear in Optima Express as soon as your MLS approves your IDX paperwork.</p>
		<?php } elseif($section === "enter-reg-key") { ?>
			<?php $activationToken = get_option(iHomefinderConstants::ACTIVATION_TOKEN_OPTION, null); ?>
			<h2>Add Registration Key</h2>
			<?php if(empty($activationToken)) { ?>
				<div class="error">
					<p>Add your Registration Key and click "Save Changes" to get started with Optima Express.</p>
				</div>
			<?php } elseif($this->admin->isActivated()) { ?>
				<div class="updated">
					<p>Your Optima Express plugin has been registered.</p>
				</div>
			<?php } else { ?>
				<div class="error">
					<p>Incorrect Registration Key.</p>
				</div>
			<?php } ?>
			<form method="post" action="options.php">
				<?php settings_fields(iHomefinderConstants::OPTION_GROUP_ACTIVATE); ?>
				<table class="form-table">
					<tr>
						<th>
							<label for="<?php echo iHomefinderConstants::ACTIVATION_TOKEN_OPTION; ?>">Registration Key</label>
						</th>
						<td>
							<input id="<?php echo iHomefinderConstants::ACTIVATION_TOKEN_OPTION; ?>" class="regular-text" type="text" name="<?php echo iHomefinderConstants::ACTIVATION_TOKEN_OPTION; ?>" value="<?php echo get_option(iHomefinderConstants::ACTIVATION_TOKEN_OPTION, null); ?>" required="required" />
						</td>
					</tr>
				</table>
				<p>
					<?php echo self::IHOMEFINDER_NOTIFICATION; ?>
				</p>
				<p class="submit">
					<button type="submit" class="button-primary">Save Changes</button>
				</p>
			</form>
		<?php } elseif($section === "free-trial") { ?>
			<h2>Free Trial Sign-Up</h2>
			<p>
				<?php echo self::IHOMEFINDER_NOTIFICATION; ?>
			</p>
			<?php
			$firstName = iHomefinderUtility::getInstance()->getRequestVar("firstName");
			$lastName = iHomefinderUtility::getInstance()->getRequestVar("lastName");
			$phoneNumber = iHomefinderUtility::getInstance()->getRequestVar("phoneNumber");
			$email = iHomefinderUtility::getInstance()->getRequestVar("email");
			$accountType = iHomefinderUtility::getInstance()->getRequestVar("accountType");
			$errors = array();
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$errors[] = "Email address is not valid.";
			}				
			if(empty($accountType)) {
				$errors[] = "Select type of trial account.";
			}
			if(count($errors) === 0) {
				if($accountType === "broker") {
					$companyname = "Many Homes Realty";
				} else {
					$companyname = "Jamie Agent";
				}
				$password = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"), 0, 6);
				$params = array(
					"plugin" => "true",
					"clientfirstname" => $firstName,
					"clientlastname" => $lastName,
					"companyname" => $companyname,
					"companyemail" => $email,
					"password" => $password,
					"companyphone" => $phoneNumber,
					"companyaddress" => "123 Main St.",
					"companycity" => "Anytown",
					"companystate" => "CA",
					"companyzip" => "12345",
					"account_type" => $accountType,
					"product" => "Optima Express",
					"lead_source" => "Plugin",
					"lead_source_description" => "Optima Express Trial Form",
					"ad_code" => "",
					"ip_address" => $_SERVER["REMOTE_ADDR"],
				);
				$requestUrl = "http://www.ihomefinder.com/store/optima-express-trial.php";
				set_time_limit(90);
				$requestArgs = array(
					"timeout" => "90",
					"body" => $params
				);
				$response = wp_remote_post($requestUrl, $requestArgs);
				if(!is_wp_error($response)) {
					$responseBody = wp_remote_retrieve_body($response);
					$responseBody = json_decode($responseBody, true);
					$clientId = $responseBody["clientID"];
					$activationToken = $responseBody["regKey"];
					$this->admin->activateAuthenticationToken($activationToken);
					?>
					<div class="updated">
						<p>Your Optima Express plugin has been registered.</p>
					</div>
					<p>Thank you for evaluating Optima Express!</p>
					<p>Your trial account uses sample listing data from Northern California. For search and listings in your MLS, <a href="http://www.ihomefinder.com/store/convert.php?cid=<?php echo $clientId ?>" target="_blank">upgrade to a paid account</a>.</p>
					<p>Visit our <a href="http://support.ihomefinder.com/index.php?/Knowledgebase/List/Index/23/optima-express-responsive/" target="_blank">knowledge base</a> for assistance setting up IDX on your site.</p>
					<p>Don't hesitate to <a href="http://www.ihomefinder.com/contact-us/" target="_blank">contact us</a> if you have any questions.</p>
				<?php } else { ?>
					<div class="error">
						<p>Error creating your account.</p>
					</div>
				<?php } ?>
			<?php
			} else {
				if($_POST) {
					$this->showErrorMessages($errors);
				}
				?>
				<form method="post">
					<table class="form-table">
						<tr>
							<th>
								<label for="firstName">First Name</label>
							</th>
							<td>
								<input id="firstName" class="regular-text" name="firstName" type="text" required="required" value="<?php echo $firstName ?>" />
							</td>
						</tr>
						<tr>
							<th>
								<label for="lastName">Last Name</label>
							</th>
							<td>
								<input id="lastName" class="regular-text" name="lastName" type="text" required="required" value="<?php echo $lastName ?>" />
							</td>
						</tr>
						<tr>
							<th>
								<label for="phoneNumber">Phone Number</label>
							</th>
							<td>
								<input id="phoneNumber" class="regular-text" name="phoneNumber" type="text" required="required" value="<?php echo $phoneNumber ?>" />
							</td>
						</tr>
						<tr>
							<th>
								<label for="email">Email</label>
							</th>
							<td>
								<input id="email" class="regular-text" name="email" type="email" required="required" placeholder="Your email will be your username" value="<?php echo $email ?>" />
							</td>
						</tr>
						<tr>
							<th>
								<label for="accountType">Account Type</label>
							</th>
							<td>
								<select id="accountType" name="accountType">
									<option>Select One</option>
									<option value="agent" <?php if($accountType === "agent") { ?> selected="selected" <?php } ?>>Individual Agent</option>
									<option value="broker" <?php if($accountType === "broker") { ?> selected="selected" <?php } ?>>Office with Multiple Agents</option>
								</select>
							</td>
						</tr>
					</table>
					<p class="submit">
						<button type="submit" class="button-primary">Start Trial</button>
						<span>&nbsp;&nbsp;&nbsp;Creating your trial account can take up to 60 seconds to complete. Please do not refresh the page or press the back button.</span>
					</p>
				</form>
			<?php } ?>
		<?php } else { ?>
			<?php if(!$this->admin->isActivated()) { ?>
				<h2>Register Optima Express</h2>
				<p>
					<a href="<?php echo admin_url("admin.php?page=" . iHomefinderConstants::PAGE_ACTIVATE . "&section=enter-reg-key"); ?>">I already have a registration key</a>
				</p>
				<p>
					<a href="<?php echo admin_url("admin.php?page=" . iHomefinderConstants::PAGE_ACTIVATE . "&section=free-trial"); ?>" class="button button-primary ihf-button-large" >Get a Free<br />30-Day Trial</a>
					<a href="http://www.ihomefinder.com/products/optima-express/optima-express-agent-pricing/?plugin=true&redirectURL=<?php echo urlencode(admin_url("admin.php?page=" . iHomefinderConstants::PAGE_ACTIVATE)); ?>" class="button button-primary ihf-button-large">Sign Up for a<br />Paid Account</a>
				</p>
				<p>Optima Express from iHomefinder adds MLS/IDX search and listings directly into your WordPress site.</p>
				<p>A free trial account uses sample IDX listings from Northern California.</p>
				<p>Signing up for a paid account provides access to listings in your MLS&reg; System and full support from iHomefinder. Plans start at $39.95 per month. You must be a member of an MLS to qualify for IDX service. <a target="_blank" href="http://www.ihomefinder.com/mls-coverage/">Learn More</a></p>
				<p>
					<?php echo self::IHOMEFINDER_NOTIFICATION; ?>
				</p>
			<?php } else { ?>
				<h2>Unregister Optima Express</h2>
				<p>Optima Express is currently registered. Clicking the below button will unregister the IDX plugin.<p>
				<form method="post" action="options.php">
					<?php settings_fields(iHomefinderConstants::OPTION_GROUP_ACTIVATE); ?>
					<input type="hidden" name="<?php echo iHomefinderConstants::ACTIVATION_TOKEN_OPTION ?>" value="" />
					<input type="hidden" name="<?php echo iHomefinderConstants::AUTHENTICATION_TOKEN_OPTION ?>" value="" />
					<p class="submit">
						<button type="submit" class="button-primary" onclick="return confirm('Are you sure you want to unregister Optima Express?');">Unregister</button>
					</p>
				</form>
				<form method="post" action="options.php" name="refreshRegistration">
					<?php settings_fields(iHomefinderConstants::OPTION_GROUP_ACTIVATE); ?>
					<input type="hidden" name="<?php echo iHomefinderConstants::ACTIVATION_TOKEN_OPTION; ?>" value="<?php echo get_option(iHomefinderConstants::ACTIVATION_TOKEN_OPTION, null); ?>" />
					<a href="#" onclick="document.refreshRegistration.submit();">Refresh Registration</a>
				</form>
			<?php } ?>
			<?php
		}
	}
	
}