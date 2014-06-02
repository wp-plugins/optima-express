//singleton infoWindow - only one window can be opened at a time
var infoWindow = null;
var streetViewService = new google.maps.StreetViewService();
var allMapListings=null;
function initializeMap(mapContainerId, mapListings, streetViewContainerId, serverUrl) { 

	//Save for future reference in callback methods,
	//if we need to use callbacks for markers.
	allMapListings=mapListings;
    if(!streetViewContainerId){
      var streetViewContainerId = null;
    }
    
    if( !serverUrl ){
      var serverUrl="";
    }
    
    var streetView = false;
    if ( streetViewContainerId != null) {
      streetView = true;
    }
    
    if( mapContainerId == null ) {
      mapContainerId = "map_canvas";
    }
    
    var mapOptions = {
      zoom: 18,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      streetViewControl: streetView,
      scrollwheel: false
    };
    
    var map = new google.maps.Map(document.getElementById(mapContainerId), mapOptions);
    
    //Request from Eric, close any open info windows, if you click anywhere on the map.
    google.maps.event.addListener(map, 'click', function(){
      closeInfoWindow();
    });
    
    
    //create map markers.
    createMapMarkers(map, mapListings,streetViewContainerId, serverUrl );
    
    //Set map bounds to display all listings.
    //Sometime createMapBounds returns null, if none of the
    //listings has a lat/lng value.  This will rarely occur
    //in production, but is a problem in development.
    createMapBounds(map, mapListings);
    
    /**
     * If we only have one listing and the street view container exists, 
     * then display the street view.
     */
    if( streetViewContainerId && streetViewContainerId != null && mapListings.length == 1){
      var oneListing=mapListings[0];
      var latlng = new google.maps.LatLng(oneListing.latitude, oneListing.longitude);
      updateStreetView( map, latlng, streetViewContainerId ); 
    }    
    
}//end initializeMap function

/**
 * Loop through the mapListings and create a new mapMarker for
 * each listing that has a lat/lon value.  If we do NOT have a lat/lng
 * value, then try to use Google's geocoder to calculate the lat/lng
 * based on the address value.
 * 
 * @param {Object} map
 * @param {Object} mapListings
 */
function createMapMarkers( map, mapListings, streetViewContainerId, serverUrl ){

  for( var i = 0; i < mapListings.length; i++){
    var oneListing=mapListings[i];
    if( i == 0 ){}
                      
    //We had some data that had default latitude longitude data set to 0 
    if( oneListing && isListingLatitudeLongitudeValid( oneListing ) ){
      var latlng = new google.maps.LatLng(oneListing.latitude, oneListing.longitude);
      if( map.getCenter()==null){
        map.setCenter( latlng );
      }
      createMapMarker( map, latlng, oneListing.mouseOverMarkerText, oneListing.message, oneListing.messageTitle, 
                       oneListing.iconId, oneListing.listMapMarkerId,streetViewContainerId, serverUrl );
    } else if ( oneListing && oneListing.address ) {
      //We need to dynamically geocode

      var geocoder = new google.maps.Geocoder();
      var markerCallback = createMarkerCallback( map, oneListing.message, oneListing.messageTitle, oneListing.mouseOverMarkerText, oneListing.iconId, 
                                                 oneListing.listMapMarkerId, streetViewContainerId, serverUrl  );
      geocoder.geocode({address: oneListing.address},  markerCallback ) ;      
    }
  } 
}

/**
 * Loop through the mapListings and dynamically create a bounds that
 * includes all the listings' lat/lon values.
 * 
 * @param {Object} mapListings
 */
function createMapBounds(map, mapListings){
  //While looping through the map markers, create
  //and generate the map bounds that will display 
  //all of the map markers.
  var mapBounds = null;

  for (var i = 0; i < mapListings.length; i++) {
    var oneListing = mapListings[i];
    if (oneListing && isListingLatitudeLongitudeValid( oneListing )) {
      var latlng = new google.maps.LatLng(oneListing.latitude, oneListing.longitude);
      if (mapBounds == null) {
       mapBounds = new google.maps.LatLngBounds(latlng, latlng);
      }
      else if (!mapBounds.contains(latlng)) {
        mapBounds.extend(latlng);
      }
    }
  }
 
  // Reset zoom if only one listing
  if (  mapListings.length == 1 ) {
    //mapBounds = null;
    map.setZoom(15);
  }
  else if (mapBounds != null) {
    map.fitBounds(mapBounds);
  }
  
  
  return mapBounds ;
}

