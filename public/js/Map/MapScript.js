var map;
var marker;

jQuery(function() {

	init();

	$('.contact_close').click(function(){
		$('.sidebar').removeClass('active')
		$('.toggle').removeClass('active')
	});

});

function init(){

		var stops = locations;

		map = new window.google.maps.Map(document.getElementById("patientmap"));
		// new up complex objects before passing them around
		var directionsDisplay = new window.google.maps.DirectionsRenderer({suppressMarkers: true});
		var directionsService = new window.google.maps.DirectionsService();

		Tour_startUp(stops);
		
		window.tour.loadMap(map, directionsDisplay);
		window.tour.fitBounds(map);

		if (stops.length > 0)
			window.tour.calcRoute(directionsService, directionsDisplay);
	}

	var rotation = 0;

	function placeMarkerAndPanTo(latLng, map) {

		var car = "M17.402,0H5.643C2.526,0,0,3.467,0,6.584v34.804c0,3.116,2.526,5.644,5.643,5.644h11.759c3.116,0,5.644-2.527,5.644-5.644 V6.584C23.044,3.467,20.518,0,17.402,0z M22.057,14.188v11.665l-2.729,0.351v-4.806L22.057,14.188z M20.625,10.773 c-1.016,3.9-2.219,8.51-2.219,8.51H4.638l-2.222-8.51C2.417,10.773,11.3,7.755,20.625,10.773z M3.748,21.713v4.492l-2.73-0.349 V14.502L3.748,21.713z M1.018,37.938V27.579l2.73,0.343v8.196L1.018,37.938z M2.575,40.882l2.218-3.336h13.771l2.219,3.336H2.575z M19.328,35.805v-7.872l2.729-0.355v10.048L19.328,35.805z";

		var cicon = {
			path: car,
			scale: .7,
			strokeColor: 'white',
			strokeWeight: .10,
			fillOpacity: 1,
			fillColor: '#404040',
			offset: '5%',
			rotation: parseInt(rotation),
			anchor: new google.maps.Point(10, 25) // orig 10,50 back of car, 10,0 front of car, 10,25 center of car
		};

        if (marker) {
			marker.setPosition(latLng);
		  } else {
			marker = new google.maps.Marker({
			  position: latLng,
			  map: map,
			  icon:cicon
			});
		  }

		  transition(latLng);
		  map.panTo(latLng);
		  rotation += 10;
   }

    var positions = [28.4594965, 77.0266383];
	var numDeltas = 100;
	var delay = 50; //milliseconds
	var i = 0;
	var deltaLat;
	var deltaLng;

	function transition(result){

		console.log(result);

		i = 0;
		deltaLat = (result.lat - positions[0])/numDeltas;
		deltaLng = (result.lng - positions[1])/numDeltas;

		moveMarker();

	}

	function moveMarker(){

		positions[0] += deltaLat;
		positions[1] += deltaLng;
		var latlng = new google.maps.LatLng(positions[0], positions[1]);
		//marker.setTitle("Latitude:"+position[0]+" | Longitude:"+position[1]);
		marker.setPosition(latlng);
		if(i!=numDeltas){
			i++;
			setTimeout(moveMarker, delay);
		}
	}

