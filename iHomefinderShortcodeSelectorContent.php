<?php

class iHomefinderShortcodeSelectorContent {

	private static $instance;

	private function __construct() {
	}

	public static function getInstance() {
		if(!isset(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function getShortcodeSelectorContent() {
		?>
		<div class="panel-body">
			<ul class="nav nav-tabs" id="ihf-dialog-tabs">
				<li class="active">
					<a href="#Listings" data-toggle="tab">Listings</a>
				</li>
				<li>
					<a href="#Search" data-toggle="tab">Search</a>
				</li>
				<li>
					<a href="#IdxPages" data-toggle="tab">IDX Pages</a>
				</li>
				<?php if(iHomefinderPermissions::getInstance()->isAgentBioEnabled()) { ?>
					<li>
						<a href="#Broker" data-toggle="tab">Broker</a>
					</li>
				<?php } ?>
			</ul>
			<div class="tab-content">
				<div class="tab-pane fade in active" id="Listings">
					<h4></h4>
					<div class="col-xs-4">
						<div class="form-group">
							<div class="radio">
								<label class="control-label">
									<input name="shortcodeType" type="radio" onclick="jQuery('.listingGalleryMenu').show(); jQuery('.ihfMenu').hide();">
									Listing Gallery
								</label>
							</div>
							<div class="form-group listingGalleryMenu" style="display: none;">
								<select class="form-control" name="header" onchange="jQuery('.ihfMenu').hide(); jQuery('#' + this.value).toggle();">
									<option value="">Type</option>
									<option value="featuredMenu">Featured Listings</option>
									<?php if(iHomefinderPermissions::getInstance()->isAgentBioEnabled()) { ?>
										<option value="agentMenu">Agent Listing</option>
									<?php } ?>
									<?php if(iHomefinderPermissions::getInstance()->isOfficeEnabled()) { ?>
										<option value="officeMenu">Office Listing</option>
									<?php } ?>
									<?php if(iHomefinderPermissions::getInstance()->isHotSheetEnabled()) { ?>
										<option value="toppicksMenu">Saved Search</option>
									<?php } ?>
									<option value="searchMenu">Search</option>
								</select>
							</div>
							<?php if(iHomefinderLayoutManager::getInstance()->supportsListingGallery()) { ?>
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.listingGalleryMenu').hide(); jQuery('.ihfMenu').hide(); jQuery('#listingGalleryMenu').toggle();">
										Gallery Slider
									</label>
								</div>
							<?php } ?>
						</div>
					</div>
					<div class="col-xs-8">
						<div id="featuredMenu" style="display: none;" class="ihfMenu">
							<form onsubmit="return false;" action="#">
								<div class="checkbox">
									<label class="control-label">
										<input type="checkbox" value="true" name="includeMap" />
										Include Map
									</label>
								</div>
								<div class="form-group">
									<label class="control-label">Sort</label>
									<div>
										<?php $this->createSortSelect(true); ?>
									</div>
								</div>
								<?php if(iHomefinderLayoutManager::getInstance()->supportsResultsDisplayType()) { ?>
									<div class="form-group">
										<label class="control-label">Display Type</label>
										<div>
											<?php $this->createDisplayTypeSelect(); ?>
										</div>
									</div>
								<?php } ?>
								<?php if(iHomefinderLayoutManager::getInstance()->supportsResultsResultsPerPage()) { ?>
									<div class="form-group">
										<label class="control-label">Results Per Page</label>
										<div>
											<input class="form-control" type="number" name="resultsPerPage" />
										</div>
									</div>
								<?php } ?>
								<div class="form-group">
									<label class="control-label">Display Header</label>
									<div>
										<?php $this->createHeaderSelect(true); ?>
									</div>
								</div>
								<input class="btn btn-default" type="button" name="insertFeatured" value="Insert" onclick="return IhfGalleryDialog.validateForm(this.form) && IhfGalleryDialog.insertFeaturedListings(this.form, '<?php echo(iHomefinderShortcodeDispatcher::getInstance()->getFeaturedShortcode()) ?>');" />
							</form>
						</div>
						<div id="toppicksMenu" style="display: none;" class="ihfMenu">
							<form onsubmit="return false;" action="#">
								<div class="form-group">
									<label class="control-label">Saved Search</label>
									<div>
										<?php $this->createTopPicksSelect(true); ?>
									</div>
								</div>
								<div class="checkbox">
									<label class="control-label">
										<input type="checkbox" value="true" name="includeMap" />
										Include Map
									</label>
								</div>
								<div class="form-group">
									<label class="control-label">Sort</label>
									<div>
										<?php $this->createSortSelect(); ?>
									</div>
								</div>
								<?php if(iHomefinderLayoutManager::getInstance()->supportsResultsDisplayType()) { ?>
									<div class="form-group">
										<label class="control-label">Display Type</label>
										<div>
											<?php $this->createDisplayTypeSelect(); ?>
										</div>
									</div>
								<?php } ?>
								<?php if(iHomefinderLayoutManager::getInstance()->supportsResultsResultsPerPage()) { ?>
									<div class="form-group">
										<label class="control-label">Results Per Page</label>
										<div>
											<input class="form-control" type="number" name="resultsPerPage" />
										</div>
									</div>
								<?php } ?>
								<div class="form-group">
									<label class="control-label">Display Header</label>
									<div>
										<?php $this->createHeaderSelect(true); ?>
									</div>
								</div>
								<input class="btn btn-default" type="button" name="insertToppicks" value="Insert" onclick="return IhfGalleryDialog.validateForm(this.form) && IhfGalleryDialog.insertToppicks(this.form, '<?php echo(iHomefinderShortcodeDispatcher::getInstance()->getToppicksShortCode()) ?>');" />
							</form>
						</div>
						<div id="searchMenu" style="display: none;" class="ihfMenu">
							<form onsubmit="return false;" action="#">
								<div class="form-group">
									<label class="control-label">Cities</label>
									<div>
										<?php $this->createCitySelect(true); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Property Type</label>
									<div>
										<?php $this->createPropertyTypeSelect(true); ?>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Bed</label>
									<div>
										<input class="form-control" type="number" name="bed" />
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Bath</label>
									<div>
										<input class="form-control" type="number" name="bath" />
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Min Price</label>
									<div>
										<input class="form-control" type="number" name="minPrice" />
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Max Price</label>
									<div>
										<input class="form-control" type="number" name="maxPrice" />
									</div>
								</div>
								<div class="checkbox">
									<label class="control-label">
										<input type="checkbox" value="true" name="includeMap" />
										Include Map
									</label>
								</div>
								<div class="form-group">
									<label class="control-label">Sort</label>
									<div>
										<?php $this->createSortSelect(true); ?>
									</div>
								</div>
								<?php if(iHomefinderLayoutManager::getInstance()->supportsResultsDisplayType()) { ?>
									<div class="form-group">
										<label class="control-label">Display Type</label>
										<div>
											<?php $this->createDisplayTypeSelect(); ?>
										</div>
									</div>
								<?php } ?>
								<?php if(iHomefinderLayoutManager::getInstance()->supportsResultsResultsPerPage()) { ?>
									<div class="form-group">
										<label class="control-label">Results Per Page</label>
										<div>
											<input class="form-control" type="number" name="resultsPerPage" />
										</div>
									</div>
								<?php } ?>
								<div class="form-group">
									<label class="control-label">Display Header</label>
									<div>
										<?php $this->createHeaderSelect(true); ?>
									</div>
								</div>
								<input class="btn btn-default" type="button" name="insertSearchResults" value="Insert" onclick="return IhfGalleryDialog.validateForm(this.form) && IhfGalleryDialog.insertSearchResults(this.form, '<?php echo(iHomefinderShortcodeDispatcher::getInstance()->getSearchResultsShortcode()) ?>');" />
							</form>
						</div>
						<div id="agentMenu" style="display: none;" class="ihfMenu">
							<form onsubmit="return false;" action="#">
								<div class="form-group">
									<label class="control-label">Agent</label>
									<div>
										<?php $this->createAgentSelect(true); ?>
									</div>
								</div>
								<input class="btn btn-default" type="button" name="insertAgentListings" value="Insert" onclick="return IhfGalleryDialog.validateForm(this.form) && IhfGalleryDialog.insertAgentListings(this.form, '<?php echo(iHomefinderShortcodeDispatcher::getInstance()->getAgentListingsShortcode()) ?>');" />
							</form>
						</div>
						<div id="officeMenu" style="display: none;" class="ihfMenu">
							<form onsubmit="return false;" action="#">
								<div class="form-group">
									<label class="control-label">Office</label>
									<div>
										<?php $this->createOfficeSelect(true); ?>
									</div>
								</div>
								<input class="btn btn-default" type="button" name="insertOfficeListings" value="Insert" onclick="return IhfGalleryDialog.validateForm(this.form) && IhfGalleryDialog.insertOfficeListings(this.form, '<?php echo(iHomefinderShortcodeDispatcher::getInstance()->getOfficeListingsShortcode()) ?>');" />
							</form>
						</div>
						<div id="listingGalleryMenu" style="display: none;" class="ihfMenu">
							<form onsubmit="return false;" action="#">
								<div class="form-group">
									<?php if(iHomefinderPermissions::getInstance()->isHotSheetEnabled()) { ?>
										<label class="radio-inline">
											<input type="radio" name="type" checked onclick="jQuery('#TopPicksSelect').hide(); jQuery('select#toppickId').prop('selectedIndex', 0); jQuery('select#toppickId').removeAttr('required');" />
											Featured
										</label>
										<label class="radio-inline">
											<input type="radio" name="type" onclick="jQuery('#TopPicksSelect').show(); jQuery('select#toppickId').attr('required', 'required');" />
											Saved Search
										</label>
									<?php } ?>
								</div>
								<div id="TopPicksSelect" class="form-group" style="display: none;">
									<?php $this->createTopPicksSelect(); ?>
								</div>
								<?php if(iHomefinderLayoutManager::getInstance()->supportsListingGalleryResponsiveness()) { ?>
									<div class="form-group">
										<label class="control-label">Width</label>
										<div class="checkbox">
											<label class="control-label">
												<input type="checkbox" name="fitToWidth" checked onchange="jQuery('#listingGalleryWidth').toggle();">
												Fit to column
											</label>
										</div>
										<div class="input-group" style="display: none;" id="listingGalleryWidth">
											<input class="form-control" type="text" name="width" />
											<span class="input-group-addon">px</span>
										</div>
									</div>
								<?php } else { ?>
									<div class="form-group">
										<label class="control-label">Width</label>
										<div class="input-group">
											<input class="form-control" type="number" name="width" required="required" />
											<span class="input-group-addon">px</span>
										</div>
									</div>
								<?php } ?>
								<div class="form-group">
									<label class="control-label">Height</label>
									<div class="input-group">
										<input class="form-control" type="number" name="height" placeholder="Default" />
										<span class="input-group-addon">px</span>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Rows</label>
									<div>
										<input class="form-control" type="number" name="rows" required="required" />
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Columns</label>
									<div>
										<input class="form-control" type="number" name="columns" required="required" />
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Effect</label>
									<div>
										<select class="form-control" name="effect" required="required">
											<option value="slide">Slide</option>
											<option value="fade">Fade</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Auto Advance</label>
									<div>
										<select class="form-control" name="auto" required="required">
											<option value="true">Yes</option>
											<option value="false">No</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="control-label">Max. Results</label>
									<div>
										<input class="form-control" type="number" name="maxResults" value="25" />
									</div>
								</div>
								<input class="btn btn-default" type="button" name="insertListingGallery" value="Insert" onclick="return IhfGalleryDialog.validateForm(this.form) && IhfGalleryDialog.insertListingGallery(this.form, '<?php echo(iHomefinderShortcodeDispatcher::getInstance()->getListingGalleryShortcode()) ?>');" />
							</form>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="Search">
					<h4></h4>
					<div class="col-xs-4">
						<div class="form-group">
							<div class="radio">
								<label class="control-label">
									<input name="shortcodeType" type="radio" onclick="jQuery('.ihfMenu').hide(); jQuery('#quickSearchMenu').toggle();">
									Quick Search
								</label>
							</div>
							<?php if(iHomefinderPermissions::getInstance()->isMapSearchEnabled()) { ?>
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.ihfMenu').hide(); jQuery('#mapSearchMenu').toggle();">
										Map Search
									</label>
								</div>
							<?php } ?>
							<?php if(iHomefinderPermissions::getInstance()->isSearchByAddressEnabled()) { ?>
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.ihfMenu').hide(); jQuery('#searchByAddressMenu').toggle();">
										Address Search
									</label>
								</div>
							<?php } ?>
							<?php if(iHomefinderPermissions::getInstance()->isSearchByListingIdEnabled()) { ?>
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.ihfMenu').hide(); jQuery('#searchByListingIdMenu').toggle();">
										Listing ID Search
									</label>
								</div>
							<?php } ?>
						</div>
					</div>
					<div class="col-xs-8">
						<div id="quickSearchMenu" style="display: none;" class="ihfMenu">
							<form onsubmit="return false;" action="#">
								<?php if(iHomefinderLayoutManager::getInstance()->supportsMultipleQuickSearchLayouts()) { ?>
									<div class="form-group">
										<label class="control-label">Style</label>
										<div>
											<select class="form-control" name="style" required="required">
												<option value="">Select One</option>
												<option value="horizontal">Horizontal</option>
												<option value="twoline">Two Line</option>
												<option value="vertical">Vertical</option>
											</select>
										</div>
									</div>
								<?php } ?>
								<?php if(iHomefinderLayoutManager::getInstance()->supportsQuickSearchPropertyType()) { ?>
									<div class="form-group">
										<div class="checkbox">
											<label class="control-label">
												<input type="checkbox" name="showPropertyType" value="true" checked />
												<span>Show Property Type</span>
											</label>
										</div>
									</div>
								<?php } ?>
								<input class="btn btn-default" type="button" name="insertQuickSearch" value="Insert" onclick="return IhfGalleryDialog.validateForm(this.form) && IhfGalleryDialog.insertQuickSearch(this.form, '<?php echo(iHomefinderShortcodeDispatcher::getInstance()->getQuickSearchShortcode()) ?>');" />
							</form>
						</div>
						<div id="mapSearchMenu" style="display: none;" class="ihfMenu">
							<form onsubmit="return false;" action="#">
							<?php if(iHomefinderLayoutManager::getInstance()->supportsMapSearchResponsiveness()) { ?>
									<div class="form-group">
										<label class="control-label">Width</label>
										<div class="checkbox">
											<label class="control-label">
												<input type="checkbox" name="fitToWidth" checked onchange="jQuery('#mapSearchWidth').toggle();">
												Fit to column
											</label>
										</div>
										<div class="input-group" style="display: none;" id="mapSearchWidth">
											<input class="form-control" type="text" name="width" />
											<span class="input-group-addon">px</span>
										</div>
									</div>
								<?php } else { ?>
									<div class="form-group">
										<label class="control-label">Width</label>
										<div class="input-group">
											<input class="form-control" type="number" name="width" required="required" />
											<span class="input-group-addon">px</span>
										</div>
									</div>
								<?php } ?>
								<div class="form-group">
									<label class="control-label">Height</label>
									<div class="input-group">
										<input class="form-control" type="number" name="height" />
										<span class="input-group-addon">px</span>
									</div>
								</div>
								<?php if(iHomefinderLayoutManager::getInstance()->supportsMapSearchCenterLatLong()) { ?>
									<div class="form-group">
										<label class="control-label">Center Latitude</label>
										<div>
											<input class="form-control" type="text" name="centerlat" />
										</div>
									</div>
									<div class="form-group">
										<label class="control-label">Center Longitude</label>
										<div>
											<input class="form-control" type="text" name="centerlong" />
										</div>
									</div>
								<?php } ?>
								<?php if(iHomefinderLayoutManager::getInstance()->supportsMapSearchCenterAddress()) { ?>
									<div class="form-group">
										<label class="control-label">Center Address</label>
										<div>
											<input class="form-control" type="text" name="address" placeholder="e.g., 1900 Addison Street, Berkeley, CA" />
										</div>
									</div>
								<?php } ?>
								<div class="form-group">
									<label class="control-label">Zoom Level</label>
									<div>
										<select class="form-control" name="zoom" required="required">
											<option value="1">1</option>
											<option value="2">2</option>
											<option value="3">3</option>
											<option value="4">4</option>
											<option value="5">5</option>
											<option value="6">6</option>
											<option value="7">7</option>
											<option value="8">8</option>
											<option value="9">9</option>
											<option value="10" selected>10</option>
											<option value="11">11</option>
											<option value="12">12</option>
											<option value="13">13</option>
											<option value="14">14</option>
											<option value="15">15</option>
											<option value="16">16</option>
											<option value="17">17</option>
											<option value="18">18</option>
											<option value="19">19</option>
											<option value="20">20</option>
										</select>
									</div>
								</div>
								
								<input class="btn btn-default" type="button" name="insertMapSearch" value="Insert" onclick="return IhfGalleryDialog.validateForm(this.form) && IhfGalleryDialog.insertMapSearch(this.form, '<?php echo(iHomefinderShortcodeDispatcher::getInstance()->getMapSearchShortcode()) ?>');" />
							</form>
						</div>
						<div id="searchByAddressMenu" style="display: none;" class="ihfMenu">
							<form onsubmit="return false;" action="#">
								<div class="form-group">
									<label class="control-label">Style</label>
									<div>
										<select class="form-control" name="style" required="required">
											<option value="">Select One</option>
											<option value="horizontal">Horizontal</option>
											<option value="vertical">Vertical</option>
										</select>
									</div>
								</div>
								<input class="btn btn-default" type="button" name="insertSearchByAddress" value="Insert" onclick="return IhfGalleryDialog.validateForm(this.form) && IhfGalleryDialog.insertSearchByAddress(this.form, '<?php echo(iHomefinderShortcodeDispatcher::getInstance()->getSearchByAddressShortcode()) ?>');" />
							</form>
						</div>
						<div id="searchByListingIdMenu" style="display: none;" class="ihfMenu">
							<form onsubmit="return false;" action="#">
								<input class="btn btn-default" type="button" name="insertSearchByListingId" value="Insert" onclick="return IhfGalleryDialog.validateForm(this.form) && IhfGalleryDialog.insertSearchByListingId(this.form, '<?php echo(iHomefinderShortcodeDispatcher::getInstance()->getSearchByListingIdShortcode()) ?>');" />
							</form>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="IdxPages">
					<h4></h4>
					<div class="col-xs-4">
						<div class="form-group">
							<div class="radio">
								<label class="control-label">
									<input name="shortcodeType" type="radio" onclick="jQuery('.ihfMenu').hide(); jQuery('#basicSearchMenu').toggle();">
									Basic Search Form
								</label>
							</div>
							<div class="radio">
								<label class="control-label">
									<input name="shortcodeType" type="radio" onclick="jQuery('.ihfMenu').hide(); jQuery('#advancedSearchMenu').toggle();">
									Advanced Search Form
								</label>
							</div>
							<?php if(iHomefinderPermissions::getInstance()->isOrganizerEnabled()) { ?>
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.ihfMenu').hide(); jQuery('#organizerLoginMenu').toggle();">
										Property Organizer Login
									</label>
								</div>
							<?php } ?>
							<?php if(iHomefinderPermissions::getInstance()->isValuationEnabled()) { ?>
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.ihfMenu').hide(); jQuery('#valuationFormMenu').toggle();">
										Valuation Form
									</label>
								</div>
							<?php } ?>
						</div>
					</div>
					<div class="col-xs-8">
						<div id="basicSearchMenu" style="display: none;" class="ihfMenu">
							<form onsubmit="return false;" action="#">
								<input class="btn btn-default" type="button" name="insertBasicSearch" value="Insert" onclick="return IhfGalleryDialog.validateForm(this.form) && IhfGalleryDialog.insertBasicSearch(this.form, '<?php echo(iHomefinderShortcodeDispatcher::getInstance()->getBasicSearchShortcode()) ?>');" />
							</form>
						</div>
						<div id="advancedSearchMenu" style="display: none;" class="ihfMenu">
							<form onsubmit="return false;" action="#">
								<input class="btn btn-default" type="button" name="insertAdvancedSearch" value="Insert" onclick="return IhfGalleryDialog.validateForm(this.form) && IhfGalleryDialog.insertAdvancedSearch(this.form, '<?php echo(iHomefinderShortcodeDispatcher::getInstance()->getAdvancedSearchShortcode()) ?>');" />
							</form>
						</div>
						<div id="organizerLoginMenu" style="display: none;" class="ihfMenu">
							<form onsubmit="return false;" action="#">
								<input class="btn btn-default" type="button" name="insertOrganizerLogin" value="Insert" onclick="return IhfGalleryDialog.validateForm(this.form) && IhfGalleryDialog.insertOrganizerLogin(this.form, '<?php echo(iHomefinderShortcodeDispatcher::getInstance()->getOrganizerLoginShortcode()) ?>');" />
							</form>
						</div>
						<div id="valuationFormMenu" style="display: none;" class="ihfMenu">
							<form onsubmit="return false;" action="#">
								<input class="btn btn-default" type="button" name="insertValuationForm" value="Insert" onclick="return IhfGalleryDialog.validateForm(this.form) && IhfGalleryDialog.insertValuationForm(this.form, '<?php echo(iHomefinderShortcodeDispatcher::getInstance()->getValuationFormShortcode()) ?>');" />
							</form>
						</div>
					</div>
				</div>
				<div class="tab-pane fade" id="Broker">
					<h4></h4>
					<div class="col-xs-5">
						<?php if(iHomefinderPermissions::getInstance()->isAgentBioEnabled()) { ?>
							<div class="form-group">
								<div class="radio">
									<label class="control-label">
										<input name="shortcodeType" type="radio" onclick="jQuery('.ihfMenu').hide(); jQuery('#agentDetailMenu').toggle();">
										Agent Bio
									</label>
								</div>
							</div>
						<?php } ?>
					</div>
					<div class="col-xs-7">
						<div id="agentDetailMenu" style="display: none;" class="ihfMenu">
							<form onsubmit="return false;" action="#">
								<div class="form-group">
									<label class="control-label">Agent</label>
									<div>
										<?php $this->createAgentSelect(true); ?>
									</div>
								</div>
								<input class="btn btn-default" type="button" name="insertAgentDetail" value="Insert" onclick="return IhfGalleryDialog.validateForm(this.form) && IhfGalleryDialog.insertAgentDetail(this.form, '<?php echo(iHomefinderShortcodeDispatcher::getInstance()->getAgentDetailShortcode()) ?>');" />
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		die(); //don't remove
	}
	
	private function createAgentSelect($required = false) {
		$values = iHomefinderSearchFormFieldsUtility::getInstance()->getFormData()->getAgentList();
		?>
		<select class="form-control" id="agentId" name="agentId"
			<?php if($required === true) { ?>
				required="required"
			<?php } ?>
		>
			<option value="">Select One</option>
			<?php foreach($values as $index => $value) { ?>
				<option value="<?php echo $value->agentId ?>">
					<?php echo $value->agentName ?>
				</option>
			<?php } ?>
		</select>
		<?php
	}
	
	private function createOfficeSelect($required = false) {
		$values = iHomefinderSearchFormFieldsUtility::getInstance()->getFormData()->getOfficeList();
		?>
		<select class="form-control" id="officeId" name="officeId"
			<?php if($required === true) { ?>
				required="required"
			<?php } ?>
		>
			<option value="">Select One</option>
			<?php foreach($values as $index => $value) { ?>
				<option value="<?php echo $value->officeId ?>">
					<?php echo $value->officeName ?>
				</option>
			<?php } ?>
		</select>
		<?php
	}
	
	private function createTopPicksSelect($required = false) {
		$values = iHomefinderSearchFormFieldsUtility::getInstance()->getFormData()->getHotsheetList();
		?>
		<select class="form-control" id="toppickId" name="toppickId"
			<?php if($required === true) { ?>
				required="required"
			<?php } ?>
		>
			<option value="">Select One</option>
			<?php foreach($values as $index => $value) { ?>
				<option value="<?php echo $value->hotsheetId ?>">
					<?php echo $value->displayName ?>
				</option>
			<?php } ?>
		</select>
		<?php
	}
	
	private function createCitySelect($required = false) {
		$values = iHomefinderSearchFormFieldsUtility::getInstance()->getFormData()->getCitiesList();
		?>
		<select class="form-control" id="cityId" name="cityId"
			<?php if($required === true) { ?>
				required="required"
			<?php } ?>
		>
			<option value="">Select One</option>
			<?php foreach($values as $index => $value) { ?>
				<option value="<?php echo $value->cityId ?>">
					<?php echo $value->displayName ?>
				</option>
			<?php } ?>
		</select>
		<?php
	}
	
	private function createPropertyTypeSelect($required = false) {
		$values = iHomefinderSearchFormFieldsUtility::getInstance()->getFormData()->getPropertyTypesList();
		?>
		<select class="form-control" id="propertyType" name="propertyType"
			<?php if($required === true) { ?>
				required="required"
			<?php } ?>
		>
			<option value="">Select One</option>
			<?php foreach($values as $index => $value) { ?>
				<option value="<?php echo $value->propertyTypeCode ?>">
					<?php echo $value->displayName ?>
				</option>
			<?php } ?>
		</select>
		<?php
	}
	
	private function createSortSelect($required = false) {
		?>
		<select class="form-control" id="sortBy" name="sortBy"
			<?php if($required === true) { ?>
				required="required"
			<?php } ?>
		>
			<option value="pd">Price Descending</option>
			<option value="pa">Price Ascending</option>
		</select>
		<?php
	}
	
	private function createDisplayTypeSelect($required = false) {
		?>
		<select class="form-control" id="displayType" name="displayType"
			<?php if($required === true) { ?>
				required="required"
			<?php } ?>
		>
			<option value="">Default</option>
			<option value="list">List</option>
			<option value="grid">Grid</option>
		</select>
		<?php
	}
	
	private function createHeaderSelect($required = false) {
		?>
			<select class="form-control" id="sortBy" name="sortBy"
				<?php if($required === true) { ?>
					required="required"
				<?php } ?>
			>
				<option value="true">Yes</option>
				<option value="false">No</option>
			</select>
			<?php
		}
	
}