<?php

class iHomefinderAdminCommunityPages extends iHomefinderAdminAbstractPage {
	
	private static $instance;
	
	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	protected function getContent() {
		$errors = null;
		//On Update, push the CSS_OVERRIDE_OPTION to iHomefinder
		if($this->isUpdated()) {
			//call function here to pass the activation key to ihf and update the CSS Override value
			$title = $_REQUEST["title"];
			$cityZip = $_REQUEST["cityZip"];
			$propertyType = $_REQUEST["propertyType"];
			$bed = $_REQUEST["bed"];
			$bath = $_REQUEST["bath"];
			$minPrice = $_REQUEST["minPrice"];
			$maxPrice = $_REQUEST["maxPrice"];
			$errors = $this->updateCommunityPages($title, $cityZip, $propertyType, $bed, $bath, $minPrice, $maxPrice);
		}
		?>
		<style type="text/css">
			.form-table td,
			.form-table th {
				padding: 10px 0px 10px 0px;
			}
		</style>
		<h2>Community Pages</h2>
		<?php $this->showErrorMessages($errors) ?>
		<div style="float:left; padding-right: 40px;">
			<h3>Create a new Community Page</h3>
			<div>Enter search criteria to create a new page under the Community Pages menu.</div>
			<form method="post">
				<input type="hidden" name="settings-updated" value="true" />
				<?php settings_fields(iHomefinderConstants::COMMUNITY_PAGES); ?>
				<table class="form-table">
					<tbody>
						<tr>
							<th>
								<label for="location">Location</label>
							</th>
							<td>
								<?php $this->createCityZipAutoComplete() ?>
							</td>
						</tr>
						<tr>
							<th>
								<label for="title">Page Title</label>
							</th>
							<td>
								<input class="regular-text" type="text" id="title" name="title" />
							</td>
						</tr>
						<tr>
							<th>
								<label for="propertyType">Property Type</label>
							</th>
							<td>
								<?php $this->createPropertyTypeSelect() ?>
							</td>
						</tr>
						<tr>
							<th>
								<label for="bed">Bed</label>
							</th>
							<td>
								<input id="bed" class="regular-text" type="text" name="bed" />
							</td>
						</tr>
						<tr>
							<th>
								<label for="bath">Bath</label>
							</th>
							<td>
								<input id="bath" class="regular-text" type="text" name="bath" />
							</td>
						</tr>
						<tr>
							<th>
								<label for="minPrice">Min Price</label>
							</th>
							<td>
								<input id="minPrice" class="regular-text" type="text" name="minPrice" />
							</td>
						</tr>
						<tr>
							<th>
								<label for="maxPrice">Max Price</label>
							</th>
							<td>
								<input id="maxPrice" class="regular-text" type="text" name="maxPrice" />
							</td>
						</tr>
					</tbody>
				</table>
				<p class="submit">
					<button type="submit" class="button-primary">Save</button>
				</p>
			</form>
		</div>
		<div style="float: left">
			<h3>Existing Community Pages</h3>
			<div style="padding-bottom: 9px;">Click the page name to edit Community Page content.</div>
			<div style="padding-bottom: 9px;">
				Change or edit the links that appear within the
				<a href="<?php echo site_url() ?>/wp-admin/nav-menus.php">Menus</a>
				section.
			</div>
			<?php $communityPageMenuItems = (array) iHomefinderMenu::getInstance()->getCommunityPagesMenuItems(); ?>
			<ul>
				<?php foreach($communityPageMenuItems as $key => $menu_item) { ?>
					<li>
						<a href="post.php?post=<?php echo $menu_item->object_id ?>&action=edit">
							<?php echo $menu_item->title; ?>
						</a>
					</li>
				<?php } ?>
			</ul>
		</div>
		<?php
	}
	
	private function updateCommunityPages($title, $cityZip, $propertyType, $bed, $bath, $minPrice, $maxPrice) {
		$errors = array();
		if($cityZip == null || $cityZip == "") {
			$errors[] = "Please select a location";
		}
		if($title == null || $title == "") {
			$errors[] = "Please enter a title";
		}
		if(empty($errors)) {
			$shortCode = iHomefinderShortcodeDispatcher::getInstance()->buildSearchResultsShortCode($cityZip, $propertyType, $bed, $bath, $minPrice, $maxPrice);
			$post = array(
				"comment_status" => "closed" ,// "closed" means no comments.
				"ping_status" => "closed", // "closed" means pingbacks or trackbacks turned off
				"post_content" => $shortCode, //The full text of the post.
				"post_name" => $title, // The name (slug) for your post
				"post_status" => "publish", //Set the status of the new post.
				"post_title" => $title, //The title of your post.
				"post_type" => "page" //You may want to insert a regular post, page, link, a menu item or some custom post type
			);
			$postId = wp_insert_post($post);
			iHomefinderMenu::getInstance()->addPageToCommunityPages($postId);
		}
		return $errors;
	}
	
	private function createCityZipAutoComplete() {
		$formData = iHomefinderSearchFormFieldsUtility::getInstance()->getFormData();
		$cityZipList = $formData->getCityZipList();
		$cityZipListJson = json_encode($cityZipList);
		wp_enqueue_script("jquery");
		wp_enqueue_script("jquery-ui-core");
		wp_enqueue_script("jquery-ui-autocomplete", "", array("jquery-ui-widget", "jquery-ui-position"), "1.8.6");
		wp_enqueue_style("jquery-ui-autocomplete", plugins_url("css/jquery-ui-1.8.18.custom.css", __FILE__));
		?>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery("input#location").focus(function() {
					jQuery("input#location").val("");
				});
				jQuery("input#location").autocomplete({
					autoFocus: true,
					source: function(request,response) {
						var data=<?php echo($cityZipListJson);?>;
						var searchTerm=request.term;
						searchTerm=searchTerm.toLowerCase();
						var results=new Array();
						for(var i=0; i<data.length;i++) {
							var oneTerm=data[i];
							var value=oneTerm.value + "";
							value=value.toLowerCase();
							if(value && value != null && value.indexOf(searchTerm) == 0) {
								results.push(oneTerm);
							}
						}
						response(results);
					},
					select: function(event, ui) {
						//When an item is selected, set the text value for the link
						jQuery("#title").val(ui.item.label);
					},
					selectFirst: true
				});
			});
		</script>
		<input id="location" class="regular-text" type="text" name="cityZip" placeholder="Enter City - OR - Postal Code" />
		<?php
	}
	
	private function createPropertyTypeSelect() {
		$formData = iHomefinderSearchFormFieldsUtility::getInstance()->getFormData();
		if(isset($formData)) {
			$propertyTypesList = $formData->getPropertyTypesList();
			if(isset($propertyTypesList)) {
				?>
				<select id="propertyType" name="propertyType" style="width: 100%;">
					<?php foreach ($propertyTypesList as $i => $value) { ?>
						<option value="<?php echo $propertyTypesList[$i]->propertyTypeCode ?>">
							<?php echo $propertyTypesList[$i]->displayName; ?>
						</option>
					<?php } ?>
				</select>
				<?php
			}
		}
	}
	
}