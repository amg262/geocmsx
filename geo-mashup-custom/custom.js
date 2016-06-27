
jQuery(document).ready(function($) {
    //console.log(kmls);
    var k = objects.custom_kml_layers;
    console.log(objects);
    console.log(options);
    //get_kmls_obj(kmls);
    $('#trail-story-post').submit(function($) {

        /*if ( document.trail_story_post.quick_post_title.value == null ||
                 document.trail_story_post.quick_post_title.value == "" ) {
            alert('All fields are required!');
            return false;

        } else if ( document.trail_story_post.quick_post_content.value == null ||
                         document.trail_story_post.quick_post_title.value == "" ) {
            alert('All fields are required!');
            return false;
        } else if ( document.trail_story_post.geo_mashup_location.value == null ||
                         document.trail_story_post.geo_mashup_location.value == "" )
            alert('Map location is required for submission!');
            return false;*/

    });
    
});
/**
* 
*/
function csv_to_array(str) {

    if (str != null && str != undefined) {
        var arr = [];

        arr = str.split(',');

        return arr;
    } else {
        return false;
    }
}

function set_kmls(objects) {

     if (objects.custom_kml_layers != null && objects.custom_kml_layers != undefined) {
        var k = objects.custom_kml_layers;
        //console.log(k);
        var objs = csv_to_array(k);

        if (objs.length > 0) {
            return objs;
        } else {
            return false;
        }
    }
}

function set_custom_styles(objects) {

    if (objects.custom_styles != null && objects.custom_styles != undefined) {
        var k = objects.custom_styles;
        console.log(k);
        return k;
    }
}
function set_geo_json(objects) {

    if (objects.geo_json != null && objects.geo_json != undefined) {
        var k = objects.geo_json;
        console.log(k);
        return k;
    }
}

GeoMashup.addAction( 'objectIcon', function( properties, object ) {

    // Use a special icon in case the custom 'complete' var is set to 1
    if ( object.link_to_image_icon != null && typeof object.link_to_image_icon !== 'undefined' ) {

        object.icon.image = object.link_to_image_icon;
        object.icon.iconSize = [ 32, 37 ];

    } 
    
});

GeoMashup.addAction( 'termIcon', function( icon, taxonomy, term_id ){
    
    icon.image = icons[taxonomy][term_id];

});

/*

 */
 // An Example Google V3 customization

GeoMashup.addAction( 'loadedMap', function( properties, mxn ) {

    // Load some KML only into global maps - for instance pictures of squirrels
    //alert('sfdfsf');d
    //jQuery(function($){
    var kml_arr = set_kmls(objects);
    var geo = set_geo_json(objects);
        //console.log(k);
    //});
    var google_map = mxn.getMap();
    //alert('s');
    console.log(google_map);
    if (properties.map_content == 'global') {
        //alert(b);

        for (var i = 0; i < kml_arr.length; i++) {
            //console.log(kml_arr[i]);
            var kml_layer = kml_arr[i];
            //var kml_no = 'kml_'+i;
            var kml = new google.maps.KmlLayer( kml_layer, {
                map: google_map
            } );

        }
        var geo = new google.maps.KmlLayer( geo, {
            map: google_map
        } );

        
        console.log(geo);

      

        var v = [{
        icon: {
              path: google.maps.SymbolPath.CIRCLE,
              scale: 1,
              fillColor: '#f00',
              fillOpacity: 0.35,
              strokeWeight: 0
            }
        }];
      var map_type = new google.maps.StyledMapType( v, { name: 'trail-map' } );

        google_map.mapTypes.set( 'custom_styles', map_type );
        google_map.setMapTypeId( 'custom_styles', map_type );
        //alert('sdfdsf');
        //WE NEED TO BASICALLY CALL THE ADD AND REMOVE
        
        // Make the Google v3 call to add a Flickr KML layer

        /*var kml = new google.maps.KmlLayer( 'http://www.iceagetrail.org/wp-content/uploads/2015/12/iat-connector.kml', {
            map: google_map
        } );*/
        //SPLIT IAT KML INTO 2 PARTS TO PREVENT IT FROM BEING TO LARGE
        /**var kml_iat1 = new google.maps.KmlLayer( 'http://www.iceagetrail.org/wp-content/iat_2016-1.kml', {
            map: google_map
        } );
        var kml_iat2 = new google.maps.KmlLayer( 'http://www.iceagetrail.org/wp-content/iat_2016-2.kml', {
            map: google_map
        } );
        var kml2 = new google.maps.KmlLayer( 'http://www.iceagetrail.org/wp-content/uploads/2016/01/iat_conn_2016.kml', {
            map: google_map
        } );
        //http://www.iceagetrail.org/wp-content/uploads/2016/01/iat_2016.kml
        //http://www.iceagetrail.org/wp-content/uploads/2016/01/iat_conn_2016.kml

        /*var kml = new google.maps.KmlLayer( kml_layer.kml_layer_1, {
            map: google_map
        } );*/

       /* var kml2 = new google.maps.KmlLayer( kml_layer.kml_layer_2, {
            map: google_map
        } );*/


        /*var kml3 = new google.maps.KmlLayer( kml_layer.kml_layer_3, {
            map: google_map
        } );*/

    }

} );

