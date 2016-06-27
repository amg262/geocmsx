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