function Tour_startUp(stops) {

	var chartimage = (document.getElementById('chartimg'));
    map.controls[window.google.maps.ControlPosition.RIGHT_TOP].push(chartimage);

	var input = (document.getElementById('route-input'));
    map.controls[window.google.maps.ControlPosition.TOP_LEFT].push(input);
    
    if (!window.tour) window.tour = {
        updateStops: function (newStops) {
            stops = newStops;
        },
        // map: google map object
        // directionsDisplay: google directionsDisplay object (comes in empty)
        loadMap: function (map, directionsDisplay) {
            var myOptions = {
                zoom: 13,
                center: new window.google.maps.LatLng(51.507937, -0.076188), // default to Canada vancouver
                mapTypeId: window.google.maps.MapTypeId.ROADMAP
            };
            map.setOptions(myOptions);
            directionsDisplay.setMap(map);
        },
        fitBounds: function (map) {
            var bounds = new window.google.maps.LatLngBounds();

            // extend bounds for each record
            jQuery.each(stops, function (key, val) {
                var myLatlng = new window.google.maps.LatLng(val.Geometry.Latitude, val.Geometry.Longitude);
                bounds.extend(myLatlng);
            });
            map.fitBounds(bounds);
        },
        calcRoute: function (directionsService, directionsDisplay) {
            var batches = [];
            var otherValue = [];
            var itemsPerBatch = 10; // google API max = 10 - 1 start, 1 stop, and 8 waypoints
            var itemsCounter = 0;

            var wayptsExist = stops.length > 0;

            while (wayptsExist) {
                var subBatch = [];
                var subitemsCounter = 0;

				for (var j = itemsCounter; j < stops.length; j++) {
                    subitemsCounter++;
                    otherValue.push({
						visitID: stops[j].Geometry.VisitID,
						time: stops[j].Geometry.Time,
						name: stops[j].Geometry.Name,
						address: stops[j].Geometry.Address,
						visitstatus: stops[j].Geometry.VisitStatusID,
						CustomerID: stops[j].Geometry.CustomerID,
                        Latitude: stops[j].Geometry.Latitude,
                        Longitude: stops[j].Geometry.Longitude
					});
					subBatch.push({
						location: new window.google.maps.LatLng(stops[j].Geometry.Latitude, stops[j].Geometry.Longitude),
                        stopover: true,
					});
                    if (subitemsCounter == itemsPerBatch)
                        break;
                }

                if(stops.length == 1)
                {
                	itemsCounter = 1;
                    subitemsCounter = 1;
                	
                	otherValue.push({
						visitID: stops[0].Geometry.VisitID,
						time: stops[0].Geometry.Time,
						name: stops[0].Geometry.Name,
						address: stops[0].Geometry.Address,
						visitstatus: stops[0].Geometry.VisitStatusID,
						CustomerID: stops[0].Geometry.CustomerID,
                        Latitude: stops[0].Geometry.Latitude,
                        Longitude: stops[0].Geometry.Longitude
					});
					subBatch.push({
						location: new window.google.maps.LatLng(stops[0].Geometry.Latitude, stops[0].Geometry.Longitude),
                        stopover: true,
					});
                }

                itemsCounter += subitemsCounter;
                batches.push(subBatch);
                wayptsExist = itemsCounter < stops.length;
                // If it runs again there are still points. Minus 1 before continuing to
                // start up with end of previous tour leg
                itemsCounter--;
            }

            // now we should have a 2 dimensional array with a list of a list of waypoints
            var combinedResults;
            var unsortedResults = [{}]; // to hold the counter and the results themselves as they come back, to later sort
            var directionsResultsReturned = 0;

            for (var k = 0; k < batches.length; k++) {
                var lastIndex = batches[k].length - 1;
                var start = batches[k][0].location;
                var end = batches[k][lastIndex].location;

				//alert(JSON.stringify(batches[k]));

                // trim first and last entry from array
                var waypts = [];
                waypts = batches[k];
                waypts.splice(0, 1);
                waypts.splice(waypts.length - 1, 1);

				//alert(JSON.stringify(otherValue));

                var request = {
                    origin: start,
                    destination: end,
                    waypoints: waypts,
                    travelMode: window.google.maps.TravelMode.DRIVING,
					optimizeWaypoints: true,
					avoidFerries: true,
					avoidHighways: true,
					avoidTolls: true,
                };
                (function (kk) {
                    directionsService.route(request, function (result, status) {
                        if (status == window.google.maps.DirectionsStatus.OK) {

                            var unsortedResult = { order: kk, result: result };
                            unsortedResults.push(unsortedResult);

                            directionsResultsReturned++;

                            if (directionsResultsReturned == batches.length) // we've received all the results. put to map
                            {
                                // sort the returned values into their correct order
                                unsortedResults.sort(function (a, b) { return parseFloat(a.order) - parseFloat(b.order); });
                                var count = 0;
                                for (var key in unsortedResults) {
                                    if (unsortedResults[key].result != null) {
                                        if (unsortedResults.hasOwnProperty(key)) {
                                            if (count == 0) // first results. new up the combinedResults object
                                                combinedResults = unsortedResults[key].result;
                                            else {
                                                // only building up legs, overview_path, and bounds in my consolidated object. This is not a complete
                                                // directionResults object, but enough to draw a path on the map, which is all I need
                                                combinedResults.routes[0].legs = combinedResults.routes[0].legs.concat(unsortedResults[key].result.routes[0].legs);
                                                combinedResults.routes[0].overview_path = combinedResults.routes[0].overview_path.concat(unsortedResults[key].result.routes[0].overview_path);

                                                combinedResults.routes[0].bounds = combinedResults.routes[0].bounds.extend(unsortedResults[key].result.routes[0].bounds.getNorthEast());
                                                combinedResults.routes[0].bounds = combinedResults.routes[0].bounds.extend(unsortedResults[key].result.routes[0].bounds.getSouthWest());
                                            }
                                            count++;
                                        }
                                    }
                                }
                                directionsDisplay.setDirections(combinedResults);
                                var legs = combinedResults.routes[0].legs;
                                // alert(legs.length);
                                for (var i=0; i < legs.length;i++){
										var markerletter = "A".charCodeAt(0);
										markerletter += i;
                                 // markerletter = String.fromCharCode(markerletter);                                
                                  markerletter = i+1; 
                                  legslat = Math.round(legs[i].start_location.lat()*100)/100;
                                  legslng = Math.round(legs[i].start_location.lng()*100)/100;
                                  
                                for(var ov = 0; ov < otherValue.length; ov++)
                                    {
                                        otherlat = Math.round(otherValue[ov].Latitude*100)/100;
                                        otherlng = Math.round(otherValue[ov].Longitude*100)/100;
                                        if(legslat == otherlat && legslng == otherlng)
                                        {
                                            console.log(legs[i].start_location+" "+otherValue[ov].name+" "+otherValue[ov].time+"<br>"+legs[i].start_address+" "+i+" "+otherValue[ov].visitID+" "+otherValue[ov].visitstatus+" "+otherValue[ov].CustomerID);                                             
                                            createMarker(directionsDisplay.getMap(),legs[i].start_location,otherValue[ov].name,otherValue[ov].time+"<br>"+legs[i].start_address,i,otherValue[ov].visitID,otherValue[ov].visitstatus,otherValue[ov].CustomerID);                                             
                                            break;
                                        }
                                    }     
                                }
                                var i=legs.length;
                                var markerletter = "A".charCodeAt(0);
								markerletter += i;
                                //markerletter = String.fromCharCode(markerletter);
                                markerletter = i+1;
                                legslat = Math.round(legs[legs.length-1].end_location.lat()*100)/100;
                                legslng = Math.round(legs[legs.length-1].end_location.lng()*100)/100;
                                for(var ov = 0; ov < otherValue.length; ov++)
                                {
                                    otherlat = Math.round(otherValue[ov].Latitude*100)/100;
                                    otherlng = Math.round(otherValue[ov].Longitude*100)/100;
                                    if(legslat == otherlat && legslng == otherlng)
                                    {
                                        console.log(legs[legs.length-1].end_location+" "+otherValue[ov].name+" "+otherValue[ov].time+"<br>"+legs[legs.length-1].end_address+" "+i+" "+otherValue[ov].visitID+" "+otherValue[ov].visitstatus+" "+otherValue[ov].CustomerID);
                                        createMarker(directionsDisplay.getMap(),legs[legs.length-1].end_location,otherValue[ov].name,otherValue[ov].time+"<br>"+legs[legs.length-1].end_address,i,otherValue[ov].visitID,otherValue[ov].visitstatus,otherValue[ov].CustomerID);
                                    }
                                }    
                            }
                        }
                    });
                })(k);
            }
        }
    };
}
var infowindow = new google.maps.InfoWindow(
  {
    size: new google.maps.Size(150,50)
  });