/*
** Customizes the look of map
 */
GeoMashup.addAction( 'loadedMap', function( properties, map ) {
    //alert('loaded');
    var styles = set_custom_styles(objects);

    var google_map = map.getMap();
    console.log(google_map);

    var v = [{
        icon: {
              path: google.maps.SymbolPath.CIRCLE,
              scale: 1,
              fillColor: '#f00',
              fillOpacity: 0.35,
              strokeWeight: 0
            }
        }];
    var custom_styles = [{
        featureType: "all",
        elementType: "all",
        stylers: [
            { saturation: -20 }
        ]
    },{

        featureType: "administrative.locality",
        elementType: "all",
        stylers: [
            { visibility: "on" }
        ]
    },{
        featureType: "road.highway",
        elementType: "all",
        stylers: [
            { visibility: "on" },
            { hue: "#E39939" },
            { saturation: 70 },
            { lightness: 60 }
        ]
    },{

        featureType: "transit.station",
        elementType: "all",
        stylers: [
            { visibility: "on" },
            { color: "#f30000" },
        ]
    },{
        featureType:    "poi.park",
        elementType:    "geometry.fill",
        stylers: [ 
            { visibility:"on" },
            { color: "#9EDC88" },
            { saturation:0 },
            { lightness:0 },
        ]
    },{
        featureType:    "water",
        elementType:    "geometry.fill",
        stylers: [ 
            { visibility:"on" },
            { color: "#5794bf" },
            { saturation:-20 },
        ]
    },{
        featureType: "poi",
        elementType: "labels",
        stylers: [
            { visibility: "on" }
        ]
    },{
        featureType: "administrative.neighborhood",
        elementType: "all",
        stylers: [
            { visibility: "on" }
        ]
    },{
        featureType: "all",
        elementType: "labels.text",
        stylers: [
            { color: "#555555" },
            { weight: "0.1" },
        ]
    },{       
        featureType: "administrative.neighborhood",
        elementType: "labels.text",
        stylers: [
            { color: "#733e1d"},
            { weight: "0.1" },
        { visibility: "on" }
        ]
    },{       
        featureType: "transit.station",
        elementType: "labels.text",
        stylers: [
            { visibility: "on" },
            { color: "#f30000" },
            { weight:"0.05" },
        ]
    },{
        featureType: "transit.station.bus",
        elementType: "geometry",
        stylers: [
            { visibility: "on" },
            { color: "#f30000" },
            { weight:"0.05" },
        ]
    },{
        featureType: "transit.station.bus",
        elementType: "geometry",
        stylers: [
            { visibility: "on" },
            { color: "#f30000" },
            { weight:"0.05" },
        ]
    },{
        featureType: "road.arterial",
        elementType: "all",
        stylers: [
            { saturation: 70 },
            { hue: "#E39939" },
            { lightness: 30 }
        ]
    }];

    var map_type = new google.maps.StyledMapType( v, { name: 'trail-map' } );

    google_map.mapTypes.set( 'custom_styles', map_type );
    google_map.setMapTypeId( 'custom_styles', map_type );

    

}); 

