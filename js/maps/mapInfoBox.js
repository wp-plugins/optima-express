/**
 * An IhfInfoBox is like an info window, but it displays
 * under the marker, opens quicker, and has flexible styling.
 * @param {GLatLng} latlng Point to place bar at
 * @param {Map} map The map on which to display this IhfInfoBox.
 * @param {Object} opts Passes configuration options - content,
 *   offsetVertical, offsetHorizontal, className
 */
function IhfInfoBox(opts) {
  google.maps.OverlayView.call(this);
  this.latlng = opts.latlng;
  this.map = opts.map;
  
  this.offsetVertical = -100;
  this.offsetHorizontal = 40;
  this.width=330;
  this.height=140;
  

  this.message = opts.message;
  this.messageTitle = opts.messageTitle;
  this.baseUrl=opts.baseUrl;
  
  if( opts.afterAdd != null ){
	  this.afterAdd=opts.afterAdd ;	
  }
	
 
  this.boundsChangedListener =
  google.maps.event.addListener(this.map, "bounds_changed", function() {
      return this.panMap();
  });
 
  // Once the properties of this OverlayView are initialized, set its map so
  // that we can display it.  This will trigger calls to panes_changed and draw.
  this.setMap(this.map);
}
 
/** 
 * IhfInfoBox extends GOverlay class from the Google Maps API
 */
IhfInfoBox.prototype = new google.maps.OverlayView();
 
IhfInfoBox.prototype.remove = function(){
	this.setMap( null );
} 

/* Creates the DIV representing this IhfInfoBox
 */
IhfInfoBox.prototype.onRemove = function() {
  this.div.parentNode.removeChild(this.div);
	this.div=null;
};
 
/* Redraw the Bar based on the current projection and zoom level
 */
IhfInfoBox.prototype.draw = function() {

  if (!this.div) return;
 
  // Calculate the DIV coordinates of two opposite corners of our bounds to
  // get the size and position of our Bar
  var pixPosition = this.getProjection().fromLatLngToDivPixel(this.latlng);
  if (!pixPosition) return;
 
  // Now position our DIV based on the DIV coordinates of our bounds
  this.div.style.left = (pixPosition.x + this.offsetHorizontal) + "px";
  this.div.style.top = (pixPosition.y + this.offsetVertical) + "px";
  this.div.style.display = 'block';
};
 
/* Creates the DIV representing this IhfInfoBox in the floatPane.  If the panes
 * object, retrieved by calling getPanes, is null, remove the element from the
 * DOM.  If the div exists, but its parent is not the floatPane, move the div
 * to the new pane.
 * Called from within draw.  Alternatively, this can be called specifically on
 * a panes_changed event.
 */
IhfInfoBox.prototype.onAdd = function() {
  var panes = this.getPanes();
  var div = this.div;
  if (!div) {
    // This does not handle changing panes.  You can set the map to be null and
    // then reset the map to move the div.
    div = this.div = document.createElement("div");
    div.setAttribute('id', 'ihf-map-info-box');
    div.setAttribute('class', 'ihf-map-info-box');

    var contentDiv = document.createElement("div");    
    contentDiv.setAttribute('class', 'ihf-map-info-box-content');
    contentDiv.innerHTML = this.message;
 

		
    var topDiv = document.createElement("div");
    topDiv.setAttribute('class', 'ihf-map-info-box-top');

    var titleDiv = document.createElement("div");    
    titleDiv.innerHTML = this.messageTitle;
    titleDiv.setAttribute('class', 'ihf-map-info-box-title');
		
	var closeDiv = document.createElement("div");
	closeDiv.setAttribute('class', 'ihf-map-info-box-close');
	closeDiv.innerHTML="&times;";
		
	var clearDiv = document.createElement("div");
    clearDiv.setAttribute('style', 'clear:both');
		
	topDiv.appendChild( titleDiv ) ;
	topDiv.appendChild( closeDiv );
	topDiv.appendChild( clearDiv );
 
    function removeIhfInfoBox(ib) {
      return function() {
        ib.setMap(null);
      };
    }
		
	function cancelEvent(e) {
	  e.cancelBubble = true;
	  if (e.stopPropagation) e.stopPropagation();
	} 
 
    google.maps.event.addDomListener(closeDiv, 'click', removeIhfInfoBox(this));
		
	//A click on the map will close the infowindow
	//We need to catch click and double click events on the infowindow and cancel them, else the event
	//will be handled by the map and the info window will close or map will zoom (on double click)
	google.maps.event.addDomListener(this.div, 'click',cancelEvent);
	google.maps.event.addDomListener(this.div, 'dblclick',cancelEvent);
 
    div.appendChild(topDiv);
    div.appendChild(contentDiv);
    div.style.display = 'none';
    panes.floatPane.appendChild(div);
    this.panMap();
  } else if (div.parentNode != panes.floatPane) {
    // The panes have changed.  Move the div.
    div.parentNode.removeChild(div);
    panes.floatPane.appendChild(div);
  } else {
    // The panes have not changed, so no need to create or move the div.
  }
	if( this.afterAdd != null ){
		this.afterAdd();
	}
	
}
 
/* Pan the map to fit the IhfInfoBox.
 */
/* Pan the map to fit the IhfInfoBox.
 */
IhfInfoBox.prototype.panMap = function() {
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