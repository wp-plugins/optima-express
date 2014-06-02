(function($) {
	/**
	 * The Area Picker requires area data in a JSON format as follows:
	 * 
	 * Pass a JSON object with a structure like
	 * 
	 * {'allAreas' : {'values' : [ {'value' : '12', 'label' : 'Alameda', 'match' :
	 * 'Alameda', 'fieldName' : 'cityId', 'groupLabel' : '', 'uniqueId' : '12'} ,
	 * {'value' : '13', 'label' : 'Alamo', 'match' : 'Alamo', 'fieldName' :
	 * 'cityId', 'groupLabel' : '', 'uniqueId' : '13'} , {'value' : '14',
	 * 'label' : 'Albany', 'match' : 'Albany', 'fieldName' : 'cityId',
	 * 'groupLabel' : '', 'uniqueId' : '14'} , {'value' : '33', 'label' :
	 * 'Antioch', 'match' : 'Antioch', 'fieldName' : 'cityId', 'groupLabel' :
	 * '', 'uniqueId' : '33'} , {'value' : '60', 'label' : 'Bay Point', 'match' :
	 * 'Bay Point', 'fieldName' : 'cityId', 'groupLabel' : '', 'uniqueId' :
	 * '60'} , {'value' : '71', 'label' : 'Benicia', 'match' : 'Benicia',
	 * 'fieldName' : 'cityId', 'groupLabel' : '', 'uniqueId' : '71'} , {'value' :
	 * '95687', 'label' : '95687', 'match' : '95687', 'fieldName' : 'zip',
	 * 'groupLabel' : '', 'uniqueId' : '95687'} , {'value' : '95694', 'label' :
	 * '95694', 'match' : '95694', 'fieldName' : 'zip', 'groupLabel' : '',
	 * 'uniqueId' : '95694'} , {'value' : '95828', 'label' : '95828', 'match' :
	 * '95828', 'fieldName' : 'zip', 'groupLabel' : '', 'uniqueId' : '95828'} ,
	 * {'value' : '95965', 'label' : '95965', 'match' : '95965', 'fieldName' :
	 * 'zip', 'groupLabel' : '', 'uniqueId' : '95965'} , {'value' : '96001',
	 * 'label' : '96001', 'match' : '96001', 'fieldName' : 'zip', 'groupLabel' :
	 * '', 'uniqueId' : '96001'} , {'value' : '96114', 'label' : '96114',
	 * 'match' : '96114', 'fieldName' : 'zip', 'groupLabel' : '', 'uniqueId' :
	 * '96114'} ] } 'customAreas' : {'values' : [ {'value' : '12', 'label' :
	 * 'Alameda', 'match' : 'Alameda', 'fieldName' : 'cityId', 'groupLabel' :
	 * '', 'uniqueId' : '12'} , {'value' : '13', 'label' : 'Alamo', 'match' :
	 * 'Alamo', 'fieldName' : 'cityId', 'groupLabel' : '', 'uniqueId' : '13'} ,
	 * {'value' : '96001', 'label' : '96001', 'match' : '96001', 'fieldName' :
	 * 'zip', 'groupLabel' : '', 'uniqueId' : '96001'} , {'value' : '96114',
	 * 'label' : '96114', 'match' : '96114', 'fieldName' : 'zip', 'groupLabel' :
	 * '', 'uniqueId' : '96114'} ] } };
	 * 
	 * Some settings can be set to alter the look and feel.
	 * 
	 * source: JSON variable that must contain a label, value, fieldName (for
	 * new form inputs) and possibly parent id preSelectedItems: items that
	 * should be selected when the area picker loads enabledItems: if empty, all
	 * items are enabled. if not empty all items display but only items in this
	 * list are selectable (useful for list of cities with Open Homes)
	 * selectedItemsContainer: id of an HTML element used to display selected areas
	 * selectedItemsLabel: id of an HTML element used to display selected areas label
	 * selected items autoCompleteMatchValues: id of an HTML element to display
	 * autocomplete matches areaPickerExpandAllButtonClass: CSS class name used
	 * which enables on click functionality to expand or contract the full list
	 * of areas areaPickerExpandAll: id of an HTML element that holds the full
	 * list of areas. This HTML element is hidden and displayed depending on
	 * clicks to elements of class areaPickerExpandAllButtonClass
	 * areaPickerExpandAllContainer: id of an HTML element that displays each
	 * area. JavaScript appends new divs for each area to this div.
	 * numberOfColumnsExpandedMode: number of columns for displaying areas in
	 * fully expanded mode outerContainer: HTML element that contains the
	 * areaPicker omitAreaTypesInExpandedMode: in the fully expanded view, hide
	 * areas of this type (for example, we hide fields of type zip, but not
	 * neighborhood or city) expandSubAreasButton: image used to expand subareas
	 * closeSubAreasButton: image used to close subareas defaultInputValue:
	 * default text in the area selector input box autocompleteMessage: message
	 * that displays in the autocomplete area, when a user starts typing
	 * customAreaListToggle: jQuery element that shows a custom area list or
	 * more areas list customAreaListToggleMoreAreasText: More areas text HTML,
	 * to display all areas customAreaListToggleLessAreasText: Custom areas text
	 * HTML, to display all areas areaPickerClearAll: jQuery element that will
	 * clear all selections. isPhpStyle: is this for php For multiple items
	 * selected, php requires an array [] naming convention (like cityId[])
	 * autocompleteTextLength: number of characters that must be typed to start autocomplete
	 */
	jQuery.fn.areaPicker = function(options) {
		var settings = jQuery
				.extend(
						{
							source : {}, // Source must contain a label,
											// value, fieldName (for new form
											// inputs) and possibly parent id
							preSelectedItems : {},
							enabledItems : {},
							selectedItemsContainer : jQuery("#selectedAreas"), // Div to display selected items
							selectedItemsLabel : jQuery("#selectedAreasLabel"),
							autoCompleteMatchValues : jQuery("#autoCompleteMatchValues"),
							areaPickerExpandAllButtonClass : ".areaPickerExpandAllButtonClass",
							areaPickerExpandAll : jQuery("#areaPickerExpandAll"),
							areaPickerExpandAllContainer : jQuery("#areaPickerExpandAllContainer"),
							numberOfColumnsExpandedMode : null,
							outerContainer : jQuery("#areaPickerContainer"),
							omitAreaTypesInExpandedMode : '',
							expandSubAreasButton : "&nbsp;+&nbsp;",
							closeSubAreasButton : "&nbsp;&minus;&nbsp;",
							defaultAutocompleteValue : 'City / Neighborhood / Zip',
							autocompleteTextLength: 3,
							autocompleteMessage : 'Enter a city, neighborhood or zip.',
							customAreaListToggle : jQuery("#areaPickerCustomListToggle"),
							customAreaListToggleMoreAreasText : '&nbsp;More Areas',
							customAreaListToggleLessAreasText : '&nbsp;Limit Areas',
							areaPickerClearAll : jQuery("#areaPickerClearAll"),
							isPhpStyle : "false"
						}, options);

		/**
		 * This follows jQuery conventions of returning this object to maintain
		 * chainablity
		 */
		return this.each(function() {
					// This object refers to the DOM element
					// so create a new variable $this to refer to
					// the jQuery decorated version.
					var $this = jQuery(this);
					var source = settings.source;
					var preSelectedItems = settings.preSelectedItems;
					var enabledItems = settings.enabledItems;
					var allItemsEnabled = true;
					var selectedItemsContainer = settings.selectedItemsContainer;
					var selectedItemsLabel = settings.selectedItemsLabel;
					var autoCompleteMatchValues = settings.autoCompleteMatchValues;
					var areaPickerExpandAllButtonClass = settings.areaPickerExpandAllButtonClass;
					var areaPickerExpandAll = settings.areaPickerExpandAll;
					var areaPickerExpandAllContainer = settings.areaPickerExpandAllContainer;
					var omitAreaTypesInExpandedMode = settings.omitAreaTypesInExpandedMode;
					var defaultAutocompleteValue = settings.defaultAutocompleteValue;
					var autocompleteMessage = settings.autocompleteMessage;
					var autocompleteTextLength = settings.autocompleteTextLength;
					var outerContainer = settings.outerContainer;
					var numberOfColumnsExpandedMode = settings.numberOfColumnsExpandedMode;
					var customAreaListToggle = settings.customAreaListToggle;
					var customAreaListToggleMoreAreasText = settings.customAreaListToggleMoreAreasText;
					var customAreaListToggleLessAreasText = settings.customAreaListToggleLessAreasText;
					var areaPickerClearAll = settings.areaPickerClearAll;
					var isPhpStyle = false;
					if (settings.isPhpStyle.toLowerCase() === "true") {
						isPhpStyle = true;
					}

					if (enabledItems !== null
							&& enabledItems.areas !== undefined) {
						allItemsEnabled = false;
					}

					var expandAllResults ;

					var selectedItemsCache = {};
					var enabledItemsCache = {};

					var ARROW_UP_KEY = 38;
					var ARROW_DOWN_KEY = 40;
					var ENTER_KEY = 13;

					var selectedAutoCompleteIndex = -1;
					var allAreas = source.allAreas.values;
					var customAreas = new Array();
					if (source.customAreas !== undefined) {
						customAreas = source.customAreas.values;
					}

					var init = function() {
						// turn off autocomplete for this input element
						$this.attr("autocomplete", "off");
						setDefaults(false);
						//Set the initial default text.  After initially selecting an area this is empty.
						$this.val(defaultAutocompleteValue);
						$this.addClass("areaPickerDefaultText");

						//When the container is clicked set the focus to this input
						outerContainer.click( function(){setDefaults(true);});

						// If enabled items is set, then only items in the
						// enabledItemsCache are
						// enabled. All other items will display, but cannot be
						// selected
						// and the CSS will show the NOT enabled items
						// differently
						if (!allItemsEnabled) {
							var enabledAreas = enabledItems.areas.values;
							setEnabledItems(enabledAreas);
						}

						// Add clear all functionality to the Clear Div
						areaPickerClearAll.click(function(event) {
							removeAllItems();
						});

						// Add full list of areas (cities and neighborhoods) to
						// the area picker
						expandAllResults = jQuery("<div/>", {
							id : "areaPickerExpandAllResults"
						});
						areaPickerExpandAllContainer.append(expandAllResults);
						createFullyExpandedArea(allAreas, expandAllResults,
								getExpandAllDivId);

						// If customAreas exist, then add custom areas to the
						// expand all area picker
						// and hide the full list of areas. Users can toggle
						// between the custom list
						// and the full list of areas.
						if (customAreas.length > 0) {
							var expandCustomResults = jQuery("<div/>", {
								id : "areaPickerExpandCustomResults"
							});
							areaPickerExpandAllContainer.append(expandCustomResults);
							createFullyExpandedArea(customAreas,expandCustomResults, getExpandCustomDivId);
							expandAllResults.hide();
							setCustomAreaListToggleText();
							customAreaListToggle.click(function(event) {
								expandAllResults.toggle();
								expandCustomResults.toggle();
								setCustomAreaListToggleText();
							});
						} else {
							// If there is not a custom list, then we only
							// display all results (no option for a custom list)
							customAreaListToggle.hide();
						}
						

						if (preSelectedItems !== undefined && preSelectedItems != null
								&& preSelectedItems.areas !== undefined && preSelectedItems.areas != null) {
							var preSelectedAreas = preSelectedItems.areas.values;
							setPreSelectedItems(preSelectedAreas);
						}
					};

					// Toggle the text to diplay a full list of areas
					// or a custom list of areas.
					var setCustomAreaListToggleText = function() {
						if (expandAllResults.is(":visible")) {
							customAreaListToggle.html(customAreaListToggleLessAreasText);
						} else {
							customAreaListToggle.html(customAreaListToggleMoreAreasText);
						}
					};

					var setPreSelectedItems = function(preSelectedItems) {
						for ( var x = 0; x < preSelectedItems.length; x++) {
							var match = preSelectedItems[x];
							addToSelectedItemsCache(match);
							updateSelectedItems(match);
						}
					};

					var setEnabledItems = function(enabledItems) {
						for ( var x = 0; x < enabledItems.length; x++) {
							var match = enabledItems[x];
							addToEnabledItemsCache(match);
						}
					};

					var isEnabled = function(item) {
						if (allItemsEnabled) {
							return true;
						}

						var key = getCacheKey(item);
						var enabledItem = enabledItemsCache[key];
						if (enabledItem !== undefined && enabledItem !== null) {
							return true;
						}

						return false;

					};

					// Used to create data for the fully expanded mode. Note
					// fully expanded mode
					// may contain a the custom area list or a full list of
					// areas supported by
					// the boards.
					var createFullyExpandedArea = function(data, expandedArea,
							matchDivIdFunction) {
						var columnCount = 0;
						var endOfRow = false;

						for ( var i = 0; i < data.length; i++) {

							var match = data[i];

							if (match.label === "" && match.fieldName === ""
									&& match.groupLabel !== "") {
								// This may occur when grouping by county, where
								// the county name is the group label.
								expandedArea
										.append("<div style='clear:both'></div>");
								expandedArea
										.append("<div class='areaPickerExpandAllGroup'>"
												+ match.groupLabel + "</div>");
								columnCount = 0;
								continue;
							} else if (omitAreaTypesInExpandedMode
									.indexOf(match.fieldName) != -1) {
								// don't include these area values in the
								// expanded area selector
								// may still be available in the autocomplete
								// section.
								// For example, zip codes may be set to NOT
								// display in fully expanded mode
								// but may still be available for auto complete.
								continue;
							}

							endOfRow = (numberOfColumnsExpandedMode != null && (columnCount % numberOfColumnsExpandedMode) == 0);
							if (endOfRow) {
								expandedArea
										.append("<div class='areaPickerExpandAllRow' style='clear:both'></div>");
							}

							columnCount++;

							var matchDivId = matchDivIdFunction(match);
							var matchClass = 'areaPickerExpandAllElement';
							if (!isEnabled(match)) {
								matchClass = 'areaPickerExpandAllElementDisabled';
							}
							var matchDivContainer = jQuery("<div/>", {
								'class' : matchClass
							});
							var matchDiv = jQuery("<div/>", {
								id : matchDivId,
								'class' : 'areaUnselected'
							});
							matchDiv.append(match.label);
							matchDivContainer.append(matchDiv);
							expandedArea.append(matchDivContainer);

							if (match.subAreas != undefined
									&& match.subAreas.values != undefined
									&& match.subAreas.values.length > 0) {
								var matchSubAreaDivId = matchDivIdFunction(match)
										+ "_expand_all_sub_areas";
								var expandButton = jQuery("<span/>");
								expandButton.html(settings.expandSubAreasButton);
								
								var subAreasContainer = 
									jQuery("<div/>",{ id : matchSubAreaDivId, 'class' : 'areaPickerExpandAllSubAreaContainer'});
								var subAreasToggler = 
									jQuery("<div/>", { style : "float:left"	});
								subAreasToggler.append(expandButton);
								matchDiv.css("float", "left");
								matchDiv.addClass("areaPickerElementHasSubAreas");
								matchDiv.parent().append(subAreasToggler);

								subAreasContainer.append(jQuery("<div/>", {	style : "clear:both" }));
								expandedArea.append(subAreasContainer);

								var subAreaData = match.subAreas.values;
								for ( var j = 0; j < subAreaData.length; j++) {
									var subAreaMatch = subAreaData[j];
									subAreaMatch.parentLabel = match.label;
									var subAreaMatchDivId = matchDivIdFunction(subAreaMatch);
									subAreaMatchDivId = subAreaMatchDivId
											.replace(" ", "_");
									subAreasContainer
											.append("<div id='"
													+ subAreaMatchDivId
													+ "' class='areaPickerExpandAllSubAreaElement'>"
													+ subAreaMatch.label
													+ "</div>");
									addSelectableEvents(subAreaMatch,
											subAreaMatchDivId);
								}

								subAreasContainer
										.append("<div style='clear:both;'></div>");
								subAreasContainer.hide();

								// show sub areas
								addSubAreasExpandEvent(expandButton,
										matchSubAreaDivId);
							}
							addSelectableEvents(match, matchDivId);
						}
					};

					// Get the div id for a match in the expand all area
					var getExpandAllDivId = function(match) {
						var expandAllMatchDivId = match.fieldName
								+ "_expand_all_areas_" + match.uniqueId;
						return expandAllMatchDivId;
					};

					// Get the div id for a match in the expand custom list area
					var getExpandCustomDivId = function(match) {
						var expandAllMatchDivId = 
							match.fieldName	+ "_expand_custom_areas_" + match.uniqueId;
						return expandAllMatchDivId;
					};

					// Get the div id for a match in autocomplete section
					var getAutocompleteMatchDivId = function(match) {
						var matchDivId = match.fieldName + "_autocomplete_"
								+ match.uniqueId;
						matchDivId.replace(" ", "_");
						return matchDivId;
					};

					// Compare function that returns true or false
					var isMatch = function(first, second) {
						var result = false;
						//Automatch empty text, but not for numbers (zipcodes)
						if( second == undefined || second == null || second == ''){
							if( isNaN( first ) ){
								result=true;
							}
						}
						else if (first && second) {
							first = first.toLowerCase();
							second = second.toLowerCase();

							if (first.indexOf(second) == 0) {
								result = true;
							}
							else if( second == "" ){
								result = true;
						}
						}
						return result;
					};

					var getAjaxData = function(currentValue) {
						// In the future, we can get data with an AJAX call
						// In this case we assume that all data matches.
					};

					// Find the searchTerm in the internal data
					var getInternalData = function(searchTerm) {
						var results = new Array();
						findMatchesForAreaValues(searchTerm, allAreas, results);

						return results;
					};

					// Find areas where the match value contains the searchTerm
					// if the searchTerm = "San", then areas with a match
					// value of "San Jose" and "San Francisco" are appended
					// to the match results.
					var findMatchesForAreaValues = function(searchTerm, areaTypeValues, results) {

						var matches = {};

						// For each value, get the string match text
						// if it is a match with the current value
						// then we push it to the results array
						for ( var i = 0; i < areaTypeValues.length; i++) {
							var areaTypeValue = areaTypeValues[i];
							var matchValue = areaTypeValue.match;
							var matchKey = areaTypeValue.fieldName + "_"
									+ areaTypeValue.value;
							if (matches[matchKey] === undefined) {
								if (isMatch(matchValue, searchTerm)) {
									results.push(areaTypeValue);
									matches[matchKey] = areaTypeValue;
								}
								// If an area does not match the search term and
								// the area has sub areas, check the sub areas
								// to
								// see if any match the search term
								else if (areaTypeValue.subAreas != undefined) {
									var subAreas = areaTypeValue.subAreas.values;
									for ( var j = 0; j < subAreas.length; j++) {
										var subAreaType = subAreas[j];
										var subAreaMatchKey = subAreaType.fieldName
												+ "_" + subAreaType.value;
										if (matches[subAreaMatchKey] === undefined) {
											if (isMatch(subAreaType.match,
													searchTerm)) {
												subAreaType.parentLabel = areaTypeValue.label;
												results.push(subAreaType);
												matches[subAreaMatchKey] = subAreaType;
											}
										}
									}
								}
							}
						}

						results.sort(function(a, b) {
							return a.label.localeCompare(b.label);
						});
					};

					// Find an array of values that match the typed text
					var getMatches = function(currentValue) {
						var data = [];
						//only try to match 2 or more characters typed.
						if( currentValue != null && currentValue.length >= autocompleteTextLength){
						if ((typeof source) === "object") {
							data = getInternalData(currentValue);
						} else if ((typeof source) === "string") {
							data = getAjaxData(currentValue);
						}
						}
						return data;
					};

					// Build the UI to display for auto complete matched items
					var updateAutoComplete = function(matches) {
						for ( var i = 0; i < matches.length; i++) {
							var match = matches[i];
							matchDivId = getAutocompleteMatchDivId(match);
							var matchDiv = jQuery("<div/>", {
								id : matchDivId
							});
							var matchClass = "areaPickerElement";
							if (!isEnabled(match)) {
								matchClass = "areaPickerElementDisabled";
							}
							matchDiv.addClass(matchClass);
							matchDiv.addClass("areaUnselected");

							matchDiv.append(match.label);
							if (!isEnabled(match)) {
								matchDiv.append(" (None)");
							}
							if (match.parentLabel != undefined) {
								matchDiv.append("&nbsp;(" + match.parentLabel
										+ ")");
							}

							autoCompleteMatchValues.append(matchDiv);

							if (match.subAreas != undefined 
									&& match.subAreas.values != undefined
									&& match.subAreas.values.length > 0) {
								var matchSubAreaDivId = match.fieldName
										+ "_auto_complete_sub_areas_"
										+ match.uniqueId;
								var expandButton = jQuery("<span/>");
								expandButton.html(settings.expandSubAreasButton);
								var subAreasContainer = $("<div/>", {
									id : matchSubAreaDivId
								});
								var subAreasToggler = jQuery("<div/>", {
									style : "float:left"
								});
								subAreasToggler.append(expandButton);
								matchDiv.css("float", "left");
								autoCompleteMatchValues.append(subAreasToggler)
										.append(jQuery("<div/>", {
											style : "clear:both"
										}));
								matchDiv
										.addClass("areaPickerElementHasSubAreas");
								autoCompleteMatchValues
										.append(subAreasContainer);

								var subAreaData = match.subAreas.values;
								for ( var j = 0; j < subAreaData.length; j++) {
									var subAreaMatch = subAreaData[j];
									var subAreaMatchDivId = subAreaMatch.fieldName
											+ "_expand_all_"
											+ subAreaMatch.uniqueId;
									subAreaMatch.parentLabel = match.label;
									subAreaMatchDivId = subAreaMatchDivId
											.replace(" ", "_");
									var subAreaMatchDiv = jQuery(
											"<div/>",
											{
												id : subAreaMatchDivId,
												'class' : 'areaPickerAutoCompleteSubAreaElement'
											});
									subAreaMatchDiv.text(subAreaMatch.label);
									subAreasContainer.append(subAreaMatchDiv);
									if (isSelected(subAreaMatch)) {
										subAreaMatchDiv.addClass("areaSelected");
										subAreaMatchDiv.removeClass("areaUnselected");
									} else {
										addSelectableEvents(subAreaMatch,
												subAreaMatchDivId);
									}
								}

								subAreasContainer.hide();
								// show sub areas
								addSubAreasExpandEvent(expandButton,matchSubAreaDivId);
							}

							if (isSelected(match)) {
								matchDiv.removeClass("areaUnselected");
								matchDiv.addClass("areaSelected");
							} else {
								addSelectableEvents(match, matchDivId);
							}
						}
					};

					// When arrowing down or update in the autocomplete
					// sections, adjust the scroll bars
					// so that the highlighted index is always visible.
					// This is based on the selected index from autocomplete
					// matches.
					var updateAutocompleteScrollbar = function(selectedIndex,
							autocompleteElementHeight) {
						var position = selectedIndex
								* autocompleteElementHeight;
						var scrollAreaSize = autoCompleteMatchValues
								.css('height');
						if (scrollAreaSize.indexOf("px") > -1) {
							var lastIndex = scrollAreaSize.length - 2;
							scrollAreaSize = scrollAreaSize.slice(0, lastIndex);
						}
						var scrollTop = autoCompleteMatchValues.scrollTop();
						var scrollAreaBottom = scrollTop
								+ new Number(scrollAreaSize);

						if (position < scrollTop || position > scrollAreaBottom) {
							autoCompleteMatchValues.scrollTop(position);
						}
					};

					// Toggle an image for use in hiding or displaying sub areas.
					var subAreasContainerToggle = function(subAreasContainerId,
							toggleButton) {
						var subAreasContainer = jQuery("#" + subAreasContainerId);
						subAreasContainer.toggle();
						if (subAreasContainer.is(":visible")) {
							$(toggleButton).removeClass();
							$(toggleButton).html(settings.closeSubAreasButton);
						} else {
							$(toggleButton).removeClass();
							$(toggleButton).html(settings.expandSubAreasButton);
						}
					};

					// Add UI events for already selected items. For example,
					// a click will remove the item from all selected items.
					var addRemovableEvents = function(match, matchDivId) {
						var matchDiv = jQuery("#" + matchDivId);
						matchDiv.click(function() {
							removeSelectedItem(match);
						});
						matchDiv.mouseover(function() {
							matchDiv.addClass("autocompleteMouseOver")
						});
						matchDiv.mouseout(function() {
							matchDiv.removeClass("autocompleteMouseOver");
						});
					};

					// Add UI events for selectable items. For example add
					// click, mouseover and out
					// events for divs.
					var addSelectableEvents = function(match, matchDivId) {
						// Add events if either all items are enabled
						// or if the item is enabled
						if (isEnabled(match)) {
							var matchDiv = jQuery("#" + matchDivId);
							matchDiv.click(function() {
								updateSelectedItems(match);
								autoCompleteMatchValues
										.html(autocompleteMessage);
							});
							matchDiv.mouseenter(function() {
								matchDiv.addClass("autocompleteMouseOver")
							});
							matchDiv.mouseout(function() {
								matchDiv.removeClass("autocompleteMouseOver");
							});
						}
					};

					var addSubAreasExpandEvent = function(matchSubAreaToggler,
							matchSubAreaDivId) {
						matchSubAreaToggler.click(function() {
							subAreasContainerToggle(matchSubAreaDivId, this);
						});
					};

					var hideAutoCompleteMatchValues = function(setFocus) {
						hideAutoComplete();
						setDefaults(setFocus);
					};
					
					var hideAutoComplete = function(){
						autoCompleteMatchValues.parent().hide();
						selectedAutoCompleteIndex = -1;
					}

					var showAutoCompleteMatchValues = function() {
						autoCompleteMatchValues.parent().show();
						areaPickerExpandAll.hide();
					};

					var toggleAreaPickerExpandAllContainer = function() {
						hideAutoCompleteMatchValues(false);
						setDefaults(true);
						areaPickerExpandAll.toggle();
						
					};

					var removeAllItems = function() {
						for ( var i in selectedItemsCache) {
							var match = selectedItemsCache[i];
							removeSelectedItem(match);
						}

					};

					// Remove selected item from selected items div
					var removeSelectedItem = function(match) {
						selectedItemsContainer
								.find('input:hidden')
								.each(
										function(index) {
											$hidden = jQuery(this);
											if ($hidden.val() == match.value) {
												$hidden.parent().remove();
												var matchDivId = getExpandAllDivId(match);
												var matchDiv = jQuery("#"
														+ matchDivId);
												matchDiv.removeClass("areaSelected");
												matchDiv.addClass("areaUnselected");
												addSelectableEvents(match,
														matchDivId);

												var customMatchDivId = getExpandCustomDivId(match);
												var customMatchDiv = jQuery("#"
														+ customMatchDivId);
												customMatchDiv.removeClass("areaSelected");
												customMatchDiv.addClass("areaUnselected");
												addSelectableEvents(match,
														customMatchDivId);

												hideAutoCompleteMatchValues(true);
												removeFromSelectedItemsCache(match);
												setDefaults(true);
											}
										});
					};

					// Add hidden input and button to the selected items div
					var updateSelectedItems = function(match) {
						var selectedItemDiv = jQuery("<div/>");
						selectedItemDiv.addClass("ihf-one-selectedArea");
						var fieldName = match.fieldName;
						// PHP uses a different variable name convention if more
						// than one value is selected.
						if (isPhpStyle) {
							fieldName += "[]";
						}
						var selectedItemInput = jQuery("<input type='hidden' value='"
								+ match.value + "' name='" + fieldName + "'/>");

						var buttonLabel = match.label;
						if (match.parentLabel != undefined) {
							buttonLabel += "&nbsp;(" + match.parentLabel + ")";
						}
						var selectedItemButton = jQuery("<button class='btn' type='button'> &times;&nbsp;"
								+ buttonLabel + "</button>");
						selectedItemButton.click(function() {
							removeSelectedItem(match);
						});

						selectedItemDiv.append(selectedItemInput);
						selectedItemDiv.append(selectedItemButton);

						selectedItemsContainer.append(selectedItemDiv);
						hideAutoCompleteMatchValues(true);

						addToSelectedItemsCache(match);

						var matchDivId = getExpandAllDivId(match);
						var matchDiv = jQuery("#" + matchDivId);
						matchDiv.addClass("areaSelected");
						matchDiv.removeClass("areaUnselected");
						matchDiv.unbind('click');
						addRemovableEvents(match, matchDivId);

						var customMatchDivId = getExpandCustomDivId(match);
						var customMatchDiv = jQuery("#" + customMatchDivId);
						customMatchDiv.addClass("areaSelected");
						customMatchDiv.removeClass("areaUnselected");
						customMatchDiv.unbind('click');
						addRemovableEvents(match, customMatchDivId);
						setDefaults(true);
					};

					// Remember which items have been selected.
					var addToSelectedItemsCache = function(match) {
						var key = getCacheKey(match);
						selectedItemsCache[key] = match;
						//Make sure we display the selected items label
						selectedItemsLabel.show();
					};

					// Remember which items have been enabled.
					var addToEnabledItemsCache = function(match) {
						var key = getCacheKey(match);
						enabledItemsCache[key] = match;
					};

					var removeFromSelectedItemsCache = function(match) {
						var key = getCacheKey(match);
						delete selectedItemsCache[key];
						if( jQuery.isEmptyObject( selectedItemsCache)){
							//Make sure we hide the selected items label
							selectedItemsLabel.hide();
						}
					};

					var getCacheKey = function(match) {
						var key = match.fieldName + match.value;
						return key;
					};

					var isSelected = function(match) {
						var key = getCacheKey(match);
						if (selectedItemsCache[key] !== undefined) {
							return true;
						}
						return false;
					}

					var setDefaults = function( setFocus ) {
						$this.val("");
						$this.blur();
						if( setFocus ){
							$this.focus();	
						}						
					};

					var autoCompleteMatcher = function ( $this, e ){	
						
								if (e.which == ENTER_KEY) {
									// Do nothing. The keydown event handles the
									// enter key
									return;
								}

								// value to the user typed text
						var currentValue = $this.value;

								// a list of all areas that match the text
						if( currentValue.length >= autocompleteTextLength ){
							
								var matches = getMatches(currentValue);
								if (e.which == ARROW_UP_KEY) {
									if (selectedAutoCompleteIndex > 0) {
										selectedAutoCompleteIndex--;
									}
								} else if (e.which == ARROW_DOWN_KEY) {
									if (selectedAutoCompleteIndex < matches.length) {
										selectedAutoCompleteIndex++;
									}
								} else {
									// reset autocomplete to empty
									autoCompleteMatchValues.text("");
									// display autocomplete container (unhide)
									showAutoCompleteMatchValues();
									// update the UI
									updateAutoComplete(matches);
								}

								if (matches.length > 0
										&& selectedAutoCompleteIndex > matches.length) {
									selectedAutoCompleteIndex = 0;
								} else if (matches.length > 0
										&& selectedAutoCompleteIndex == -1) {
									selectedAutoCompleteIndex = 0;
								} else if (matches.length == 0) {
									selectedAutoCompleteIndex = -1;
								}

								if (selectedAutoCompleteIndex > -1) {
									var match = matches[selectedAutoCompleteIndex];
									var matchDivId = getAutocompleteMatchDivId(match);
									var matchClass = "autocompleteArrowOver";
									if (!isEnabled(match)) {
										matchClass = "autocompleteArrowOverDisabled";
									}
								jQuery("#" + matchDivId).addClass(matchClass);

									var previousIndex = selectedAutoCompleteIndex - 1;
									if (previousIndex > -1) {
										var previousMatch = matches[previousIndex];
										var previousDivId = getAutocompleteMatchDivId(previousMatch);
										var matchClass = "autocompleteArrowOver";
										if (!isEnabled(previousMatch)) {
											matchClass = "autocompleteArrowOverDisabled";
										}
										jQuery("#" + previousDivId)
												.removeClass(matchClass);
									}

									var nextIndex = selectedAutoCompleteIndex + 1;
									if (nextIndex < matches.length) {
										var nextMatch = matches[nextIndex];
										var nextDivId = getAutocompleteMatchDivId(nextMatch);
										var matchClass = "autocompleteArrowOver";
										if (!isEnabled(nextMatch)) {
											matchClass = "autocompleteArrowOverDisabled";
										}
										jQuery("#" + nextDivId).removeClass(
												matchClass);
									}

									updateAutocompleteScrollbar(
											selectedAutoCompleteIndex, 15);
								}

						}
						else{
							hideAutoComplete();	
						}
					};

					// This.onkeyup look through the source and display matches
					// in selectedItems
					// For each li created here, we add an onclick event that
					// will clear the auto selector
					// and insert a checkbox
					$this.keyup(function(e) {autoCompleteMatcher( this, e );});

					// This handles the enter event and call preventDefault
					// so that the form is NOT submitted.
					$this.keydown(function(e) {
						if (e.which == ENTER_KEY) {
							e.preventDefault();
							// value to the user typed text
							var currentValue = this.value;
							// a list of all areas that match the text
							var matches = getMatches(currentValue);
							var match = matches[selectedAutoCompleteIndex];
							if (isEnabled(match)) {
								if (match !== undefined) {
									if (!isSelected(match)) {
										updateSelectedItems(match);
									}
									hideAutoCompleteMatchValues(true);
								}
							}
						}
					});

					// Remove default text and start autocomplete mode
					$this.click(function(e) {
						$this.val("");
						$this.removeClass("areaPickerDefaultText");
						$this.addClass("areaPickerActive");
						// value to the user typed text
						var autocompleteValueDiv = jQuery("<div/>", {
							style : "padding: 2px"
						}).html(autocompleteMessage);
						autoCompleteMatchValues.html(autocompleteValueDiv);
						var currentValue = $this.val();
						autoCompleteMatcher( this, e );
					});

					// CSS class selector to open or close the container that
					// shows all areas
					jQuery(areaPickerExpandAllButtonClass).each(function() {
						jQuery(this).click(function() {
							toggleAreaPickerExpandAllContainer();
						});
					});

					// When the mouse leaves the all city container, then hide
					// it
					areaPickerExpandAll.mouseleave(function() {
						// areaPickerExpandAll.hide();
					});

					// When clicking outside of the area picker, close the area
					// picker.
					jQuery(document).click(
							function(event) {
								if (event.target.type == 'image'
										&& event.target.form
										&& event.target.nodeName == 'INPUT') {

									//This is the case where an input image for the search form was used for form submission.
									//In this case, we want to grab the value input and see if there are any matches in the
									//set of possible zips or cities.
									//If there is a match, then set it as an input value before form submission.
									var currentValue = jQuery($this).val();
									var matches = getMatches(currentValue);

									if (matches && matches.length == 1) {
										updateSelectedItems(matches[0]);
									}

									return;
								}
								
								if( !jQuery(event.target).hasClass("expand-sub-areas") && !jQuery(event.target).hasClass("close-sub-areas") ){
									
									if (jQuery(event.target).parents().index(
											outerContainer) == -1) {
										areaPickerExpandAll.hide();
										hideAutoCompleteMatchValues(false);
									}
									
								}
							});

					init();
				});
	};
})(jQuery);