var directionDisplay;
  var directionsService = new google.maps.DirectionsService();
  var map;
  function initialize(lat,lng) {
    directionsDisplay = new google.maps.DirectionsRenderer();
    //var location = new google.maps.LatLng(9.93123, 76.26730);
	var location = new google.maps.LatLng(lat, lng);
    
    var zm =  parseInt(document.getElementById('map_zoom').value);
    var myOptions = {
 
      zoom: zm,
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      center: location
    }
    
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    directionsDisplay.setMap(map);
  }
  function calcRoute(from,to){
	var start = from;
    var end = to;
    var request = {
        origin:start,
        destination:end,
        travelMode: google.maps.DirectionsTravelMode.DRIVING,
		unitSystem: google.maps.DirectionsUnitSystem.METRIC 	//IMPERIAL     //METRIC
    };
    // function to round the decimal digits eg: round(123.456,2); gives 123.45
    function round(number,X) {
        X = (!X ? 2 : X);
        return Math.round(number*Math.pow(10,X))/Math.pow(10,X);
    }
    directionsService.route(request, function(response, status) {
      if (status == google.maps.DirectionsStatus.OK) {
        directionsDisplay.setDirections(response);
		var distance = response.routes[0].legs[0].distance.text;
		var time_taken = response.routes[0].legs[0].duration.text;
                
                var calc_distance = response.routes[0].legs[0].distance.value;
                
				if(document.getElementById('day_time').checked) {
				  	var less_five =  document.getElementById('day_less_five').value;
                	var more_five =  document.getElementById('day_more_five').value;
					var travel_time = 'Day';
				}
				else if(document.getElementById('night_time').checked) {
				  	var less_five =  document.getElementById('night_less_five').value;
                	var more_five =  document.getElementById('night_more_five').value;
					var travel_time = 'Night';
				}
				var curr_format =  document.getElementById('curr_format').value;
                
                if (calc_distance <= 5010) {
                    var amount_to_pay = calc_distance * less_five;
                }
                else {
                    var amount_to_pay = calc_distance * more_five;
                }
	function roundNumber(numbr,decimalPlaces) 
	{
		var placeSetter = Math.pow(10, decimalPlaces);
		numbr = Math.round(numbr * placeSetter) / placeSetter;
		return numbr;
	}
	var mi =  calc_distance / 1.609;
	var mi = mi/1000;
	var mi = roundNumber(mi, 2);   //Sets value to 2 decimal places.
				
                var rounded_amount_to_pay = round(amount_to_pay/1000,2); 
		document.getElementById('distance').innerHTML = '<div class="distance-inner">'+ "The distance between <em>"+from+"</em> and <em>"+to+"</em>: <strong>"+distance+" / "+mi+ " mi</strong>\n\
                <br/>\n\
                Time take to travel: <strong>"+time_taken+"</strong><br/>\n\
                <br/><strong>Charge to be paid: "+curr_format+rounded_amount_to_pay+"</strong>\n\
                <br/>\n\
				<div style='color: #8F4C4C; font-size: 11px;'><em>Charge rate: <5kms: "+curr_format+less_five+", >5kms: "+curr_format+more_five+", <span>Travel Time: "+travel_time+"</span></em></div></div>";
                
		var steps = "<ul>";
		var myRoute = response.routes[0].legs[0];
		for (var i = 0; i < myRoute.steps.length; i++) {
		 steps += "<li>" + myRoute.steps[i].instructions + "</li>";
		}
		steps += "</ul>";
		document.getElementById('steps').innerHTML = '<div class="steps-inner"><h2>Driving directions to '+response.routes[0].legs[0].end_address+'</h2>'+steps+'</div>';
      }
	  else{
		document.getElementById('distance').innerHTML = '<span class="gdc-error">Google Map could not be created for the entered parameters. Please be specific while providing the destination location.</span>';
	  }
    });
  }
//window.onload=function(){initialize();}