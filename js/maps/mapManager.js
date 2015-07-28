var MapManager = function( containerId ) {
	
	this.DEBUG = false;
	//this.MAPBOX_ACCESS_TOKEN = 'pk.eyJ1IjoiaWhvbWVmaW5kZXIiLCJhIjoiR1JFZzQyYyJ9.0g0cCGrEpv3B4zuYl2lBGw';
	this.MAPBOX_ID_ROADMAP = 'ihomefinder.k3f0npic';
	this.MAPBOX_ID_SATELLITE = 'ihomefinder.k3f132cj';
	this.MAPBOX_ID_HYBRID = 'ihomefinder.k767gpl3';
	this.MAPBOX_ID_TERRAIN = 'ihomefinder.k3f11f54';
	this.MAPQUEST_KEY = 'Gmjtd%7Cluurn96bng%2Cb5%3Do5-lrba1';
	this.MAPBOX_ACCESS_TOKEN;
	
	this.containerId;
	this.markerAjaxUrl;
	this.detailAjaxUrl;
	
	this.map;
	this.clusterMarkers = false;
	this.markerLayer = null;
	this.cachedMarkers;
	this.cachedAreas;
	this.ajaxRequest;
	
	/**
	 * debug helper
	 * @returns void
	 */
	this.debug = function( message ) {
		if( this.DEBUG ) {
			console.log( message );
		}
	};
	
	/**
	 * 
	 */
	this.createMap = function( defaultMapType, allowMapTypeSwitching ) {
		
		var mapUrl = '//api.tiles.mapbox.com/v4/{mapboxId}/{z}/{x}/{y}.png?access_token=' + this.MAPBOX_ACCESS_TOKEN;
		var mapAttribution ='<a href="https://www.mapbox.com/about/maps/" target="_blank">&copy; Mapbox</a> <a href="http://www.openstreetmap.org/copyright" target="_blank">&copy; OpenStreetMap</a> <a href="http://developer.mapquest.com/web/info/terms-of-use" target="_blank">&copy; MapQuest</a>';
		
		var roadmapLayer = new L.TileLayer( mapUrl, {mapboxId: this.MAPBOX_ID_ROADMAP, attribution: mapAttribution, maxZoom: 18} );
		var satelliteLayer = new L.TileLayer( mapUrl, {mapboxId: this.MAPBOX_ID_SATELLITE, attribution: mapAttribution, maxZoom: 18} );
		var hybridLayer = new L.TileLayer( mapUrl, {mapboxId: this.MAPBOX_ID_HYBRID, attribution: mapAttribution, maxZoom: 18} );
		var terrainLayer = new L.TileLayer( mapUrl, {mapboxId: this.MAPBOX_ID_TERRAIN, attribution: mapAttribution, maxZoom: 18} );
		
		switch( defaultMapType ) {
			case 'G_NORMAL_MAP':
			case 'ROADMAP':
			default:
				defaultLayer = roadmapLayer;
				break;
			case 'G_SATELLITE_MAP':
			case 'SATELLITE':
				defaultLayer = satelliteLayer;
				break;
			case 'G_HYBRID_MAP':
			case 'HYBRID':
				defaultLayer = hybridLayer;
				break;
			case 'G_PHYSICAL_MAP':
			case 'TERRAIN':
				defaultLayer = terrainLayer;
				break;
		}
		
		this.map = new L.Map(
			this.containerId,
			{
				scrollWheelZoom: false,
				touchZoom: false,
				layers: [defaultLayer]
			}
		);
		
		this.map.attributionControl.setPrefix( '' );
		
		if( allowMapTypeSwitching ) {
			var baseMaps = {
				'Map': roadmapLayer,
				'Satellite': satelliteLayer,
				'Hybrid': hybridLayer,
				'Terrain': terrainLayer
			};
			L.control.layers( baseMaps ).addTo( this.map );
		}
		
	};
	
	/**
	 * @returns void
	 */
	this.centerMap = function( latLng, zoom ) {
		if( zoom === null ) {
			this.map.setView( latLng );
		} else {
			this.map.setView( latLng, zoom );
		}
	};
	
	/**
	 * fit the map to an array of LatLng
	 * if only one LatLng, then only zoom to 15
	 */
	this.fitBounds = function( markersLatLng ) {
		if( markersLatLng.length === 1 ) {
			this.map.setView( markersLatLng[0], 15 );
		} else {
			this.map.fitBounds( markersLatLng, {padding: [25, 25]} );
		}
	};
	
	/**
	 * 
	 */
	this.centerMapToMarker = function( marker ) {
		var latLng = marker.getLatLng();
		this.map.setView( latLng );
		
		if( marker.getPopup()._isOpen ) {
			this.map.panBy( [0, -75] );
		}
	};
	
	/**
	 * removes previously cached areas and creates new array
	 */
	this.resetCachedAreas = function() {
		delete this.cachedAreas;
		this.cachedAreas = [];
	};
	
	/**
	 * checks if an area has been added to the array
	 */
	this.isCachedArea = function() {
		return false;
		//always return false because we're not using this
		//because it causes zoom and pan position to no be saved
		//because no ajax request is made to ihf server
		for( var index in this.cachedAreas ) {
			var cachedBounds = this.cachedAreas[index];
			var currentBounds = this.map.getBounds();
			if( cachedBounds.contains( currentBounds ) ) {
				return true;
			}
		}
		return false;
	};
	
	/**
	 * cache an area
	 */
	this.setCachedArea = function() {
		this.cachedAreas.push( this.map.getBounds() );
	};
	
	/**
	 * removes previously cached markers and creates new array
	 */
	 this.resetMarkerCache = function() {
		delete this.cachedMarkers;
		this.cachedMarkers = [];
	 };
	
	/**
	 * checks if a marker has been added to the array
	 */
	this.isCachedMarker = function( boardId, listingId ) {
		if( this.cachedMarkers[boardId + '-' + listingId] === undefined ) {
			return false;
		} else {
			return true;
		}
	};
	
	/**
	 * cache a marker
	 */
	this.setCachedMarker = function( boardId, listingId, marker ) {
		this.cachedMarkers[boardId + '-' + listingId] = marker;
	};
	
	/**
	 * removes and creates a new map layer. this is used to clear
	 * previous markers from the map. also, reset the marker and area cache
	 */
	this.resetMarkerLayer = function() {
		if( this.markerLayer !== null ) {
			this.map.removeLayer( this.markerLayer );
			delete this.markerLayer;
		}
		this.resetMarkerCache();
		this.resetCachedAreas();
		if( this.clusterMarkers ) {
			this.markerLayer = new L.MarkerClusterGroup( {showCoverageOnHover: false, maxClusterRadius: 80} );
		} else {
			this.markerLayer = new L.LayerGroup();
		}
		this.map.addLayer( this.markerLayer );
		this.updateCountText();
	};
	
	/**
	 * abort the last ajax request
	 */
	this.abortAjaxRequest = function() {
		if( this.ajaxRequest !== undefined && this.ajaxRequest.abort() !== undefined ) {
			this.ajaxRequest.abort();
		}
	};
	
	/**
	 * updates the "XX listings found" text based on the current map bounds
	 */
	this.updateCountText = function() {
		var count = 0;
		var bounds = this.map.getBounds();
		this.markerLayer.eachLayer( function( marker) {
			if( bounds.contains( marker.getLatLng() ) ) {
				count++;
			}
		});
		jQuery( '.ihf-map-results-count' ).html( count + ' listings found' );
	};
	
	/**
	 * retrieves and updates the map with markers within the
	 * current map bounds that have not been cached
	 */
	this.updateMarkerLayer = function() {
		data = this.getMarkerRequestData();
		var self = this;
		if( !this.isCachedArea() ) {
			this.ajaxRequest = jQuery.ajax({
				type: 'GET',
				url: this.markerAjaxUrl,
				data: data,
				dataType: 'jsonp'
			})
			.done(function( maplistings ) {
				if( maplistings.data.length > 0 ) {
					for( var index in maplistings.data ) {
						var mapListing = maplistings.data[index];
						var latLng = new L.LatLng( mapListing.latitude, mapListing.longitude );
						var boardId = mapListing.boardId;
						var listingId = mapListing.listingNumber;
						var propertyType = mapListing.propertyType;
						self.addMarkerForMapSearch( latLng, boardId, listingId, propertyType );
					}
					//results are limited so don't cache the area if too many results were returned
					if( maplistings.length < 1000 ) {
						self.setCachedArea();
					}
					self.updateCountText();
				}
			});
		}
	};
	
	/**
	 * helper method to return an object of query parameter used to
	 * retrieve marker data
	 */
	this.getMarkerRequestData = function() {
		var sw = this.map.getBounds().getSouthWest();
		var ne = this.map.getBounds().getNorthEast();
		var zoom = this.map.getZoom();
		var centerLatitude = this.map.getCenter().lat;
		var centerLongitude = this.map.getCenter().lng;
		var data = {
			swlat: sw.lat,
			swlong: sw.lng,
			nelat: ne.lat,
			nelong: ne.lng,
			mapZoomLevel: zoom,
			centerLat: centerLatitude,
			centerLong: centerLongitude
		};
		//location
		if( jQuery( '#ihf-location' ).val() ) {
			data.location = jQuery( '#ihf-location' ).val();
		}
		//min price
		if( jQuery( '#ihf-minprice-homes' ).val() ) {
			data.minPrice = jQuery( '#ihf-minprice-homes' ).val();
		}
		//max price
		if( jQuery( '#ihf-maxprice-homes' ).val() ) {
			data.maxPrice = jQuery( '#ihf-maxprice-homes' ).val();
		}
		//bedrooms
		if( jQuery( '#ihf-select-bedrooms-homes' ).val() ) {
			data.bedRooms = jQuery( '#ihf-select-bedrooms-homes' ).val();
		}
		//bathrooms
		if( jQuery( '#ihf-select-baths-homes' ).val() ) {
			data.bathRooms = jQuery( '#ihf-select-baths-homes' ).val();
		}
		//propertyType
		propertyType = jQuery( 'input:checkbox[name="propertyType"]:checked' ).map(function(){
			return jQuery( this ).val();
		}).get().join( ',' );
		if( propertyType.length !== 0 ) {
			data.propertyType = propertyType;
		}
		return data;
	};
	
	/**
	 * adds a single marker onto the map
	 * we use the hasMarker and setMarker methods to not include duplicates
	 */
	this.addMarkerForMapSearch = function( latLng, boardId, listingId, propertyType ) {
		if( !this.isCachedMarker( boardId, listingId ) ) {
			// add a marker in the given location, attach some popup content to it and open the popup
			var iconHtml = this.getPropertyTypeSpecificMarkerIconHtml( propertyType );
			var size = new L.Point( 24, 24 );
			var anchor = new L.Point( 12, 30 );
			var icon = new L.DivIcon({
				html: iconHtml,
				iconSize: size,
				iconAnchor: anchor
			});
			var marker = new L.Marker( latLng, {icon: icon} );
			if( marker !== undefined ) {
				this.markerLayer.addLayer( marker );
				this.setCachedMarker( boardId, listingId, marker );
				var self = this;
				marker.on( 'click', function( event ) {
					self.openInfoWindow( this, boardId, listingId );
				});
			}
		}
	};
	
	/**
	 * 
	 */
	this.getPropertyTypeSpecificMarkerIconHtml = function( propertyType ) {
		var html;
		switch( propertyType ) {
			case 'SFR':
				html = '<div class="ihf-map-icon ihf-map-icon-house"><i class="fa fa-home"></i></div>';
				break;
			case 'CND':
				html = '<div class="ihf-map-icon ihf-map-icon-condo"><i class="glyphicon glyphicon-credit-card"></i></div>';
				break;
			case 'LL':
				html='<div class="ihf-map-icon ihf-map-icon-land"><i class="glyphicon glyphicon-tree-conifer"></i></div>';
				break;
			case 'COM':
				html = '<div class="ihf-map-icon ihf-map-icon-commercial"><i class="fa fa-tag"></i></div>';
				break;
			case 'RI':
				html = '<div class="ihf-map-icon ihf-map-icon-multiunit"><i class="glyphicon glyphicon-th-large"></i></div>';
				break;
			case 'MH':
				html = '<div class="ihf-map-icon ihf-map-icon-mobilehome"><i class="fa fa-road"></i></div>';
				break;
			case 'FRM':
				html = '<div class="ihf-map-icon ihf-map-icon-house"><i class="fa fa-leaf"></i></div>';
				break;
			case 'RNT':
				html = '<div class="ihf-map-icon ihf-map-icon-rental"><i class="fa fa-building-o"></i></div>';
				break;
			default:
				html = '<div class="ihf-map-icon ihf-map-icon-house"><i class="fa fa-home"></i></div>';
		}
		return html;
	};
	
	/**
	 * 
	 */
	this.getNumericMarkerIconHtml = function( number ) {
		return '<div class="ihf-map-icon">' + number + '</div>';
	};
	
	
	/**
	 * adds a single marker onto the map
	 */
	this.addMarkerForResultsOrDetail = function( context, latLng, popupHtml, number ) {
		// add a marker in the given location, attach some popup content to it and open the popup
		var size = new L.Point( 24, 24 );
		var anchor = new L.Point( 12, 30 );
		var iconHtml = this.getNumericMarkerIconHtml( number );
		var icon = new L.DivIcon({
			html: iconHtml,
			iconSize: size,
			iconAnchor: anchor
		});
		var marker = new L.Marker( latLng, {icon: icon} );
		this.markerLayer.addLayer( marker );
		if( context === "results" ) {
			var popup = new L.Popup().setContent( popupHtml );
			marker.bindPopup( popup, {offset: [0, -35] } );
			var self = this;
			jQuery( '[data-map-icon="' + number + '"]' ).click(function(){
				marker.togglePopup();
				self.centerMapToMarker( marker );
				jQuery('html, body').animate({
			        scrollTop: jQuery( '#' + self.containerId ).offset().top - 50
			    }, 250);
				
			});
		}
	};
	
	/**
	 * lazy loads the marker popup creation and content request
	 */
	this.openInfoWindow = function( marker, boardId, listingId ) {
		if( marker.getPopup() === undefined ) {
			var data = {
				boardId: boardId,
				listingNumber: listingId
			};
			var self = this;
			this.abortAjaxRequest();
			this.ajaxRequest = jQuery.ajax({
				type: 'GET',
				url: self.detailAjaxUrl,
				dataType: 'jsonp',
				data: data
			})
			.done(function( response ) {
				var popup = new L.Popup().setContent( response[0].content );
				marker.bindPopup( popup, {offset: [0, -35]} ).openPopup();
				self.centerMapToMarker( marker );
			});
		}
	};
	
	/**
	 * callback function to retrieve geo locations from mapquest
	 */
	 this.getGeoData = function( request, onSuccess ) {
		var searchTerm = jQuery.trim( request.term );
		var self = this;
		var url = '//www.mapquestapi.com/geocoding/v1/address?key=' + this.MAPQUEST_KEY + '&location=' + encodeURIComponent(searchTerm);
		this.abortAjaxRequest();
		this.ajaxRequest = jQuery.ajax({
			type: 'GET',
			url: url,
			dataType: 'jsonp'
		})
		.done( function( response ) {
			var mapCenterLatLng = self.map.getCenter();
			var data = [];
			for( var index in response.results[0].locations ) {
				var place = response.results[0].locations[index];
				var label = self.generateLabel( place );
				if( label !== null ) {
					data.push({
						label: label,
						value: {
							latLng: new L.LatLng( place.latLng.lat, place.latLng.lng )
						}
					});
				}
			}
			data = data.sort( function( a, b ) {
				return mapCenterLatLng.distanceTo( a.value.latLng ) - mapCenterLatLng.distanceTo( b.value.latLng );
			});
			onSuccess( data );
		});
	};
	
	/**
	 * helper method to generate label text for autosugest
	 * @return string if label can be generated
	 * @return null if there is not enough info to generate label
	 */
	this.generateLabel = function( place ) {
		var valid = true;
		var label = '';
		//street
		if( place.street.length !== 0 ) {
			label += place.street + ', ';
		}
		//neighborhood
		if( place.adminArea6.length !== 0 ) {
			label += place.adminArea6 + ', ';
		}
		//city
		if( place.adminArea5.length !== 0 ) {
			label += place.adminArea5 + ', ';
		} else {
			valid = false;
		}
		//state
		if( place.adminArea3.length !== 0 ) {
			label += place.adminArea3 + ', ';
		} else {
			valid = false;
		}
		//country
		if( place.adminArea1.length !== 0 && ( place.adminArea1 === 'US' || place.adminArea1 === 'CA' ) ) {
			label += place.adminArea1;
		} else {
			valid = false;
		}
		if( valid ) {
			return label;
		} else {
			return null;
		}
	};
	
	/**
	 * 
	 * synchronously retrieves LatLng for an address
	 * @returns LatLng object
	 */
	this.geocodeAddress = function( address, geocodingUrl, onSuccess ) {
	//	var url = '//www.mapquestapi.com/geocoding/v1/address?key=' + this.MAPQUEST_KEY + '&location=' + encodeURIComponent(address);
	//	var url ='//api.tiles.mapbox.com/v4/geocode/mapbox.places/' + encodeURIComponent(address) + '.json?access_token='+ this.MAPBOX_ACCESS_TOKEN ;
		this.abortAjaxRequest();
		var geocodingUrl = geocodingUrl + "?address=" + encodeURIComponent(address);
		this.ajaxRequest = jQuery.ajax({
			type: 'GET',
			url: geocodingUrl,
			dataType: 'jsonp'
		}).done( function( response ) {
			//lat = response.results[0].locations[0].latLng.lat;
			//lng = response.results[0].locations[0].latLng.lng;
			lat = response.features[0].geometry.coordinates[1];
			lng = response.features[0].geometry.coordinates[0];
			latLng = new L.LatLng( lat, lng );
			onSuccess( latLng );
		});
		/*this.ajaxRequest = jQuery.getJSON(url,function(response){
			lat = response.features[0].geometry.coordinates[1];
			lng = response.features[0].geometry.coordinates[0];
			latLng = new L.LatLng( lat, lng );
			onSuccess( latLng );
		});*/
	};
	
	/**
	 * @returns somewhere in Kansas
	 */
	this.getDefaultCenter = function() {
		return new L.LatLng( 39.8282, -98.5795 );
	};
	
	/**
	 * toggles the refine search form
	 */
	 this.toggleRefineForm = function() {
		jQuery( '.ihf-mapsearch-refine-overlay' ).toggle();
		jQuery( '.ihf-map-search-refine-link' ).toggle();
	 };
	
	/**
	 * 
	 */
	this.initializeMapSearch = function(
		containerId,
		zoom,
		centerAddress,
		centerLat,
		centerLng,
		defaultMapType,
		markerAjaxUrl,
		detailAjaxUrl,
		geocodingUrl,
		mapBoxToken
	) {
		this.containerId = containerId;
		this.markerAjaxUrl = markerAjaxUrl;
		this.detailAjaxUrl = detailAjaxUrl;
		this.MAPBOX_ACCESS_TOKEN = mapBoxToken;
		var geocodingUrl = geocodingUrl;
		this.createMap( defaultMapType );
		
		var self = this;
		var callback = function( latLng ) {
			self.centerMap( latLng, zoom );
			self.clusterMarkers = true;
			self.resetMarkerLayer();
			self.updateMarkerLayer( markerAjaxUrl );
			//bind map event
			self.map.on( 'moveend', function() {
				self.updateCountText();
				self.updateMarkerLayer();
			});
			//bind search box event
			jQuery( '#ihf-location' ).autocomplete({
				source: function( request, callback ) {
					self.getGeoData( request, callback );
				},
				select: function( event, ui ) {
					event.preventDefault();
					jQuery( '#ihf-location' ).val( ui.item.label );
					self.centerMap( ui.item.value.latLng );
				},
				focus: function (event, ui) {
					event.preventDefault();
				}
			});
			//bind refine open / close button event
			jQuery( '.ihf-map-search-refine-link, #ihf-refine-search-close' ).click(function(){
				self.toggleRefineForm();
			});
			//bind refine submit event
			jQuery( '#ihf-main-search-form-submit' ).click(function(){
				self.toggleRefineForm();
				self.resetMarkerLayer();
				self.updateMarkerLayer();
			});
		};
		var latLng;
		if( centerLat.length !== 0 && centerLng.length !== 0 && centerLat !== 0 && centerLng !== 0 ) {
			latLng = new L.latLng( centerLat, centerLng );
			callback( latLng );
		} else if( centerAddress.length !== 0 ) {
			this.geocodeAddress( centerAddress, geocodingUrl, callback );
		} else {
			latLng = this.getDefaultCenter();
			callback( latLng );
		}
	};
	
	/**
	 * shared method for results and detail. currently detail map
	 * is the same as results map, except that click map icon does not
	 * show popup
	 */
	this.initializeResultsOrDetailMap = function(
		containerId,
		listings,
		context,
		mapBoxToken,
		geocodingUrl
	) {
		if(!listings.length > 0) {
			return;
		}
		this.containerId = containerId;
		this.MAPBOX_ACCESS_TOKEN = mapBoxToken;
		var self = this;
		var geocodingUrl = geocodingUrl;
		var getInvalidListing = function() {
			for( var index in listings ) {
				var listing = listings[index];
				if(
					listing.latitude.length === 0 ||
					listing.longitude.length === 0 ||
					listing.latitude === 0 ||
					listing.longitude === 0
				) {
					return listing;
				}
			}
			return null;
		};
		var tryToLoadMap = function() {
			var listing = getInvalidListing();
			if( listing !== null ) {
				self.debug( 'listing #' + listing.number + ': invalid lat lng' );
				self.geocodeAddress( listing.address, geocodingUrl, function( latLng ) {
					self.debug( 'listing #' + listing.number + ': updating listing lat lng' );
					listing.latitude = latLng.lat;
					listing.longitude = latLng.lng;
					tryToLoadMap();
				});
			} else {
				//create a map
				self.createMap( 'ROADMAP', true );
				//iterate each listing and add it to a markerBounds array
				markersLatLng = [];
				for( var index in listings ) {
					var listing = listings[index];
					var latLng = new L.LatLng( listing.latitude, listing.longitude );
					markersLatLng.push( latLng );
				}
				//fit the map to the marker bounds
				self.fitBounds( markersLatLng );
				//reset the marker layer
				self.resetMarkerLayer();
				//iterate each listing and add a marker on the map
				for( var index in listings ) {
					var listing = listings[index];
					self.debug( 'listing #' + listing.number + ': adding marker' );
					var latLng = new L.LatLng( listing.latitude, listing.longitude );
					self.addMarkerForResultsOrDetail( context, latLng, listing.message, listing.number );
				}
			}
		};
		tryToLoadMap();
	};
	
	/**
	 * 
	 */
	this.initializeResultsMap = function(
		containerId,
		listings,
		mapBoxToken,
		geocodingUrl
	) {
		var context = "results";
		this.initializeResultsOrDetailMap( containerId, listings, context, mapBoxToken, geocodingUrl);
	};
	
	/**
	 * currently detail map is the same as results map
	 */
	this.initializeDetailMap = function(
		containerId,
		listings,
		mapBoxToken,
		geocodingUrl
	) {
		var context = "detail";
		this.initializeResultsOrDetailMap( containerId, listings, context, mapBoxToken, geocodingUrl);
	};
	
};