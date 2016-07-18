
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

/*GeoMashup.addAction( 'glowMarkerIcon', function( properties, object ) {
        var multi = 'trail-story-add-icon-text-box-Glow-Marker';

        // When there is more than one marker assigned to the same location - mm_20_plus.png
        //console.log(options[multi]);
        //console.log(object);
        if (options.custom_icons != 'false') {
            if (options[multi] != null && options[multi] != undefined && options[multi] != "") {
                console.log('hi0');
                glow_options.icon = properties.custom_url_path + '/images/mm_20_glow.png';
                glow_options.iconSize = [ 40, 40 ];
                glow_options.iconAnchor = [ 11, 27 ];
                console.log(glow_options);
            }
        }
    } );
/*

 */
 // An Example Google V3 customization

GeoMashup.addAction( 'loadedMap', function( properties, mxn ) {

    // Load some KML only into global maps - for instance pictures of squirrels

    var kml_arr = set_kmls(objects);
    var geo_arr = set_georss(objects);
    var geo = set_geo_json(objects);
    var google_map = mxn.getMap();



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
        /*var layer = new google.maps.FusionTablesLayer({
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
        layer.setMap(google_map);*/
      
          
        if (options.eq_geo_rss != null && options.enable_eq == '1') {

           
           var georssLayer = new google.maps.KmlLayer( options.eq_geo_rss, {
                    map: google_map
            });
            
        }
        if (options.geo_json != null && options.enable_geojson == '1') {

           var geo_json = new google.maps.KmlLayer( options.geo_json, {
                    map: google_map
            });
        
        }

        /*if (options.count_data_layers != null) {
       // trail-story-add-icon-text-box-data-layer-1trail_story_add_icon_text_box_data_layer_1
            var laya = new google.maps.KmlLayer( options.trail_story_add_icon_text_box_data_layer_1, {
               map: google_map
            } );

        for (var i =1; i <= j; i++) {
            var k = i.toString();
            var q = options['trail_story_add_icon_text_box_data_layer_'+k];
            var kml = new google.maps.KmlLayer( q, {
               map: google_map
            } );

            console.log(q);
        }

        //console.log(j);
        }*/
        if (options.custom_kml_layers != null && options.enable_kml == '1')
            for (var i = 0; i < kml_arr.length; i++) {
                //console.log(kml_arr[i]);
                var kml_layer = kml_arr[i];
                //var kml_no = 'kml_'+i;
                var kml = new google.maps.KmlLayer( kml_layer, {
                    map: google_map
                } );
            }
        }
        

        
        //console.log(geo);

      
        var grayscale = [{"featureType":"landscape","stylers":[{"saturation":-100},{"lightness":65},{"visibility":"on"}]},{"featureType":"poi","stylers":[{"saturation":-100},{"lightness":51},{"visibility":"simplified"}]},{"featureType":"road.highway","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"road.arterial","stylers":[{"saturation":-100},{"lightness":30},{"visibility":"on"}]},{"featureType":"road.local","stylers":[{"saturation":-100},{"lightness":40},{"visibility":"on"}]},{"featureType":"transit","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"administrative.province","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":-25},{"saturation":-100}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#ffff00"},{"lightness":-25},{"saturation":-97}]}];
        var browns = [{"elementType":"geometry","stylers":[{"hue":"#ff4400"},{"saturation":-68},{"lightness":-4},{"gamma":0.72}]},{"featureType":"road","elementType":"labels.icon"},{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"hue":"#0077ff"},{"gamma":3.1}]},{"featureType":"water","stylers":[{"hue":"#00ccff"},{"gamma":0.44},{"saturation":-33}]},{"featureType":"poi.park","stylers":[{"hue":"#44ff00"},{"saturation":-23}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"hue":"#007fff"},{"gamma":0.77},{"saturation":65},{"lightness":99}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"gamma":0.11},{"weight":5.6},{"saturation":99},{"hue":"#0091ff"},{"lightness":-86}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"lightness":-48},{"hue":"#ff5e00"},{"gamma":1.2},{"saturation":-23}]},{"featureType":"transit","elementType":"labels.text.stroke","stylers":[{"saturation":-64},{"hue":"#ff9100"},{"lightness":16},{"gamma":0.47},{"weight":2.7}]}];
        var pale = [{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"on"},{"lightness":33}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2e5d4"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#c5dac6"}]},{"featureType":"poi.park","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":20}]},{"featureType":"road","elementType":"all","stylers":[{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#c5c6c6"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#e4d7c6"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#fbfaf7"}]},{"featureType":"water","elementType":"all","stylers":[{"visibility":"on"},{"color":"#acbcc9"}]}];
        var midnight = [{"featureType":"all","elementType":"labels.text.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"color":"#000000"},{"lightness":13}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#144b53"},{"lightness":14},{"weight":1.4}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#08304b"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#0c4152"},{"lightness":5}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#000000"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#0b434f"},{"lightness":25}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#000000"}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"color":"#0b3d51"},{"lightness":16}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"}]},{"featureType":"transit","elementType":"all","stylers":[{"color":"#146474"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#021019"}]}];
        var paper = [{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"landscape","elementType":"all","stylers":[{"visibility":"simplified"},{"hue":"#0066ff"},{"saturation":74},{"lightness":100}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"off"},{"weight":0.6},{"saturation":-85},{"lightness":61}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"visibility":"on"}]},{"featureType":"road.arterial","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road.local","elementType":"all","stylers":[{"visibility":"on"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"water","elementType":"all","stylers":[{"visibility":"simplified"},{"color":"#5f94ff"},{"lightness":26},{"gamma":5.86}]}];
        var retro = [{"featureType":"administrative","stylers":[{"visibility":"off"}]},{"featureType":"poi","stylers":[{"visibility":"simplified"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"simplified"}]},{"featureType":"water","stylers":[{"visibility":"simplified"}]},{"featureType":"transit","stylers":[{"visibility":"simplified"}]},{"featureType":"landscape","stylers":[{"visibility":"simplified"}]},{"featureType":"road.highway","stylers":[{"visibility":"off"}]},{"featureType":"road.local","stylers":[{"visibility":"on"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"visibility":"on"}]},{"featureType":"water","stylers":[{"color":"#84afa3"},{"lightness":52}]},{"stylers":[{"saturation":-17},{"gamma":0.36}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"color":"#3f518c"}]}];
        var bright = [{"featureType":"water","stylers":[{"color":"#19a0d8"}]},{"featureType":"administrative","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"},{"weight":6}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#e85113"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#efe9e4"},{"lightness":-40}]},{"featureType":"road.arterial","elementType":"geometry.stroke","stylers":[{"color":"#efe9e4"},{"lightness":-20}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"lightness":100}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"lightness":-100}]},{"featureType":"road.highway","elementType":"labels.icon"},{"featureType":"landscape","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"landscape","stylers":[{"lightness":20},{"color":"#efe9e4"}]},{"featureType":"landscape.man_made","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels.text.stroke","stylers":[{"lightness":100}]},{"featureType":"water","elementType":"labels.text.fill","stylers":[{"lightness":-100}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"hue":"#11ff00"}]},{"featureType":"poi","elementType":"labels.text.stroke","stylers":[{"lightness":100}]},{"featureType":"poi","elementType":"labels.icon","stylers":[{"hue":"#4cff00"},{"saturation":58}]},{"featureType":"poi","elementType":"geometry","stylers":[{"visibility":"on"},{"color":"#f0e4d3"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#efe9e4"},{"lightness":-25}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#efe9e4"},{"lightness":-10}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"simplified"}]}];
        var avocado = [{"featureType":"water","elementType":"geometry","stylers":[{"visibility":"on"},{"color":"#aee2e0"}]},{"featureType":"landscape","elementType":"geometry.fill","stylers":[{"color":"#abce83"}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"color":"#769E72"}]},{"featureType":"poi","elementType":"labels.text.fill","stylers":[{"color":"#7B8758"}]},{"featureType":"poi","elementType":"labels.text.stroke","stylers":[{"color":"#EBF4A4"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"visibility":"simplified"},{"color":"#8dab68"}]},{"featureType":"road","elementType":"geometry.fill","stylers":[{"visibility":"simplified"}]},{"featureType":"road","elementType":"labels.text.fill","stylers":[{"color":"#5B5B3F"}]},{"featureType":"road","elementType":"labels.text.stroke","stylers":[{"color":"#ABCE83"}]},{"featureType":"road","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#A4C67D"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#9BBF72"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#EBF4A4"}]},{"featureType":"transit","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"visibility":"on"},{"color":"#87ae79"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#7f2200"},{"visibility":"off"}]},{"featureType":"administrative","elementType":"labels.text.stroke","stylers":[{"color":"#ffffff"},{"visibility":"on"},{"weight":4.1}]},{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#495421"}]},{"featureType":"administrative.neighborhood","elementType":"labels","stylers":[{"visibility":"off"}]}];
        var hopper = [{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#165c64"},{"saturation":34},{"lightness":-69},{"visibility":"on"}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"hue":"#b7caaa"},{"saturation":-14},{"lightness":-18},{"visibility":"on"}]},{"featureType":"landscape.man_made","elementType":"all","stylers":[{"hue":"#cbdac1"},{"saturation":-6},{"lightness":-9},{"visibility":"on"}]},{"featureType":"road","elementType":"geometry","stylers":[{"hue":"#8d9b83"},{"saturation":-89},{"lightness":-12},{"visibility":"on"}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"hue":"#d4dad0"},{"saturation":-88},{"lightness":54},{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"hue":"#bdc5b6"},{"saturation":-89},{"lightness":-3},{"visibility":"simplified"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"hue":"#bdc5b6"},{"saturation":-89},{"lightness":-26},{"visibility":"on"}]},{"featureType":"poi","elementType":"geometry","stylers":[{"hue":"#c17118"},{"saturation":61},{"lightness":-45},{"visibility":"on"}]},{"featureType":"poi.park","elementType":"all","stylers":[{"hue":"#8ba975"},{"saturation":-46},{"lightness":-28},{"visibility":"on"}]},{"featureType":"transit","elementType":"geometry","stylers":[{"hue":"#a43218"},{"saturation":74},{"lightness":-51},{"visibility":"simplified"}]},{"featureType":"administrative.province","elementType":"all","stylers":[{"hue":"#ffffff"},{"saturation":0},{"lightness":100},{"visibility":"simplified"}]},{"featureType":"administrative.neighborhood","elementType":"all","stylers":[{"hue":"#ffffff"},{"saturation":0},{"lightness":100},{"visibility":"off"}]},{"featureType":"administrative.locality","elementType":"labels","stylers":[{"hue":"#ffffff"},{"saturation":0},{"lightness":100},{"visibility":"off"}]},{"featureType":"administrative.land_parcel","elementType":"all","stylers":[{"hue":"#ffffff"},{"saturation":0},{"lightness":100},{"visibility":"off"}]},{"featureType":"administrative","elementType":"all","stylers":[{"hue":"#3a3935"},{"saturation":5},{"lightness":-57},{"visibility":"off"}]},{"featureType":"poi.medical","elementType":"geometry","stylers":[{"hue":"#cba923"},{"saturation":50},{"lightness":-46},{"visibility":"on"}]}];

        if (options.map_type != null) {

            if (options.map_type == 'Subtle Grayscale') {
                var map_type = new google.maps.StyledMapType( grayscale, { name: 'rsf' } ); 
                google_map.mapTypes.set( 'Grayscale', map_type ); 
                google_map.setMapTypeId( 'Grayscale' ); 
            } else if ( options.map_type == 'Unsaturated Browns' ) {
                var map_type = new google.maps.StyledMapType( browns, { name: 'rsf' } ); 
                google_map.mapTypes.set( 'Browns', map_type ); 
                google_map.setMapTypeId( 'Browns' ); 
            } else if ( options.map_type == 'Pale Dawn' ) {
                var map_type = new google.maps.StyledMapType( pale, { name: 'rsf' } ); 
                google_map.mapTypes.set( 'Pale', map_type ); 
                google_map.setMapTypeId( 'Pale' ); 
            } else if ( options.map_type == 'Midnight Commander' ) {
                var map_type = new google.maps.StyledMapType( midnight, { name: 'rsf' } ); 
                google_map.mapTypes.set( 'Midnight', map_type ); 
                google_map.setMapTypeId( 'Midnight' ); 
            } else if ( options.map_type == 'Paper' ) {
                var map_type = new google.maps.StyledMapType( paper, { name: 'rsf' } ); 
                google_map.mapTypes.set( 'Paper', map_type ); 
                google_map.setMapTypeId( 'Paper' ); 
            } else if ( options.map_type == 'Retro' ) {
                var map_type = new google.maps.StyledMapType( retro, { name: 'rsf' } ); 
                google_map.mapTypes.set( 'Retro', map_type ); 
                google_map.setMapTypeId( 'Retro' ); 
            } else if ( options.map_type == 'Bright' ) {
                var map_type = new google.maps.StyledMapType( bright, { name: 'rsf' } );
                google_map.mapTypes.set( 'Bright', map_type ); 
                google_map.setMapTypeId( 'Bright' ); 
            } else if ( options.map_type == 'Avocado' ) {
                var map_type = new google.maps.StyledMapType( avocado, { name: 'rsf' } ); 
                google_map.mapTypes.set( 'Avocado', map_type ); 
                google_map.setMapTypeId( 'Avocado' ); 
            } else if ( options.map_type == 'Hopper' ) {
                var map_type = new google.maps.StyledMapType( hopper, { name: 'rsf' } ); 
                google_map.mapTypes.set( 'Hopper', map_type ); 
                google_map.setMapTypeId( 'Hopper' ); 
            } 
            
        }
       



} );

 