/**
 *  Called from the markerCallback to adjust the bounds of the map
 *  based on results returned from Google's geocoder.  This only happens
 *  if we do not have the lat/lon for a listing and we need to geocode
 *  on the fly.
 *  
 * @param {Object} map
 * @param {Object} latlng
 */
function adjustMapBounds( map, latlng ){
  var mapBounds = map.getBounds();
  if ( mapBounds == null ) {
    mapBounds = new google.maps.LatLngBounds(latlng, latlng);
    map.fitBounds( mapBounds );
  }
  else if( !mapBounds.contains( latlng) ) {
    mapBounds.extend( latlng );
    map.fitBounds( mapBounds );
  }
  
  
  // Reset zoom if only one listing
  if ( (typeof allMapListings != null ) && allMapListings.length == 1 ){
	  map.setZoom(15);  
  }
}

function createMarkerCallback( map, message, title, mouseOverMarkerText, markerNumber, listMapMarkerId, streetViewContainerId, serverUrl ){
  /**
   *  Callback passed to Google's geocoder.  This only happens
   *  if we do not have the lat/lon for a listing and we need to geocode
   *  on the fly.
   * @param {Object} results
   * @param {Object} status
   */
  var markerCallback = function ( results, status ){

    if (status == google.maps.GeocoderStatus.OK) {
      var latlng = results[0].geometry.location ;
      var address = results[0].formatted_address ;     
      if( address ){
        createMapMarker( map, latlng, mouseOverMarkerText, message, title, markerNumber, listMapMarkerId, streetViewContainerId, serverUrl );  
        adjustMapBounds( map, latlng );
      }
    }   
  }
  
  return markerCallback ;
}

function createMapMarker(map, latlng, mouseOverMarkerText, message, messageTitle, imageNumber, listMapMarkerId, streetViewContainerId, serverUrl){
   
  var markerId="ihf-map-marker-" + imageNumber ;

  var marker = new IhfMapMarker({
	  latlng: latlng,
      map: map,
      content: '<div id="' + markerId + '" class="ihf-map-icon">' + imageNumber + '</div>'
  });
  
  /**
  var marker = new google.maps.Marker({ 
        position: latlng,
      map: map
      });
  */
      

  if(mouseOverMarkerText && mouseOverMarkerText != null ) {
    var markerToolTip = new IhfMarkerToolTip({
      marker: marker,
      content: mouseOverMarkerText
    });   
  }
        
  
  //If we don't have a message for the marker, then we don't 
  //create an InfoWindow for the marker.
  if( message && message != null ){
	google.maps.event.addListener(marker, 'click', function(e) {
		openInfoWindow(latlng, map, message,messageTitle, serverUrl);  
    });
  
    //Map marker icon that displays with the property details in the results list.  
    //Does not display in the map itself.   
    if( listMapMarkerId != null ){
      var listMapMarker =  document.getElementById( listMapMarkerId );        
      if( listMapMarker != null ){
        google.maps.event.addDomListener(listMapMarker, 'click', function() {
            openInfoWindow(latlng, map, message,messageTitle, serverUrl)});   
      }
    }   
  }
  }

/**
 * Update the street view container to display the view of the latitude/longitude
 * 
 * @param {Object} latlng
 * @param {Object} streetViewContainer
 */
function updateStreetView(map, latlng, streetViewContainerId){
  var streetViewCallback = createStreetviewCallback(map, streetViewContainerId ) ;    
  streetViewService.getPanoramaByLocation( latlng, 50, streetViewCallback);   
}

/**
 * Create a callback to update the street view
 * This is a closure that has references to the map and the streetViewContainerId
 * @param {Object} map
 * @param {Object} streetViewContainerId
 */
