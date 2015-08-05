var IhfEventManager = function() {
};

IhfEventManager.prototype._events = {};

IhfEventManager.prototype._setEvent = function(name, callback) {
	var events = this._events[name] || [];
	events.push(callback);
	this._events[name] = events;
};

IhfEventManager.prototype._getEvent = function(name) {
	return this._events[name];
};

IhfEventManager.prototype._toArray = function(values) {
	values = values || [];
	if(!Array.isArray(values)) {
		values = values.split(/[ ,]+/);
	}
	return values;
};

IhfEventManager.prototype._each = function(values, callback) {
	values = this._toArray(values);
	for(var index = 0; index < values.length; index++) {
		var value = values[index];
		callback(value, index);
	}
};

IhfEventManager.prototype.on = function(eventNames, callback) {
	var self = this;
	self._each(eventNames, function(eventName) {
		//console.log("bound: " + eventName);
		self._setEvent(eventName, callback);
	});
	return self;
};

IhfEventManager.prototype.trigger = function(eventNames, data) {
	var self = this;
	data = data || {};
	self._each(eventNames, function(eventName) {
		var callbacks = self._getEvent(eventName);
		self._each(callbacks, function(callback) {
			//console.log("triggering: " + eventName);
			data.eventName = eventName;
			callback(data);
		});
	});
	return self;
};

var ihfEventManager = new IhfEventManager();

jQuery(document).on("ready DOMSubtreeModified propertychange", function() {
	var bindEvent = function($element, element, eventName) {
		var eventType = eventName.split("-").pop();
		if(eventType === "loaded") {
			ihfEventManager.trigger(eventName, {
				$element: $element,
				element: element
			});
		} else {
			$element.on(eventType, function(event) {
				ihfEventManager.trigger(eventName, {
					event: event,
					$element: $element,
					element: element
				});
			});
		}
	};
	var bindEvents = function() {
		jQuery("#ihf-main-container [data-ihf-event]:not([data-ihf-event-bound]").each(function() {
			var element = this;
			var $element = jQuery(element);
			var eventNames = $element.attr("data-ihf-event");
			$element.attr("data-ihf-event-bound", true);
			ihfEventManager._each(eventNames, function(eventName) {
				bindEvent($element, element, eventName);
			});
		});
	};
	bindEvents();
});