GeoMashup.addAction( 'multiObjectMarker', function( properties, marker ) {
 
        // When there is more than one marker assigned to the same location - mm_20_plus.png
        marker.setIcon( properties.custom_url_path + '/images/icon-20x20.png' );
 
    } );
 
    GeoMashup.addAction( 'singleMarkerOptions', function ( properties, marker_opts ) {
 
        // When the map is a single object map with just one marker
        marker_opts.image = properties.custom_url_path + '/images/icon-20x20.png';
        marker_opts.iconShadow = '';
        marker_opts.iconSize = [ 24, 24 ];
        marker_opts.iconAnchor = [ 12, 24 ];
        marker_opts.iconInfoWindowAnchor = [ 12, 1 ];
 
    } );
 
    GeoMashup.addAction( 'glowMarkerIcon', function( properties, glow_options ) {
        glow_options.icon = properties.custom_url_path + '/images/icon-20x20.png';
        glow_options.iconSize = [ 22, 30 ];
        glow_options.iconAnchor = [ 11, 27 ];
    } );



// console.log(allImageOverlays);


// this lambda function is loaded by the main geomashup plugin.  It has access to the
// mapstraction map object ("map"), and to a properties object which stores all of the shortcode
// attributes along with a bunch of other data. But we're only interested in the new attrributes we've added:
// imageoverlay, imageopacity, geojson, and kml.   
GeoMashup.addAction( 'loadedMap', function ( properties, map ) {
    // console.log(properties.imageoverlay);
    // get direct access to the google map object. Should do a check to ensure that we're actually
    // using google map.  A proper implementation will likely rewrite some of this code to be more
    // portable
        // placeholder to be used in the lampda function below
var historicalOverlay;
// list of all the image overlays. The unique name of each overlay is an attribute of the main variable, and has as a value
// with two parts, "url" and "imageBounds". Add your image overlays here. It is probably a good idea to use relative URL's
// in case your site name changes.  
var allImageOverlays = {
    newtonbrook: {
        url: 'http://flynnhouse.hackinghistory.ca/wp-content/uploads/2016/03/Newtonbrook-Tremaine-Map-r-neg21.png',
        imageBounds: {
            north : 43.8073086158169,
            south : 43.76891864109982,
            west : -79.45731331229433,
            east : -79.38658034742863
        }
    }
}

// similar to the above, but stores sets of geoJSON data, if you have any.
var geoJsonFiles = {
    newtonbrook: ''
}
    var google_map = map.getMap();
    // we'll use these later
    var thisOverlay, thisOpacity;

    // prepare to insert image; set thisOpacity to 0.7 by default
    if (properties.imageopacity || properties.imageopacity <= 1|| properties.imageopacity >= 0 ) {
        thisOpacity = properties.imageopacity;
    } else {
        thisOpacity = 0.7;
    }

    // if shortcode specifies an image overlay, retrieve its information from the 
    // variable 'imageOverlays' and add it to the map.
    if (properties.imageoverlay) {
        // just make it easier to reference the overlay object
        var o = allImageOverlays[properties.imageoverlay];
        // this line defines the overlay
        thisOverlay = new google.maps.GroundOverlay(o.url, o.imageBounds, {opacity:thisOpacity});
        // now make it appear on the map
        thisOverlay.setMap(google_map);
    }

    // if shortcode specifies a kml, retrieve it and add to map.
    if (properties.kml) {
        var kml = new google.maps.KmlLayer(properties.kml, {
            map:google_map});
    }

    // goeJSON hasn't been implemented yet!! Ooops
    

} );
GeoMashup.addAction( 'loadedMap', function( properties, map ) { 
    var google_map = map.getMap(); 
     var custom_styles = [ 
    { 
    featureType: "administrative", 
     elementType: "all", 
    stylers: [ { visibility: "off" }, { saturation: -100 } ] 
    },{ 

     featureType: "landscape", 
    elementType: "all", 
    stylers: [ { visibility: "off" }, { saturation: -100 } ] 
     },{ 
    featureType: "poi", 
    elementType: "all", 
     stylers: [ { visibility: "off" }, { saturation: -100 } ] 
    },{ 

    featureType: "road", 
     elementType: "all", 
    stylers: [ { visibility: "simplified" }, { saturation: -100 } ] 
    },{ 

    featureType: "road", 
     elementType: "labels", 
    stylers: [ {visibility: "on" }, { hue: "#00ffe6" } ] 
    },{ 
    
     featureType: "transit", 
    elementType: "all", 
    stylers: [ { visibility: "on" }, { saturation: -100 } ] 
     },{ 
    featureType: "water", 
    elementType: "all", 
     stylers: [ { visibility: "simplified" }, { hue: "#00ffe6" } ] 
    } 

    ]; 
     //var map_type = new google.maps.StyledMapType( custom_styles, { name: 'rsf' 
    } ); 

   // google_map.mapTypes.set( 'grey', map_type ); 
    // google_map.setMapTypeId( 'grey' ); 
   // } ); 
