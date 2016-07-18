
jQuery(document).ready(function($) {
    //console.log(kmls);
    var k = objects.custom_kml_layers;


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
function set_georss(objects) {

     if (objects.geo_rss != null && objects.geo_rss != undefined) {
        var k = objects.geo_rss;
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
        //console.log(k);
        return k;
    }
}
function set_geo_json(objects) {

    if (objects.geo_json != null && objects.geo_json != undefined) {
        var k = objects.geo_json;
        //console.log(k);
        return k;
    }
}


GeoMashup.addAction( 'objectIcon', function( properties, object ) {

    if (options.custom_icons != 'false') {
        // Use a special icon in case the custom 'complete' var is set to 1
        if ( object.link_to_image_icon != null && typeof object.link_to_image_icon !== 'undefined' ) {

            object.icon.image = object.link_to_image_icon;
            object.icon.iconSize = [ 32, 37 ];


        } 
    }
    
});

GeoMashup.addAction( 'termIcon', function( icon, taxonomy, term_id ){
    if (options.custom_icons != 'false') {
        if (icons[taxonomy] != null) {
            icon.image = icons[taxonomy][term_id];
        }
    }
});

GeoMashup.addAction( 'multiObjectMarker', function( properties, object ) {
        var multi = 'trail-story-add-icon-text-box-Multi-Marker';

        // When there is more than one marker assigned to the same location - mm_20_plus.png
        //console.log(options[multi]);
        //console.log(object);
        if (options.custom_icons != 'false') {
            if (options[multi] != null && options[multi] != undefined && options[multi] != "") {
                object.setIcon( options[multi] );
            } else {
            }
        }
    } );

GeoMashup.addAction( 'glowMarkerIcon', function( properties, object ) {
        var multi = 'trail-story-add-icon-text-box-Glow-Marker';

        // When there is more than one marker assigned to the same location - mm_20_plus.png
        //console.log(options[multi]);
        //console.log(object);
        if (options.custom_icons != 'false') {
            if (options[multi] != null && options[multi] != undefined && options[multi] != "") {
                console.log('hi0');
                object.setIcon( options[multi] );
            } else {
                console.log('noah');
            }
        }
    } );
/*

 */
 // An Example Google V3 customization

GeoMashup.addAction( 'loadedMap', function( properties, mxn ) {

    // Load some KML only into global maps - for instance pictures of squirrels
    //alert('sfdfsf');d
    //jQuery(function($){
    var kml_arr = set_kmls(objects);
    var geo_arr = set_georss(objects);
    var geo = set_geo_json(objects);
        //console.log(k);
    //});
    var google_map = mxn.getMap();
    //alert('s');
    //console.log(google_map);


    if (properties.map_content == 'global') {
        //alert(b);
       // alert(options.bike_layer);
        if (options.bike_layer == '1') {
            var bikeLayer = new google.maps.BicyclingLayer();
            bikeLayer.setMap(google_map);
        }
        if (options.transit_layer == '1') {
            var transitLayer = new google.maps.TransitLayer();
            transitLayer.setMap(google_map);

        }
        if (options.traffic_layer == '1') {
            var trafficLayer = new google.maps.TrafficLayer();
            trafficLayer.setMap(google_map);

        }

        if (options.fusion_heat_layer == '1') {
            var layer = new google.maps.FusionTablesLayer({
              query: {
                select: 'location',
                from: '1xWyeuAhIFK_aED1ikkQEGmR8mINSCJO9Vq-BPQ'
              },
              heatmap: {
                enabled: true
              }
            });

            layer.setMap(google_map);


        }
        var layer = new google.maps.FusionTablesLayer({
          query: {
            select: 'geometry',
            from: '1ertEwm-1bMBhpEwHhtNYT47HQ9k2ki_6sRa-UQ'
                       //from: '1mZ53Z70NsChnBMm-qEYmSDOvLXgrreLTkQUvvg'

          },
          styles: [{
            polygonOptions: {
              fillColor: '#00FF00',
              fillOpacity: 0.3
            }
          }, {
            where: 'birds > 300',
            polygonOptions: {
              fillColor: '#0000FF'
            }
          }, {
            where: 'population > 5',
            polygonOptions: {
              fillOpacity: 1.0
            }
          }]
        });
        //layer.setMap(google_map);
      
       //google_map.data.addGeoJson('https://storage.googleapis.com/maps-devrel/quakes.geo.json');
          
        if (options.eq_geo_rss != null) {

       //var georssLayer = new google.maps.KmlLayer({
          //url: options.geo_rss
        //});
       var georssLayer = new google.maps.KmlLayer( options.eq_geo_rss, {
                map: google_map
        });
        //georssLayer.setMap(google_map);
        
      }
      //for (var i = 0; i < geo_arr.length; i++) {
            //console.log(kml_arr[i]);
            //var geo_rss = geo_arr[i];
            //var kml_no = 'kml_'+i;
           //var georssLayer = new google.maps.KmlLayer({
          //url: geo_arr[i]//options.geo_rss
        //});
        //georssLayer.setMap(google_map);

      //  }


      if (options.count_data_layers != null) {
       // trail-story-add-icon-text-box-data-layer-1trail_story_add_icon_text_box_data_layer_1
var kml = new google.maps.KmlLayer( options.trail_story_add_icon_text_box_data_layer_1, {
               map: google_map
            } );

        /*for (var i =1; i <= j; i++) {
            var k = i.toString();
            var q = options['trail_story_add_icon_text_box_data_layer_'+k];
            var kml = new google.maps.KmlLayer( q, {
               map: google_map
            } );

            console.log(q);
        }*/

        //console.log(j);
      }
      
        for (var i = 0; i < kml_arr.length; i++) {
            //console.log(kml_arr[i]);
            var kml_layer = kml_arr[i];
            //var kml_no = 'kml_'+i;
            var kml = new google.maps.KmlLayer( kml_layer, {
                map: google_map
            } );

        }
        

        
        //console.log(geo);

      

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

    //var map_type = new google.maps.StyledMapType( v, { name: 'trail-map' } );

    //google_map.mapTypes.set( 'custom_styles', map_type );
    //google_map.setMapTypeId( 'custom_styles', map_type );

    

}); 


 
    GeoMashup.addAction( 'singleMarkerOptions', function ( properties, marker_opts ) {
 
        // When the map is a single object map with just one marker
        marker_opts.image = properties.custom_url_path + '/assets/icon-20x20.png';
        marker_opts.iconShadow = '';
        marker_opts.iconSize = [ 24, 24 ];
        marker_opts.iconAnchor = [ 12, 24 ];
        marker_opts.iconInfoWindowAnchor = [ 12, 1 ];
 
    } );
 /*
    GeoMashup.addAction( 'glowMarkerIcon', function( properties, glow_options ) {
        glow_options.icon = properties.custom_url_path + '/images/icon-20x20.png';
        glow_options.iconSize = [ 22, 30 ];
        glow_options.iconAnchor = [ 11, 27 ];
    } );
*/


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
     var map_type = new google.maps.StyledMapType( custom_styles, { name: 'rsf' 
    } ); 

    google_map.mapTypes.set( 'grey', map_type ); 
     google_map.setMapTypeId( 'grey' ); 
    } ); 
