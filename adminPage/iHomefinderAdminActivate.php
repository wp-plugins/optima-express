<?php

class iHomefinderAdminActivate extends iHomefinderAdminAbstractPage {
	
	private $ihomefinderNotification = "By registering this plugin you consent to allow downloads of IDX listings that include images, attribution of iHomefinder as the IDX provider and other MLS-specified compliance requirements.";
	
	private static $instance;
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	protected function getContent() {
		
		$activationToken = null;
		if(array_key_exists("reg", $_REQUEST)) {
			$activationToken = $_REQUEST["reg"];
		}
		
		if($activationToken) {
			update_option(iHomefinderConstants::ACTIVATION_TOKEN_OPTION, $activationToken);
			$this->admin->updateAuthenticationToken();
			?>
			<h2>Thanks For Signing Up</h2>
			<div class="updated">
				<p>Your Optima Express plugin has been registered.</p>
			</div>
			<p>You will receive an email from us with IDX paperwork for your MLS. Please complete the paperwork and return it to iHomefinder promptly. Listings from your MLS will appear in Optima Express as soon as your MLS approves your IDX paperwork.</p>
			<?php

		} elseif($this->isUpdated()) {
			//call function here to pass the activation key to ihf and get
			//an authentication token
			$this->admin->updateAuthenticationToken();
		}
		
		$section = null;
		if(array_key_exists("section", $_REQUEST)) {
			$section = $_REQUEST["section"];
		}
		
		if($section === "enter-reg-key") {
			?>
			<h2>Add Registration Key</h2>
			<?php
			if(get_option(iHomefinderConstants::ACTIVATION_TOKEN_OPTION) == "") {
				?>
				<div class="error">
					<p>Add your Registration Key and click "Save Changes" to get started with Optima Express.</p>
				</div>
				<?php
			} elseif(get_option(iHomefinderConstants::AUTHENTICATION_TOKEN_OPTION) != "") {
				?>
				<div class="updated">
					<p>Your Optima Express plugin has been registered.</p>
				</div>
				<?php
			} else {
				?>
				<div class="error">
					<p>Incorrect Registration Key.</p>
				</div>
				<?php
			}
			?>
			<form method="post" action="options.php">
				<?php settings_fields(iHomefinderConstants::OPTION_ACTIVATE); ?>
				<table class="form-table">
					<tr valign="top">
						<th>
							<strong>Registration Key:</strong>
						</th>
						<td>
							<input class="regular-text" type="text" name="<?php echo iHomefinderConstants::ACTIVATION_TOKEN_OPTION; ?>" value="<?php echo get_option(iHomefinderConstants::ACTIVATION_TOKEN_OPTION); ?>" />
						</td>
					</tr>
				</table>
				<p>
					<?php echo $this->ihomefinderNotification; ?>
				</p>
				<p class="submit">
					<button type="submit" class="button-primary">Save Changes</button>
				</p>
			</form>
		<?php
		} elseif($section === "free-trial") {
			?>
			<h2>Free Trial Sign-Up</h2>
			<p>
				<?php echo $this->ihomefinderNotification; ?>
			</p>
			<?php
			
			$firstName = $_POST["firstName"];
			$lastName = $_POST["lastName"];
			$phoneNumber = $_POST["phoneNumber"];
			$email = $_POST["email"];
			$accountType = $_POST["accountType"];
			
			$errors = array();
			
			if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$errors[] = "Email address is not valid.";
			}
							
			if(empty($accountType)) {
				$errors[] = "Select type of trial account.";
			}
			
			if(count($errors) == 0) {
				
				if($accountType == "Broker") {
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
				$requestArgs = array("timeout" => "90", "body" => $params);
				$response = wp_remote_post($requestUrl, $requestArgs);
				if(!is_wp_error($response)) {
					$responseBody = wp_remote_retrieve_body($response);
					$responseBody = json_decode($responseBody, true);
					
					$clientId = $responseBody["clientID"];
					$regKey = $responseBody["regKey"];
					$username = $responseBody["username"];
					$password = $responseBody["password"];
					
					update_option(iHomefinderConstants::ACTIVATION_TOKEN_OPTION, $regKey);
					$this->admin->updateAuthenticationToken();
					
					?>
					<div class="updated">
						<p>Your Optima Express plugin has been registered.</p>
					</div>
					<p>Thank you for evaluating Optima Express!</p>
					<p>Your trial account uses sample listing data from Northern California. For search and listings in your MLS, <a href="http://www.ihomefinder.com/store/convert.php?cid=<?php echo $clientId ?>" target="_blank">upgrade to a paid account</a>.</p>
					<p>Visit our <a href="http://support.ihomefinder.com/index.php?/Knowledgebase/List/Index/23/optima-express-responsive/" target="_blank">knowledge base</a> for assistance setting up IDX on your site.</p>
					<p>Don't hesitate to <a href="http://www.ihomefinder.com/contact-us/" target="_blank">contact us</a> if you have any questions.</p>
					
					<?php
					
				} else {
					?>
					<div class="error">
						<p>Error creating your account.</p>
					</div>
					<?php
				}
				
			} else {
			
				if($_POST) {
					$this->showErrorMessages($errors);
				}
				
				?>
				<style type="text/css">
					table {
						width: 300px;
					}
					tr td:nth-child(1) {
						width: 150px;
					}
					input,
					select {
						display: block;
						width: 250px;
					}
					label {
						font-weight: bold;
					}
				</style>
				<form method="post">
					<table class="form-table">
						<tr>
							<td>
								<label for="email">First Name:</label>
							</td>
							<td>
								<input id="email" name="firstName" type="text" required="required" value="<?php echo $firstName ?>" />
							</td>
						</tr>
						<tr>
							<td>
								<label for="email">Last Name:</label>
							</td>
							<td>
								<input id="email" name="lastName" type="text" required="required" value="<?php echo $lastName ?>" />
							</td>
						</tr>
						<tr>
							<td>
								<label for="email">Phone Number:</label>
							</td>
							<td>
								<input id="email" name="phoneNumber" type="text" required="required" value="<?php echo $phoneNumber ?>" />
							</td>
						</tr>
						<tr>
							<td>
								<label for="email">Email:</label>
							</td>
							<td>
								<input id="email" name="email" type="email" required="required" placeholder="Your email will be your username" value="<?php echo $email ?>" />
							</td>
						</tr>
						<tr>
							<td>
								<label>Account Type:</label>
							</td>
							<td>
								<?php
								if($accountType == "Agent") {
									$agentSelected = "selected=\"selected\"";
								}
								if($accountType == "Broker") {
									$brokerSelected = "selected=\"selected\"";
								}									
								?>
								<select name="accountType">
									<option>Select One</option>
									<option value="Agent" <?php echo $agentSelected ?>>Individual Agent</option>
									<option value="Broker" <?php echo $brokerSelected ?>>Office with Multiple Agents</option>
								</select>
							</td>
						</tr>
					</table>
					<p class="submit">
						<button type="submit" class="button-primary">Start Trial</button>
						<span>&nbsp;&nbsp;&nbsp;Creating your trial account can take up to 60 seconds to complete. Please do not refresh the page or press the back button.</span>
					</p>
				</form>
				<?php
			
			}
			
		} else {
			$authenticationToken = get_option(iHomefinderConstants::AUTHENTICATION_TOKEN_OPTION);
			if(empty($authenticationToken)) {
				?>
				<style type="text/css">
					.button-large-ihf {
						height: 54px !important;
						text-align: center;
						font: 14px arial !important;
						padding-top: 10px !important;
						margin-right: 15px !important;
					}
				</style>
				<h2>Register Optima Express</h2>
				<br />
				<a href="admin.php?page=<?php echo iHomefinderConstants::OPTION_ACTIVATE ?>&section=enter-reg-key">I already have a registration key</a>
				<br />
				<br />
				<a href="admin.php?page=<?php echo iHomefinderConstants::OPTION_ACTIVATE ?>&section=free-trial" class="button button-primary button-large-ihf" >Get a Free<br />30-Day Trial</a>
				<a href="http://www.ihomefinder.com/products/optima-express/optima-express-agent-pricing/?plugin=true&redirectURL=<?php echo urlencode ('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" class="button button-primary button-large-ihf">Sign Up for a<br />Paid Account</a>
				<br />
				<br />
				<p>Optima Express from iHomefinder adds MLS/IDX search and listings directly into your WordPress site.</p>
				<p>A free trial account uses sample IDX listings from Northern California.</p>
				<p>Signing up for a paid account provides access to listings in your MLS&reg; System and full support from iHomefinder. Plans start at $39.95 per month. You must be a member of an MLS to qualify for IDX service. <a target="_blank" href="http://www.ihomefinder.com/mls-coverage/">Learn More</a></p>
				<p>
					<?php echo $this->ihomefinderNotification; ?>
				</p>
				<?php
			} elseif($activationToken == false) {
				?>
				<h2>Unregister Optima Express</h2>
				<p>Optima Express is currently registered. Clicking the below button will unregister the IDX plugin.<p>
				<form method="post" action="options.php">
					<?php settings_fields(iHomefinderConstants::OPTION_ACTIVATE); ?>
					<input type="hidden" name="<?php echo iHomefinderConstants::ACTIVATION_TOKEN_OPTION ?>" value="" />
					<input type="hidden" name="<?php echo iHomefinderConstants::AUTHENTICATION_TOKEN_OPTION ?>" value="" />
					<p class="submit">
						<button type="submit" class="button-primary" onclick="return confirm('Are you sure you want to unregister Optima Express?');">Unregister</button>
					</p>
				</form>
				<form method="post" action="options.php" name="refreshRegistration">
					<?php settings_fields(iHomefinderConstants::OPTION_ACTIVATE); ?>
					<input type="hidden" name="<?php echo iHomefinderConstants::ACTIVATION_TOKEN_OPTION ?>" value="<?php echo get_option(iHomefinderConstants::ACTIVATION_TOKEN_OPTION); ?>" />
					<a href="#" onclick="document.refreshRegistration.submit();">Refresh Registration</a>
				</form>
				<?php
				
			}
		}
	}
	
}