var icons = new Array();
icons["red"] = new google.maps.MarkerImage("mapIcons/marker_red.png",
      // This marker is 20 pixels wide by 34 pixels tall.
      new google.maps.Size(20, 34),
      // The origin for this image is 0,0.
      new google.maps.Point(0,0),
      // The anchor for this image is at 9,34.
      new google.maps.Point(9, 34));



function getMarkerImage(iconStr,visitstatus) {

   if ((typeof(iconStr)=="undefined") || (iconStr==null)) {
      iconStr = "red";
   }
   if (!icons[iconStr]) {
	   var markerimg = "https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld="+ iconStr +"%7Cff776b";

	    if(visitstatus == 1){        /* In-complete */
		 // var markerimg = "https://www.googlemapsmarkers.com/v1/"+ iconStr +"/ff776b/";
		 var color = 'ff776b';
		 var markerimg = "https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld="+ iconStr +"%7Cff776b";
	    }
		if(visitstatus == 2){  /* complete */
		  //var markerimg = "https://www.googlemapsmarkers.com/v1/"+ iconStr +"/009900/";
		  var color = '00ff00';
		 var markerimg = "https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld="+ iconStr +"%7C00ff00";
		}
		if(visitstatus == 3){  /* Skip */
		  //var markerimg = "https://www.googlemapsmarkers.com/v1/"+ iconStr +"/f9f900/";
		   var color = '0099ff';
		    var markerimg = "https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld="+ iconStr +"%7C0099ff";
		}
		if(visitstatus == 4){  /* Cancel */
		  //var markerimg = "https://www.googlemapsmarkers.com/v1/"+ iconStr +"/0099FF/";
		   var color = ' cccccc';
		    var markerimg = "https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld="+ iconStr +"%7CCCCCCC";
		}
		if(visitstatus == 5){  /* Postpone */
		  //var markerimg = "https://www.googlemapsmarkers.com/v1/"+ iconStr +"/0099FF/";
		   var color = 'ff33cc';
		    var markerimg = "https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld="+ iconStr +"%7Cff33cc";
		}
		if(visitstatus == 6){  /* Return */
		  //var markerimg = "https://www.googlemapsmarkers.com/v1/"+ iconStr +"/0099FF/";
		   var color = 'f9f900';
		    var markerimg = "https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld="+ iconStr +"%7Cf9f900";
		}
        if(visitstatus == 7){  /* Return */
          //var markerimg = "https://www.googlemapsmarkers.com/v1/"+ iconStr +"/0099FF/";
           var color = 'fff';
            var markerimg = "https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld="+ iconStr +"%7Cfff";
        }

	   icons[iconStr] = new google.maps.MarkerImage(markerimg,

      // This marker is 20 pixels wide by 34 pixels tall.
      new google.maps.Size(20, 34),
      // The origin for this image is 0,0.
      new google.maps.Point(0,0),
      // The anchor for this image is at 6,20.
      new google.maps.Point(9, 34));
   }
   //alert(JSON.stringify(icons[iconStr]);
   return icons[iconStr];

}
  // Marker sizes are expressed as a Size of X,Y
  // where the origin of the image (0,0) is located
  // in the top left of the image.

  // Origins, anchor positions and coordinates of the marker
  // increase in the X direction to the right and in
  // the Y direction down.

  var iconImage = new google.maps.MarkerImage('mapIcons/marker_red.png',
      // This marker is 20 pixels wide by 34 pixels tall.
      new google.maps.Size(20, 34),
      // The origin for this image is 0,0.
      new google.maps.Point(0,0),
      // The anchor for this image is at 9,34.
      new google.maps.Point(9, 34));
  var iconShadow = new google.maps.MarkerImage('http://www.google.com/mapfiles/shadow50.png',
      // The shadow image is larger in the horizontal dimension
      // while the position and offset are the same as for the main image.
      new google.maps.Size(37, 34),
      new google.maps.Point(0,0),
      new google.maps.Point(9, 34));
      // Shapes define the clickable region of the icon.
      // The type defines an HTML &lt;area&gt; element 'poly' which
      // traces out a polygon as a series of X,Y points. The final
      // coordinate closes the poly by connecting to the first
      // coordinate.
  var iconShape = {
      coord: [9,0,6,1,4,2,2,4,0,8,0,12,1,14,2,16,5,19,7,23,8,26,9,30,9,34,11,34,11,30,12,26,13,24,14,21,16,18,18,16,20,12,20,8,18,4,16,2,15,1,13,0],
      type: 'poly'
  };


