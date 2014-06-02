/**
 * An IhfMapMarker 
 * @param {GLatLng} latlng Point to place bar at
 * @param {Map} map The map on which to display this IhfMapMarker.
 * @param {Object} opts Passes configuration options - content,
 *   offsetVertical, offsetHorizontal, className, height, width
 */
function IhfMapMarker(opts) {
  //debugger;
 // google.maps.OverlayView.call(this);
  this.latlng = opts.latlng;
  this.map = opts.map;
  this.content = opts.content; 
  
  this.offsetHorizontal=2;
  this.offsetVertical=0;
//  this.width=24;
 // this.height=24;
  
  
//  this.boundsChangedListener =
//	  google.maps.event.addListener(this.map, "bounds_changed", function() {
//		  
//	      return this.panMap();
//	  });
	 
  
  // Once the properties of this OverlayView are initialized, set its map so
  // that we can display it. This will trigger calls to panes_changed and draw.
  this.setMap(opts.map);
}
 
/** 
 * IhfMapMarker extends OverlayView class from the Google Maps API
 */
IhfMapMarker.prototype = new google.maps.OverlayView();
 
IhfMapMarker.prototype.remove = function(){
	this.setMap( null );
}; 

/* Creates the DIV representing this IhfMapMarker
 */
IhfMapMarker.prototype.onRemove = function() {
  this.div.parentNode.removeChild(this.div);
	this.div=null;
};

IhfMapMarker.prototype.getMapInfoBox= function(){
	return this.mapInfoBox;
};

IhfMapMarker.prototype.getPosition = function(){
	return this.latlng;
}; 
/*
 * This function has been added to work with markerclusterer
 * for map search
 * Do not remove
 */
IhfMapMarker.prototype.getDraggable = function(){
	return false;
};
 
/* Redraw the Bar based on the current projection and zoom level
 */
IhfMapMarker.prototype.draw = function() {
  if (!this.div) return;
 
  // Calculate the DIV coordinates of two opposite corners of our bounds to
  // get the size and position of our Bar
  var pixPosition = this.getProjection().fromLatLngToDivPixel(this.latlng);
  if (!pixPosition) return;
 
  // Now position our DIV based on the DIV coordinates of our bounds
  this.div.style.width = this.width + "px";
  this.div.style.left = (pixPosition.x + this.offsetHorizontal) + "px";
  this.div.style.height = this.height + "px";
  this.div.style.top = (pixPosition.y + this.offsetVertical) + "px";
  this.div.style.display = 'block';
};
 
/* Creates the DIV representing this IhfMapMarker in the floatPane.  If the panes
 * object, retrieved by calling getPanes, is null, remove the element from the
 * DOM.  If the div exists, but its parent is not the floatPane, move the div
 * to the new pane.
 * Called from within draw.  Alternatively, this can be called specifically on
 * a panes_changed event.
 */
IhfMapMarker.prototype.onAdd = function() {
  var panes = this.getPanes();
  var div = this.div;
  if (!div) {
    // This does not handle changing panes.  You can set the map to be null and
    // then reset the map to move the div.
    div = this.div = document.createElement("div");
    div.style.position = "absolute";
  //  div.style = "height: 40px; width: 40px;position:absolute";

		
    var contentDiv = document.createElement("div"); 
    contentDiv.innerHTML = this.content;
    div.appendChild(contentDiv);
    panes.overlayMouseTarget.appendChild(div);
	//debugger;		
	//A click on the map will close the IhfMapMarker
	//We need to catch click and double click events on the infowindow and cancel them, else the event
	//will be handled by the map and the info window will close or map will zoom (on double click)
    var me=this;
    google.maps.event.addDomListener(div, "click", function(e){ 
    	//debugger;
    	google.maps.event.trigger(me, 'click');
    	e.cancelBubble = true;
  	  	if (e.stopPropagation) e.stopPropagation();   
    	
    });
//    google.maps.event.addDomListener(div, "mousedown", function (e) {
//    	google.maps.event.trigger(me, 'click');
//    	e.cancelBubble = true;
//  	  	if (e.stopPropagation) e.stopPropagation(); 
//      });
    google.maps.event.addDomListener(div, 'dblclick', function(e){ 
    	e.cancelBubble = true;
  	  	if (e.stopPropagation) e.stopPropagation();       	
    });
 
   //  debugger;
   // this.panMap();
  } else if (div.parentNode != panes.overlayMouseTarget) {
    // The panes have changed.  Move the div.
    div.parentNode.removeChild(div);
    panes.overlayMouseTarget.appendChild(div);
  } else {
    // The panes have not changed, so no need to create or move the div.
  }
	if( this.afterAdd != null ){
		this.afterAdd();
	}	
};
 
/* Pan the map to fit the IhfMapMarker.
 */
IhfMapMarker.prototype.panMap = function() {
  // if we go beyond map, pan map
  var map = this.map;
  var bounds = map.getBounds();
  if (!bounds) return;
 
  // The position of the infowindow
  var position = this.latlng;
 
  // The dimension of the infowindow
  var iwWidth = this.width;
  var iwHeight = this.height;
 
  // The offset position of the infowindow
  var iwOffsetX = this.offsetHorizontal;
  var iwOffsetY = this.offsetVertical;
 
  // Padding on the infowindow
  var padX = 40;
  var padY = 40;
 
  // The degrees per pixel
  var mapDiv = map.getDiv();
  var mapWidth = mapDiv.offsetWidth;
  var mapHeight = mapDiv.offsetHeight;
  var boundsSpan = bounds.toSpan();
  var longSpan = boundsSpan.lng();
  var latSpan = boundsSpan.lat();
  var degPixelX = longSpan / mapWidth;
  var degPixelY = latSpan / mapHeight;
 
  // The bounds of the map
  var mapWestLng = bounds.getSouthWest().lng();
  var mapEastLng = bounds.getNorthEast().lng();
  var mapNorthLat = bounds.getNorthEast().lat();
  var mapSouthLat = bounds.getSouthWest().lat();
 
  // The bounds of the infowindow
  var iwWestLng = position.lng() + (iwOffsetX - padX) * degPixelX;
  var iwEastLng = position.lng() + (iwOffsetX + iwWidth + padX) * degPixelX;
  var iwNorthLat = position.lat() - (iwOffsetY - padY) * degPixelY;
  var iwSouthLat = position.lat() - (iwOffsetY + iwHeight + padY) * degPixelY;
 
  // calculate center shift
  var shiftLng =
      (iwWestLng < mapWestLng ? mapWestLng - iwWestLng : 0) +
      (iwEastLng > mapEastLng ? mapEastLng - iwEastLng : 0);
  var shiftLat =
      (iwNorthLat > mapNorthLat ? mapNorthLat - iwNorthLat : 0) +
      (iwSouthLat < mapSouthLat ? mapSouthLat - iwSouthLat : 0);
 
  // The center of the map
  var center = map.getCenter();
 
  // The new map center
  var centerX = center.lng() - shiftLng;
  var centerY = center.lat() - shiftLat;
 
  // center the map to the new shifted center
  map.setCenter(new google.maps.LatLng(centerY, centerX));
 
  // Remove the listener after panning is complete.
  google.maps.event.removeListener(this.boundsChangedListener);
  this.boundsChangedListener = null;
};			
