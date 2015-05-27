tinyMCEPopup.requireLangPack();

var IhfGalleryDialog = {
	init: function() {
	},
	insertFeaturedListings: function(theForm, shortcode) {
		var parameters = {
			sortBy: this.getFieldValue(theForm.sortBy),
			displayType: this.getFieldValue(theForm.displayType),
			resultsPerPage: this.getFieldValue(theForm.resultsPerPage),
			header: this.getFieldValue(theForm.header),
			includeMap: this.getFieldValue(theForm.includeMap)
		};
		this.buildShortcode(shortcode, parameters);
	},
	insertToppicks: function(theForm, shortcode) {
		var parameters = {
			id: this.getFieldValue(theForm.toppickId),
			sortBy: this.getFieldValue(theForm.sortBy),
			displayType: this.getFieldValue(theForm.displayType),
			resultsPerPage: this.getFieldValue(theForm.resultsPerPage),
			header: this.getFieldValue(theForm.header),
			includeMap: this.getFieldValue(theForm.includeMap)
		};
		this.buildShortcode(shortcode, parameters);
	},
	insertSearchResults: function(theForm, shortcode) {
		var parameters = {
			cityId: this.getFieldValue(theForm.cityId),
			propertyType: this.getFieldValue(theForm.propertyType),
			bed: this.getFieldValue(theForm.bed),
			bath: this.getFieldValue(theForm.bath),
			minPrice: this.getFieldValue(theForm.minPrice),
			maxPrice: this.getFieldValue(theForm.maxPrice),
			sortBy: this.getFieldValue(theForm.sortBy),
			displayType: this.getFieldValue(theForm.displayType),
			resultsPerPage: this.getFieldValue(theForm.resultsPerPage),
			header: this.getFieldValue(theForm.header),
			includeMap: this.getFieldValue(theForm.includeMap)
		};
		this.buildShortcode(shortcode, parameters);
	},
	insertListingGallery: function(theForm, shortcode) {
		var parameters = {};
		parameters["id"] = this.getFieldValue(theForm.toppickId);
		if(theForm.fitToWidth == undefined || theForm.fitToWidth.checked == false) {
			parameters["width"] = this.getFieldValue(theForm.width);
		}
		parameters["height"] = this.getFieldValue(theForm.height);
		parameters["rows"] = this.getFieldValue(theForm.rows);
		parameters["columns"] = this.getFieldValue(theForm.columns);
		parameters["effect"] = this.getFieldValue(theForm.effect);
		parameters["auto"] = this.getFieldValue(theForm.auto);
		parameters["maxResults"] = this.getFieldValue(theForm.maxResults);
		this.buildShortcode(shortcode, parameters);
	},
	insertQuickSearch: function(theForm, shortcode) {
		var parameters = {
			style: this.getFieldValue(theForm.style),
			showPropertyType: this.getFieldValue(theForm.showPropertyType)
		};
		this.buildShortcode(shortcode, parameters);
	},
	insertSearchByAddress: function(theForm, shortcode) {
		var parameters = {
			style: this.getFieldValue(theForm.style)
		};
		this.buildShortcode(shortcode, parameters);
	},
	insertSearchByListingId: function(theForm, shortcode) {
		this.buildShortcode(shortcode);
	},
	insertBasicSearch: function(theForm, shortcode) {
		this.buildShortcode(shortcode);
	},
	insertAdvancedSearch: function(theForm, shortcode) {
		this.buildShortcode(shortcode);
	},
	insertOrganizerLogin: function(theForm, shortcode) {
		this.buildShortcode(shortcode);
	},
	insertValuationForm: function(theForm, shortcode) {
		this.buildShortcode(shortcode);
	},
	insertMapSearch: function(theForm, shortcode) {
		var parameters = {};
		if(theForm.fitToWidth == undefined || theForm.fitToWidth.checked == false) {
			parameters["width"] = this.getFieldValue(theForm.width);
		}
		parameters["height"] = this.getFieldValue(theForm.height);
		parameters["centerlat"] = this.getFieldValue(theForm.centerlat);
		parameters["centerlong"] = this.getFieldValue(theForm.centerlong);
		parameters["address"] = this.getFieldValue(theForm.address);
		parameters["zoom"] = this.getFieldValue(theForm.zoom);
		this.buildShortcode(shortcode, parameters);
	},
	insertAgentDetail: function(theForm, shortcode) {
		var parameters = {
			agentId: this.getFieldValue(theForm.agentId)
		};
		this.buildShortcode(shortcode, parameters);
	},
	insertAgentListings: function(theForm, shortcode) {
		var parameters = {
			agentId: this.getFieldValue(theForm.agentId)
		};
		this.buildShortcode(shortcode, parameters);
	},
	insertOfficeListings: function(theForm, shortcode) {
		var parameters = {
			officeId: this.getFieldValue(theForm.officeId)
		};
		this.buildShortcode(shortcode, parameters);
	},
	buildShortcode: function(shortcode, parameters) {
		var result = "[" + shortcode;
		if(parameters) {
			for(var key in parameters) {
				var value = parameters[key];
				if(value != undefined && value != null && value.length != 0) {
					result += " " + key + "=\"" + value + "\"";
				}
			}
		}
		result += "]";
		tinyMCEPopup.editor.execCommand("mceInsertContent", false, result);
		tinyMCEPopup.close();
	},
	getFieldValue: function(formField) {
		var value = null;
		if(formField != undefined) {
			if(formField.type == "checkbox") {
				value = formField.checked;
			} else {
				value = formField.value;
			}
		}
		if (value === undefined || value === null || value.length === 0) {
			return null;
		} else {
			return value;
		}
	},
	validateForm: function(theForm) {
		result = true;
		jQuery(theForm).find("input,select,textarea").each(function() {
			var field = jQuery(this);
			field.parent().removeClass("has-error");
			if(field.attr("required") && field.val() == "") {
				field.parent().addClass("has-error");
				result = false;
			}
		});
		return result;
	}
}

tinyMCEPopup.onInit.add(IhfGalleryDialog.init, IhfGalleryDialog);