function createMarker(map, latlng, label, html, color,pid,visitstatus,pvid) {
// alert("createMarker("+latlng+","+label+","+html+","+color+")");
	var vs = visitstatus;
	var status = (vs == 1) ? 'New Order' : (vs == 2) ? 'Delivered' : (vs == 3) ? 'Skipped' : (vs == 4) ? 'Cancelled' : (vs == 5) ? 'Postponed' : (vs == 6) ? 'Returned' : 'Undelivered';
    var contentString = '<h2><b>'+label+'</b></h2><br>'+html+'<br> Status : '+status;
    var marker = new google.maps.Marker({
        position: latlng,
        map: map,
        shadow: iconShadow,
        icon: getMarkerImage(color,visitstatus),
        shape: iconShape,
        title: label,
		animation: google.maps.Animation.DROP,
        zIndex: Math.round(latlng.lat()*-100000)<<5,
		pid: pid,
		pvid:pvid,
        });
        marker.myname = label;

    google.maps.event.addListener(marker, 'mouseover', function() {
        infowindow.setContent(contentString);
        infowindow.open(map,marker);
    });

	google.maps.event.addListener(marker, 'click', function() {
      //alert(routeID+' '+marker.pid);
	    $('.sidebar').addClass('active');
		$('.toggle').addClass('active');
		// alert(marker.pvid);

		$.ajax({
				url: "/map/marker_info/",
				type: "POST",
				data: {VisitID:marker.pid,CustomerID:marker.pvid},
				//dataType:"JSON",
				headers: {
				    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				  },
				success: function (data, status) {

				const finaldt = data;
				// alert(finaldt['CustomerID']);
				// alert(finaldt[0]['CustomerID']);
				 var content = '';
				 var ctcontent = '';
				 var drcontent = '';
				 var vs = finaldt['VisitStatus'];
				 var status = (vs == 1) ? 'Pending' : (vs == 2) ? 'Completed' : (vs == 3) ? 'Skipped' : (vs == 4) ? 'Cancelled' : (vs == 5) ? 'Postponed' : 'Returned';

					content += "<p><b>Full Name:</b> "+finaldt['Name']+"</p>";
					content += "<p><b>Address:</b> "+finaldt['Address']+"</p> ";
					content += "<p><b>Phone no:</b> "+finaldt['Phone']+"</p> ";
					content += "<p><b>Status:</b> "+status+"</p> ";

					ctcontent += "<tr><td>Name</td><td>Qty</td><td>Price</td></tr> ";
					$.each(finaldt['visit'],function(ctkey,ctval){
						const inc = ctkey + 1;

						var color = '#FF0000';

						if(ctval.ServiceStaus == 'Completed'){
						var	color = "#008000";
						}

						ctcontent += "<tr><td><b> "+ctval['Goods']+' </b></td> <td><b> '+ctval['GoodsQty']+' </b></td> <td style="font-size:12px;color:'+color+';"> '+ctval['GoodsAmt']+"</td> ";

					});

					drcontent += "<p><b>Name:</b> "+finaldt['Driver']+"</p>";
					drcontent += "<p><b>Phone </b> "+finaldt['DriverPhone']+"</p> ";

				$('#patientinfoappend').html(content);
				$('#patientctstatusappend').html(ctcontent);
				$('#driverinfoappend').html(drcontent);
			  }
		  });


    });

    return marker;
}