/*var infowindow = new google.maps.InfoWindow();
        var service = new google.maps.places.PlacesService(map);

        service.getDetails({
          placeId: 'ChIJN1t_tDeuEmsRUsoyG83frY4'
        }, function(place, status) {
          if (status === google.maps.places.PlacesServiceStatus.OK) {
            var marker = new google.maps.Marker({
              map: map,
              position: place.geometry.location
            });
            google.maps.event.addListener(marker, 'click', function() {
              infowindow.setContent('<div><strong>' + place.name + '</strong><br>' +
                'Place ID: ' + place.place_id + '<br>' +
                place.formatted_address + '</div>');
              infowindow.open(map, this);
            });

             function performSearch() {
        var request = {
          bounds: map.getBounds(),
          keyword: 'best view'
        };
        service.radarSearch(request, callback);
      }

      function callback(results, status) {
        if (status !== google.maps.places.PlacesServiceStatus.OK) {
          console.error(status);
          return;
        }
        for (var i = 0, result; result = results[i]; i++) {
          addMarker(result);
        }
      }

      function addMarker(place) {
        var marker = new google.maps.Marker({
          map: map,
          position: place.geometry.location,
          icon: {
            url: 'http://maps.gstatic.com/mapfiles/circle.png',
            anchor: new google.maps.Point(10, 10),
            scaledSize: new google.maps.Size(10, 17)
          }
        });

        google.maps.event.addListener(marker, 'click', function() {
          service.getDetails(place, function(result, status) {
            if (status !== google.maps.places.PlacesServiceStatus.OK) {
              console.error(status);
              return;
            }
            infoWindow.setContent(result.name);
            infoWindow.open(map, marker);
          });
        });
      }
      <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 100%;
      }
      .controls {
        margin-top: 10px;
        border: 1px solid transparent;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        height: 32px;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
      }

      #pac-input {
        background-color: #fff;
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
        margin-left: 12px;
        padding: 0 11px 0 13px;
        text-overflow: ellipsis;
        width: 300px;
      }

      #pac-input:focus {
        border-color: #4d90fe;
      }

      .pac-container {
        font-family: Roboto;
      }

      #type-selector {
        color: #fff;
        background-color: #4d90fe;
        padding: 5px 11px 0px 11px;
      }

      #type-selector label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
      }


      <input id="pac-input" class="controls" type="text"
        placeholder="Enter a location">
    <div id="type-selector" class="controls">
      <input type="radio" name="type" id="changetype-all" checked="checked">
      <label for="changetype-all">All</label>

      <input type="radio" name="type" id="changetype-establishment">
      <label for="changetype-establishment">Establishments</label>

      <input type="radio" name="type" id="changetype-address">
      <label for="changetype-address">Addresses</label>

      <input type="radio" name="type" id="changetype-geocode">
      <label for="changetype-geocode">Geocodes</label>
    </div>

    var input = /** @type {!HTMLInputElement} */(
           /* document.getElementById('pac-input'));

        var types = document.getElementById('type-selector');
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);

        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map);

        var infowindow = new google.maps.InfoWindow();
        var marker = new google.maps.Marker({
          map: map,
          anchorPoint: new google.maps.Point(0, -29)
        });

        autocomplete.addListener('place_changed', function() {
          infowindow.close();
          marker.setVisible(false);
          var place = autocomplete.getPlace();
          if (!place.geometry) {
            window.alert("Autocomplete's returned place contains no geometry");
            return;
          }

          // If the place has a geometry, then present it on a map.
          if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
          } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);  // Why 17? Because it looks good.
          }
          marker.setIcon(/** @type {google.maps.Icon} */({
         /*   url: place.icon,
            size: new google.maps.Size(71, 71),
            origin: new google.maps.Point(0, 0),
            anchor: new google.maps.Point(17, 34),
            scaledSize: new google.maps.Size(35, 35)
          }));
          marker.setPosition(place.geometry.location);
          marker.setVisible(true);

          var address = '';
          if (place.address_components) {
            address = [
              (place.address_components[0] && place.address_components[0].short_name || ''),
              (place.address_components[1] && place.address_components[1].short_name || ''),
              (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
          }

          infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
          infowindow.open(map, marker);
        });

        // Sets a listener on a radio button to change the filter type on Places
        // Autocomplete.
        function setupClickListener(id, types) {
          var radioButton = document.getElementById(id);
          radioButton.addEventListener('click', function() {
            autocomplete.setTypes(types);
          });
        }

        setupClickListener('changetype-all', []);
        setupClickListener('changetype-address', ['address']);
        setupClickListener('changetype-establishment', ['establishment']);
        setupClickListener('changetype-geocode', ['geocode']);
      }*/
/*
       panorama = new google.maps.StreetViewPanorama(
            document.getElementById('street-view'),
            {
              position: {lat: 37.869260, lng: -122.254811},
              pov: {heading: 165, pitch: 0},
              zoom: 1
            });
      }
      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 8,
          center: {lat: 63.333, lng: -150.5},  // Denali.
          mapTypeId: 'terrain'
        });
        var elevator = new google.maps.ElevationService;
        var infowindow = new google.maps.InfoWindow({map: map});

        // Add a listener for the click event. Display the elevation for the LatLng of
        // the click inside the infowindow.
        map.addListener('click', function(event) {
          displayLocationElevation(event.latLng, elevator, infowindow);
        });
      }

      function displayLocationElevation(location, elevator, infowindow) {
        // Initiate the location request
        elevator.getElevationForLocations({
          'locations': [location]
        }, function(results, status) {
          infowindow.setPosition(location);
          if (status === google.maps.ElevationStatus.OK) {
            // Retrieve the first result
            if (results[0]) {
              // Open the infowindow indicating the elevation at the clicked position.
              infowindow.setContent('The elevation at this point <br>is ' +
                  results[0].elevation + ' meters.');
            } else {
              infowindow.setContent('No results found');
            }
          } else {
            infowindow.setContent('Elevation service failed due to: ' + status);
          }
        });
      }
      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 13,
          center: {lat: 51.501904, lng: -0.115871}
        });

        var transitLayer = new google.maps.TransitLayer();
        transitLayer.setMap(map);
      }
      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 14,
          center: {lat: 42.3726399, lng: -71.1096528}
        });

        var bikeLayer = new google.maps.BicyclingLayer();
        bikeLayer.setMap(map);
      }
       function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 13,
          center: {lat: 34.04924594193164, lng: -118.24104309082031}
        });

        var trafficLayer = new google.maps.TrafficLayer();
        trafficLayer.setMap(map);
      }
      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 10, lng: -140},
          zoom: 3
        });

        var layer = new google.maps.FusionTablesLayer({
          query: {
            select: 'location',
            from: '1xWyeuAhIFK_aED1ikkQEGmR8mINSCJO9Vq-BPQ'
          },
          heatmap: {
            enabled: true
          }
        });

        layer.setMap(map);
      }

      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 4,
          center: {lat: -33, lng: 151},
          zoomControl: false,
          scaleControl: true
        });
      }
       var marker;

      function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 13,
          center: {lat: 59.325, lng: 18.070}
        });

        marker = new google.maps.Marker({
          map: map,
          draggable: true,
          animation: google.maps.Animation.DROP,
          position: {lat: 59.327, lng: 18.067}
        });
        marker.addListener('click', toggleBounce);
      }

      function toggleBounce() {
        if (marker.getAnimation() !== null) {
          marker.setAnimation(null);
        } else {
          marker.setAnimation(google.maps.Animation.BOUNCE);
        }
      }

      function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 11,
          center: {lat: 35.6894, lng: 139.692},
          mapTypeId: google.maps.MapTypeId.HYBRID
        });

        infoWindow = new google.maps.InfoWindow();

        maxZoomService = new google.maps.MaxZoomService();

        map.addListener('click', showMaxZoom);
      }

      function showMaxZoom(e) {
        maxZoomService.getMaxZoomAtLatLng(e.latLng, function(response) {
          if (response.status !== google.maps.MaxZoomStatus.OK) {
            infoWindow.setContent('Error in MaxZoomService');
          } else {
            infoWindow.setContent(
                'The maximum zoom at this location is: ' + response.zoom);
          }
          infoWindow.setPosition(e.latLng);
          infoWindow.open(map);
        });
      }