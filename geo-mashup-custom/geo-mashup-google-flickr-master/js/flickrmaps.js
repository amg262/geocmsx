/** -----------------------------------------------------------
* Google Maps / Flickr Mashup
* -----------------------------------------------------------
* Description: jQuery-based Google/Flickr Mashup using Geocoding
* - ---------------------------------------------------------
* Created by: chrisaiv@gmail.com
* Modified by: 
* Date Modified: May 24, 2009
* - ---------------------------------------------------------
* Copyright 2009
* - ---------------------------------------------------------
*
*/

/**********************************************
* Helper Functions
**********************************************/
function trace(text) {
    $('#console').append('<div>'+text+'</div>');
}

/**********************************************
* Google Maps
**********************************************/
function showMap(){
	//Create a Google Map Object
	window.gmap = new google.maps.Map2( document.getElementById("gmap") );
	
	//Choose a Destination
	var cityofangels = new google.maps.LatLng( 34.043, -118.25 );
	//Center the Destination
	gmap.setCenter(cityofangels, 13);
	gmap.setMapType( google.maps.SATELLITE_MAP );
	gmap.addControl( new google.maps.LargeMapControl() );  
	gmap.addControl( new google.maps.MapTypeControl() );  
}
/**********************************************
* Flickr API
**********************************************/
function jsonFlickrFeed( json ) {
	showPhotos( json );
}

function makePhoto(photo) {
	var img = $("<img>").attr( {src: photo.media.m, title: photo.title, alt: photo.alt} );
		//Replace Medium Sized image with a 75x75 Thumb
		img.attr( "src", photo.media.m.replace('_m', '_s') );
	var a = $("<a>").attr( { href: photo.link } );
		a.prepend( img );
	var li = $("<li>");
		li.prepend( a );
		
	return li;
}

function showPhotos( json ) {
	if( !json ) alert('Flickr photos failed to load'); 
	
	//Remove the only list itm from the photos <ul>
	//$("#photos li:first").remove();		
	//Remove all the items from the ul
	$("#photos li").remove();

	//Set Google Map Bounds before Iteration
	var bounds = new google.maps.LatLngBounds();

	//Loop through the photos
	$.each( json.items, function( name,photo ) {
		$("#photos").append( makePhoto( photo ) );

		//Add Google Marker
		//trace( photo.latitude + " : " + photo.longitude );
		var point  = new google.maps.LatLng( photo.latitude, photo.longitude );

		bounds.extend( point );
		
		//Create a marker from the point
		photo.marker = new google.maps.Marker( point );
		
		//Add a marker on the Google Map
		gmap.addOverlay( photo.marker );
		
		//Event Listener for the Thumbnail Images
		//trace( $("#photos li:nth(" + name + ") a").attr("href") );
		$("#photos li:nth(" + name + ") a").bind("click",  makeClickCallback( photo ) );
		//Event Listener for the Google Markers
		google.maps.Event.addListener( photo.marker, "click", makeClickCallback( photo ) );
	});

	gmap.setZoom( gmap.getBoundsZoomLevel( bounds ) );
	gmap.setCenter( bounds.getCenter() );
}

function buildInfoWindow( photo ){
	var div = document.createElement("div");
		$(div).addClass("infoBox");
		$(div).html(
			"<h4>" + photo.title + "</h4>" +
			'<a href="' + photo.media.m.replace('_m', '') + '" rel="lightbox-gallery" class="flickr">' +
			'<img src="' + photo.media.m + '" border="0" height="150" onload="resizeInfoWindow(event)" />' +
			'</a>' +
			'<p class="moreInfo">Click the photo for larger view</p>'
		);
		$(div).contents("a.flickr").bind("click", launchSlimBox( photo  ) );

		/*
		// Add event listener 
		var link = div.getElementsByTagName('a')[0]; 
		google.maps.Event.addDomListener( link, 'click', makeZoomCallback( photo ) ); 
		//		$(div).contents("a#zoom").bind("click", makeZoomCallback( photo ) );
		*/		
	return div;
}

function resizeInfoWindow( event ){
	var w = event.target.width;
	var h = event.target.height;
	var infoBox = $(event.target).parent().parent();
}

	
function highlightPhoto( photo ){ 
	var links = $("#photos li a img");
	$.each(links, function( idx, img ){
		//trace( $(img).attr("src") );
		if( $(img).parent().attr("href") == photo.link ){
			$(img).css("opacity", 1);
			$(img).css("filter", 'alpha(opacity=40)');
		} else {
			$(img).css("opacity", 0.4);
			$(img).css("filter", 'alpha(opacity=40)');
		}
	});
	
}  

function loadJSON(url) {
	var script = $("<script>").attr({type: 'text/javascript', src: url});
	$("head").append(script);
	showMap();	
}

function loadTag( tag ) {
	loadJSON( "http://api.flickr.com/services/feeds/photos_public.gne?format=json&tags=" + tag );
}

/**********************************************
* Event Handlers
**********************************************/
function stopEvent( e ){
	e = e || window.event;
	if( e ){
		if( e.preventDefault ){
			e.preventDefault();
		} else {
			e.returnValue = false;
		}
	}
}

function makeClickCallback( photo ){
	//trace( "makeClickCallback: " + photo.marker );
	return function( e ){
		//Stop the link from activating
		stopEvent( e );
		//Build InfoWindow on Google Map Marker
		photo.marker.openInfoWindow( buildInfoWindow( photo ) );
		//Highlight Thumbnail
		highlightPhoto( photo );
	}
}

function makeZoomCallback( photo ){
	// Creates a callback function that zooms the map to that photo 
	return function( e ) {
		//Stop the link from activating
		stopEvent( e );
		//Center the marker on the Google Map
		gmap.setCenter( new google.maps.LatLng( photo.latitude, photo.longitude ), 19 );
		photo.marker.openInfoWindow( buildInfoWindow( photo ) );
		//Highlight Thumbnail
		highlightPhoto( photo );
	}
}

function launchSlimBox( photo ){
	return function( e ){
		//Stop the link from activating
		stopEvent( e );
		//Fire off Slimbox		
		jQuery.slimbox( photo.media.m.replace('_m', '') , photo.title + "<br/><span style='font-size: 0.8em'>" + 
														  $(photo.description).next().text() + "<br/>" + 
														  "<a href='" + $("#photos").attr("alt") + "' target='_blank'>View My Flickr Photostream</a></span>" );
	}
}

/**********************************************
* Dom Listeners + Handlers
**********************************************/
$("#search :submit").bind("click", onSearchClickHandler);
function onSearchClickHandler(){
	loadTag( $("#search :text").val() );
	return false;
}
