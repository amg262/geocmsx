$(function () {

  var weekly_quakes_endpoint =
    "http://earthquake.usgs.gov/earthquakes/feed/v1.0/summary/significant_week.geojson";
  var earthquakes = [];
  var map = new google.maps.Map($('#map-canvas')[0], {
    center: { lat: 37.78, lng: -122.44 },
    zoom: 8
  });
  var markers = [];

  getQuakes(weekly_quakes_endpoint);

  // listen for checkbox clicks - show/hide markers
  $('#info').on('change', 'input:checkbox', function (event) {
    if ($(this).is(':checked')) {
      var quake = earthquakes[$(this).data('id')];
      console.log($(this).data('id'));
      // show the marker
      markers[$(this).data('id')].setMap(map);
      map.panTo({ lat: quake.geometry.coordinates[1], lng: quake.geometry.coordinates[0] });
    }
    else {
      markers[$(this).data('id')].setMap(null);
    }
  });

  function getQuakes (endpoint) {
    $.get(weekly_quakes_endpoint, function (data, status, xhr) {
      data.features.forEach(function (quake) {
        // calculate how many hours ago and add to properties
        quake.properties.hoursAgo = Math.round(((Date.now() - quake.properties.time)/1000)/3600);
        earthquakes.push(quake);
      });
      render(earthquakes);
      addMarkers();
    });
  }

  function render (earthquakes) {
    var template = _.template( $('#quake-template').html() );
    $('#info').html( template({quakes: earthquakes}));
  }

  // add markers, but don't display until checked
  function addMarkers () {
    earthquakes.forEach(function (quake, index) {
      markers.push(new google.maps.Marker({
        position: { lat: quake.geometry.coordinates[1], lng: quake.geometry.coordinates[0] },
        map: map,
        title: quake.properties.title
      }));
      // hide the marker
      markers[index].setMap(null);
    });
  }

});

function initMap() {
  var map = new google.maps.Map($('#map-canvas')[0], {
    center: { lat: 37.78, lng: -122.44 },
    zoom: 8
  });
  quakeMap = map;
}

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

    var input = /** @type {!HTMLInputElement} *///(
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
          marker.setIcon(/** @type {google.maps.Icon} *///({
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