function createStreetviewCallback( map, streetViewContainerId ){
  /**
   *  Callback passed to Street view service
   * @param {Object} results
   * @param {Object} status
   */
  var streetViewCallback = function ( data, status ){
    if (status == google.maps.StreetViewStatus.OK) {
      
      var latlng = data.location.latLng ;
       map.setCenter(latlng);    
      var markerPanoId = data.location.pano;
      var streetViewContainer = document.getElementById(streetViewContainerId);
            
      var streetView = new google.maps.StreetViewPanorama(streetViewContainer );
      streetView.setPano(markerPanoId);
      map.setStreetView(streetView);
      streetView.setVisible(true);
      jQuery(streetViewContainerId).show();
      streetView.addressControl=false;
    }   
    else{
    	jQuery(streetViewContainerId).hide();
    }
  } 
  
  return streetViewCallback ;
}

function closeInfoWindow() {
  if( infoWindow && infoWindow != null){
    infoWindow.remove();
    infoWindow = null ;
  } 
}

function openInfoWindow(latlng, map, message, messageTitle, serverUrl ){
  
  //There can only be one infoWindow open
  //This is a singleton global variable.
  closeInfoWindow();
  var myOptions = {
	map: map,
	latlng: latlng,
	message: message,
	messageTitle: messageTitle,
    content: '',
    pixelOffset: new google.maps.Size(-140, 0),
    boxStyle: {
      //background: "url(" + serverUrl + "/idx/images/mapinfowindow/tipbox2.png) no-repeat",
      width: "395px",
      height: "175px"
    },
    closeBoxMargin: "13px 10px 2px 2px",
    closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif"
  };    
  
  infoWindow = new IhfInfoBox(myOptions);
}

/**
 * Return true, if oneListing has reasonable latitude and longitude values.
 * @param {Object} oneListing
 */
function isListingLatitudeLongitudeValid( oneListing ){
  var result = true;

  if( oneListing == null ){
   result = false;  
  }
  else if ( !oneListing.latitude || !oneListing.longitude  ){
    result = false;
  } 
  else if ( oneListing.latitude == null || oneListing.longitude == null ){
    result = false;
  }
  else if( oneListing.latitude == 0 || oneListing.longitude == 0 ){
    result = false;
  }
  else if(oneListing.latitude == "" || oneListing.longitude == ""){
    result = false;
  }
  
  return result;
}

function IhfMarkerToolTip( opts ){
  this.marker=opts.marker;
  this.map=this.marker.get('map');
  this.content=opts.content ;
  //This fires the add event, which calls onAdd()
  this.setMap( this.map );
};

IhfMarkerToolTip.prototype = new google.maps.OverlayView();

IhfMarkerToolTip.prototype.hide = function(){
  if (this.contentContainer != null) {
    this.contentContainer.style.visibility = "hidden";
  }
}

IhfMarkerToolTip.prototype.show = function(){
  if (this.contentContainer != null) {
    this.contentContainer.style.visibility = "visible";
  }
}

// create a constructor
IhfMarkerToolTip.prototype.onAdd = function Tooltip(options) {
  var div = document.createElement('DIV');
  div.style.position = "absolute";
  div.style.display="block";
  div.style.visibility = "hidden";
  div.className="ihfMarkerTooltip";
  div.innerHTML=this.content ;
  
  var self=this;
  google.maps.event.addListener(self.marker, 'mouseover', function() { 
    self.show();
  });   
  google.maps.event.addListener(self.marker, 'mouseout', function(){
    self.hide();
  });     

  this.contentContainer=div;
  var panes = this.getPanes();
  panes.floatPane.appendChild(div);
}

IhfMarkerToolTip.prototype.onRemove = function() { 
  this.contentContainer.parentNode.removeChild(this.contentContainer); 
  this.contentContainer = null; 
}
    
IhfMarkerToolTip.prototype.draw = function() { 
  var overlayProjection  = this.getProjection(); 
  var ne = overlayProjection.fromLatLngToDivPixel(this.marker.getPosition());
  // Position the DIV.
  var div=this.contentContainer;
  div.style.left = ne.x + 'px';
  div.style.top = ne.y + 'px';
}


