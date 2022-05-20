jQuery(function() {

    init();

    $('.contact_close').click(function() {
        $('.sidebar').removeClass('active')
        $('.toggle').removeClass('active')
    });

});

const socket = io(socket_url);
var mapid = { RouteId: 1, map: 1 };

socket.emit("join_map", mapid);
socket.on('coordinated_receive', data => {
    console.log("coordinated_receive", data);
    var ik = driver_ids.indexOf(parseInt(data.driver_id));
    console.log("ik", ik);
    if (ik != -1)
        startAnimation(ik, data);
    // placeMarkerAndPanTo(data, map);
});
// Initialise some variables
var directionsService;
var num, map, data, traceMarker, poly = [];
var requestArray = [],
    renderArray = [];
var map;
var directionDisplay;
var stepDisplay;
var markerArray = [];
var position;
var marker = [];
var polyline = [];
var poly2 = [];
var speed = 0.000005,
    wait = 1;
var endLocation = [];
var infowindow = null;
var timerHandle = null;
var driver_location = [{
    lat: 52.2751124,
    lng: -113.8211762
}, {
    lat: 52.2766062,
    lng: -113.8443059
}, {
    lat: 52.2786197,
    lng: -113.8458752
}];
var iconShadow = new google.maps.MarkerImage('http://www.google.com/mapfiles/shadow50.png',
    // The shadow image is larger in the horizontal dimension
    // while the position and offset are the same as for the main image.
    new google.maps.Size(37, 34),
    new google.maps.Point(0, 0),
    new google.maps.Point(9, 34));

// A JSON Array containing some people/routes and the destinations/stops
var jsonArray = locations_og;

// var jsonArray = {
//     "Person 1": [{
//         lat: 52.275345,
//         lng: -113.821277
//     }, {
//         lat: 52.279801,
//         lng: -113.824587
//     }, {
//         lat: 52.279031,
//         lng: -113.834750
//     }],
//     "Person 2": [{
//         lat: 52.276735,
//         lng: -113.844260
//     }, {
//         lat: 52.275113,
//         lng: -113.844746
//     }, {
//         lat: 52.273695,
//         lng: -113.846576
//     }, {
//         lat: 52.271844,
//         lng: -113.843662
//     }],
//     "Person 3": [{
//         lat: 52.278541,
//         lng: -113.845904
//     }, {
//         lat: 52.279455,
//         lng: -113.845530
//     }, {
//         lat: 52.281878,
//         lng: -113.845082
//     }, {
//         lat: 52.284323,
//         lng: -113.844372
//     }]
// }

// 16 Standard Colours for navigation polylines
var colourArray = ['red', 'grey', 'green', 'black', 'white', 'lime', 'maroon', 'purple', 'aqua', 'silver', 'olive', 'blue', 'yellow', 'teal'];
var colourArrayNew = ['green', 'red', 'black', 'green', 'lime', 'white', 'purple', 'maroon', 'silver', 'aqua', 'blue', 'olive', 'teal', 'yellow'];


function createMarker(map, latlng, label, html, color, pid, visitstatus, pvid) {
    console.log(html);
    // alert("createMarker(" + latlng + "," + label + "," + html + "," + color + ")");
    var vs = visitstatus;
    var status = (vs == 1) ? 'New Order' : (vs == 2) ? 'Delivered' : (vs == 3) ? 'Skipped' : (vs == 4) ? 'Cancelled' : (vs == 5) ? 'Postponed' : (vs == 6) ? 'Returned' : 'Undelivered';
    var contentString = '<h2><b>' + label + '</b></h2><br>' + html + '<br> Status : ' + status;
    var marker_1 = new google.maps.Marker({
        position: latlng,
        map: map,
        shadow: iconShadow,
        icon: getMarkerImage(color, visitstatus),
        shape: iconShape,
        title: label,
        animation: google.maps.Animation.DROP,
        zIndex: Math.round(latlng.lat() * -100000) << 5,
        pid: pid,
        pvid: pvid,
    });
    marker_1.myname = label;

    google.maps.event.addListener(marker_1, 'mouseover', function() {
        infowindow.setContent(contentString);
        infowindow.open(map, marker_1);
    });

    google.maps.event.addListener(marker_1, 'click', function() {
        //alert(routeID+' '+marker.pid);
        $('.sidebar').addClass('active');
        $('.toggle').addClass('active');
        // alert(marker.pvid);

        $.ajax({
            url: "/map/marker_info/",
            type: "POST",
            data: { VisitID: marker_1.pid, CustomerID: marker_1.pvid },
            //dataType:"JSON",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(data, status) {

                const finaldt = data;
                // alert(finaldt['CustomerID']);
                // alert(finaldt[0]['CustomerID']);
                var content = '';
                var ctcontent = '';
                var drcontent = '';
                var vs = finaldt['VisitStatus'];
                var status = (vs == 1) ? 'New Order' : (vs == 2) ? 'Delivered' : (vs == 3) ? 'Skipped' : (vs == 4) ? 'Cancelled' : (vs == 5) ? 'Postponed' : (vs == 6) ? 'Returned' : 'Undelivered';

                content += "<p><b>Full Name:</b> " + finaldt['Name'] + "</p>";
                content += "<p><b>Address:</b> " + finaldt['Address'] + "</p> ";
                content += "<p><b>Phone no:</b> " + finaldt['Phone'] + "</p> ";
                content += "<p><b>Status:</b> " + status + "</p> ";

                ctcontent += "<tr><td>Name</td><td>Qty</td><td>Price</td></tr> ";
                $.each(finaldt['visit'], function(ctkey, ctval) {
                    const inc = ctkey + 1;

                    var color = '#FF0000';

                    if (ctval.ServiceStaus == 'Completed') {
                        var color = "#008000";
                    }

                    ctcontent += "<tr><td><b> " + ctval['Goods'] + ' </b></td> <td><b> ' + ctval['GoodsQty'] + ' </b></td> <td style="font-size:12px;color:' + color + ';"> ' + ctval['GoodsAmt'] + "</td> ";

                });

                drcontent += "<p><b>Name:</b> " + finaldt['Driver'] + "</p>";
                drcontent += "<p><b>Phone </b> " + finaldt['DriverPhone'] + "</p> ";

                $('#patientinfoappend').html(content);
                $('#patientctstatusappend').html(ctcontent);
                $('#driverinfoappend').html(drcontent);
            }
        });


    });

    return marker_1;
}




// Let's make an array of requests which will become individual polylines on the map.
function generateRequests() {

    requestArray = [];

    for (var route in jsonArray) {
        // This now deals with one of the people / routes

        // Somewhere to store the wayoints
        var waypts = [];

        // 'start' and 'finish' will be the routes origin and destination
        var start, finish

        // lastpoint is used to ensure that duplicate waypoints are stripped
        var lastpoint

        data = jsonArray[route]

        limit = data.length
        for (var waypoint = 0; waypoint < limit; waypoint++) {
            if (data[waypoint] === lastpoint) {
                // Duplicate of of the last waypoint - don't bother
                continue;
            }

            // Prepare the lastpoint for the next loop
            lastpoint = data[waypoint]

            // Add this to waypoint to the array for making the request
            waypts.push({
                location: {
                    'lat': parseFloat(data[waypoint]['lat']),
                    'lng': parseFloat(data[waypoint]['lng'])
                },
                stopover: true,
            });
        }

        // Grab the first waypoint for the 'start' location
        start = (waypts.shift()).location;
        // Grab the last waypoint for use as a 'finish' location
        finish = waypts.pop();
        if (finish === undefined) {
            // Unless there was no finish location for some reason?
            finish = start;
        } else {
            finish = finish.location;
        }

        // Let's create the Google Maps request object
        var request = {
            origin: start,
            destination: finish,
            waypoints: waypts,
            travelMode: google.maps.TravelMode.DRIVING
        };
        console.log('request', request);
        // and save it in our requestArray
        requestArray.push({
            "route": route,
            "request": request
        });
    }

    processRequests();
}

function processRequests() {

    // Counter to track request submission and process one at a time;
    var i = 0;


    // Used to submit the request 'i'
    function submitRequest() {
        directionsService.route(requestArray[i].request, directionResults);
    }

    // Used as callback for the above request for current 'i'
    function directionResults(response, status) {
        console.log("i", i);
        var path = new google.maps.MVCArray();
        if (status == google.maps.DirectionsStatus.OK) {
            polyline.push(new google.maps.Polyline({
                path: [],
                strokeColor: colourArray[i],
                strokeWeight: 3
            }));
            poly2.push(new google.maps.Polyline({
                path: [],
                strokeColor: colourArray[i],
                strokeWeight: 3
            }));
            // Create a unique DirectionsRenderer 'i'
            renderArray[i] = new google.maps.DirectionsRenderer();
            // renderArray[i].setMap(map);

            // Some unique options from the colorArray so we can see the routes
            renderArray[i].setOptions({
                preserveViewport: true,
                suppressInfoWindows: true,
                polylineOptions: {
                    strokeWeight: 4,
                    strokeOpacity: 0.8,
                    strokeColor: colourArray[i]
                },
            });

            // Use this new renderer with the response
            renderArray[i].setDirections(response);
            // and start the next request
            // nextRequest();


            // directionsDisplay.setDirections(response);

            var bounds = new google.maps.LatLngBounds();
            var route = response.routes[0];
            startLocation = new Object();
            endLocation[i] = new Object();

            // For each route, display summary information.
            var path = response.routes[0].overview_path;
            var legs = response.routes[0].legs;
            var driver_id = "Driver_" + driver_ids[i];
            for (ik = 0; ik < legs.length; ik++) {
                if (ik === 0) {
                    startLocation.latlng = legs[ik].start_location;
                    startLocation.address = legs[ik].start_address;
                    //   marker = createMarker(legs[i].start_location, "start", legs[i].start_address, "green");
                }
                endLocation[i].latlng = legs[ik].end_location;
                endLocation[i].address = legs[ik].end_address;
                var steps = legs[ik].steps;
                for (j = 0; j < steps.length; j++) {
                    var nextSegment = steps[j].path;
                    for (k = 0; k < nextSegment.length; k++) {
                        polyline[i].getPath().push(nextSegment[k]);
                        bounds.extend(nextSegment[k]);
                    }
                }
                var markerletter = "A".charCodeAt(0);
                // markerletter += ik;
                // markerletter = String.fromCharCode(markerletter);                                
                // markerletter = ik + 1;
                legslat = Math.round(legs[ik].start_location.lat() * 100) / 100;
                legslng = Math.round(legs[ik].start_location.lng() * 100) / 100;
                console.log('legslat', legslat);
                for (var ov = 0; ov < jsonArray[driver_id].length; ov++) {
                    otherlat = Math.round(jsonArray[driver_id][ov].lat * 100) / 100;
                    otherlng = Math.round(jsonArray[driver_id][ov].lng * 100) / 100;
                    console.log('otherlat', otherlat);
                    if (legslat == otherlat && legslng == otherlng) {
                        markerletter = jsonArray[driver_id][ov].VisitID;
                        console.log(legs[ik].start_location + " " + jsonArray[driver_id][ov].Name + " " + jsonArray[driver_id][ov].Time + "<br>" + legs[ik].start_address + " " + markerletter + " " + jsonArray[driver_id][ov].VisitID + " " + jsonArray[driver_id][ov].VisitStatusID + " " + jsonArray[driver_id][ov].CustomerID);
                        createMarker(directionsDisplay.getMap(), legs[ik].start_location, jsonArray[driver_id][ov].Name, jsonArray[driver_id][ov].Time + "<br>" + legs[ik].start_address, markerletter, jsonArray[driver_id][ov].VisitID, jsonArray[driver_id][ov].VisitStatusID, jsonArray[driver_id][ov].CustomerID);
                        break;
                    }
                }
            }

            var ik = legs.length;
            var markerletter = "A".charCodeAt(0);
            // markerletter += ik;
            //markerletter = String.fromCharCode(markerletter);
            // markerletter = ik + 1;
            legslat = Math.round(legs[legs.length - 1].end_location.lat() * 100) / 100;
            legslng = Math.round(legs[legs.length - 1].end_location.lng() * 100) / 100;
            console.log('legslat', legslat);
            for (var ov = 0; ov < jsonArray[driver_id].length; ov++) {
                otherlat = Math.round(jsonArray[driver_id][ov].lat * 100) / 100;
                otherlng = Math.round(jsonArray[driver_id][ov].lng * 100) / 100;
                if (legslat == otherlat && legslng == otherlng) {
                    console.log(legs[legs.length - 1].end_location + " " + jsonArray[driver_id][ov].Name + " " + jsonArray[driver_id][ov].Time + "<br>" + legs[legs.length - 1].end_address + " " + markerletter + " " + jsonArray[driver_id][ov].VisitID + " " + jsonArray[driver_id][ov].VisitStatusID + " " + jsonArray[driver_id][ov].CustomerID);
                    markerletter = jsonArray[driver_id][ov].VisitID;
                    createMarker(directionsDisplay.getMap(), legs[legs.length - 1].end_location, jsonArray[driver_id][ov].Name, jsonArray[driver_id][ov].Time + "<br>" + legs[legs.length - 1].end_address, markerletter, jsonArray[driver_id][ov].VisitID, jsonArray[driver_id][ov].VisitStatusID, jsonArray[driver_id][ov].CustomerID);
                }
            }
            polyline[i].setMap(map);
            if (i == 0)
                map.fitBounds(bounds);
            // map.setZoom(18);
            // startAnimation(i);
            console.log("Next")
            nextRequest();

        }

    }

    function nextRequest() {
        // Increase the counter
        i++;
        // Make sure we are still waiting for a request
        if (i >= requestArray.length) {
            // No more to do
            return;
        }
        // Submit another request
        submitRequest();
    }

    // This request is just to kick start the whole process
    submitRequest();
}

// Called Onload
function init() {

    infowindow = new google.maps.InfoWindow({
        size: new google.maps.Size(150, 50)
    });
    // Instantiate a directions service.
    directionsService = new google.maps.DirectionsService();
    // Some basic map setup (from the API docs)
    var mapOptions = {
        center: {
            lat: 52.278747,
            lng: -113.838134
        },
        zoom: 10,
        mapTypeControl: false,
        streetViewControl: false,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById('patientmap'), mapOptions);

    // Create a renderer for directions and bind it to the map.
    var rendererOptions = {
        map: map
    };
    directionsDisplay = new google.maps.DirectionsRenderer(rendererOptions);

    // Instantiate an info window to hold step text.
    stepDisplay = new google.maps.InfoWindow();

    // poly2 = new google.maps.Polyline({
    //     path: [],
    //     strokeColor: '#FF0000',
    //     strokeWeight: 3
    // });
    // Start the request making
    var chartimage = (document.getElementById('chartimg'));
    map.controls[window.google.maps.ControlPosition.RIGHT_TOP].push(chartimage);

    var input = (document.getElementById('route-input'));
    map.controls[window.google.maps.ControlPosition.TOP_LEFT].push(input);
    generateRequests()
}

// Get the ball rolling and trigger our init() on 'load'
// google.maps.event.addDomListener(window, 'load', init);


var step = 50; // 5; // metres
var tick = 200; // milliseconds
var eol = [];
var k = 0;
var stepnum = 0;
var speed = "";
var lastVertex = 1;

//=============== animation functions ======================
function updatePoly(d, i) {
    // Spawn a new polyline every 20 vertices, because updating a 100-vertex poly is too slow
    if (poly2[i].getPath().getLength() > 20) {
        poly2[i] = new google.maps.Polyline([polyline[i].getPath().getAt(lastVertex - 1)]);
        // map.addOverlay(poly2)
    }

    if (polyline[i].GetIndexAtDistance(d) < lastVertex + 2) {
        if (poly2[i].getPath().getLength() > 1) {
            poly2[i].getPath().removeAt(poly2[i].getPath().getLength() - 1);
        }
        poly2[i].getPath().insertAt(poly2[i].getPath().getLength(), polyline[i].GetPointAtDistance(d));
    } else {
        poly2[i].getPath().insertAt(poly2[i].getPath().getLength(), endLocation[i].latlng);
    }
}

function animate(d, i, data) {
    var icon_new = {
            path: car,
            scale: .7,
            strokeColor: 'white',
            strokeWeight: .10,
            fillOpacity: 1,
            fillColor: colourArrayNew[i],
            offset: '5%',
            // rotation: parseInt(heading[i]),
            anchor: new google.maps.Point(10, 25) // orig 10,50 back of car, 10,0 front of car, 10,25 center of car
        }
        // console.log("d", d);
    if (d > eol[i]) {
        // map.panTo(endLocation.latlng);
        marker[i].setPosition(endLocation[i].latlng);
        return;
    }
    var p = polyline[i].GetPointAtDistance(d);
    // map.panTo(p);
    var latlng = new google.maps.LatLng(data.lat, data.lng);
    data.lng = p.lng();
    data.lat = p.lat();
    // console.log("latlng", p);
    var lastPosn = marker[i].getPosition();
    marker[i].setPosition(latlng);
    var heading = google.maps.geometry.spherical.computeHeading(lastPosn, latlng);
    icon.rotation = heading;
    marker[i].setIcon(icon);
    updatePoly(d, i);
    // timerHandle = setTimeout("animate(" + (d + step) + "," + i + "," + JSON.stringify(data) + ")", tick);
}

function startAnimation(i, data) {
    var icon_new = {
        path: car,
        scale: .7,
        strokeColor: 'white',
        strokeWeight: .10,
        fillOpacity: 1,
        fillColor: colourArrayNew[i],
        offset: '5%',
        // rotation: parseInt(heading[i]),
        anchor: new google.maps.Point(10, 25) // orig 10,50 back of car, 10,0 front of car, 10,25 center of car
    }
    eol[i] = (polyline[i].Distance());
    // map.setCenter(polyline[i].getPath().getAt(0));
    if (marker[i] == undefined)
        marker[i] = (new google.maps.Marker({
            position: polyline[i].getPath().getAt(0),
            map: map,
            icon: icon
        }));

    poly2[i] = new google.maps.Polyline({
        path: [polyline[i].getPath().getAt(0)],
        strokeColor: colourArray[i],
        strokeWeight: 10
    });
    // map.addOverlay(poly2);
    setTimeout("animate(50," + i + "," + JSON.stringify(data) + ")", 2000); // Allow time for the initial map display
}

//=============== ~animation funcitons =====================

var car = "M17.402,0H5.643C2.526,0,0,3.467,0,6.584v34.804c0,3.116,2.526,5.644,5.643,5.644h11.759c3.116,0,5.644-2.527,5.644-5.644 V6.584C23.044,3.467,20.518,0,17.402,0z M22.057,14.188v11.665l-2.729,0.351v-4.806L22.057,14.188z M20.625,10.773 c-1.016,3.9-2.219,8.51-2.219,8.51H4.638l-2.222-8.51C2.417,10.773,11.3,7.755,20.625,10.773z M3.748,21.713v4.492l-2.73-0.349 V14.502L3.748,21.713z M1.018,37.938V27.579l2.73,0.343v8.196L1.018,37.938z M2.575,40.882l2.218-3.336h13.771l2.219,3.336H2.575z M19.328,35.805v-7.872l2.729-0.355v10.048L19.328,35.805z";
var icon = {
    path: car,
    scale: .7,
    strokeColor: 'white',
    strokeWeight: .10,
    fillOpacity: 1,
    fillColor: '#404040',
    offset: '5%',
    // rotation: parseInt(heading[i]),
    anchor: new google.maps.Point(10, 25) // orig 10,50 back of car, 10,0 front of car, 10,25 center of car
};

// === first support methods that don't (yet) exist in v3
google.maps.LatLng.prototype.distanceFrom = function(newLatLng) {
    var EarthRadiusMeters = 6378137.0; // meters
    var lat1 = this.lat();
    var lon1 = this.lng();
    var lat2 = newLatLng.lat();
    var lon2 = newLatLng.lng();
    var dLat = (lat2 - lat1) * Math.PI / 180;
    var dLon = (lon2 - lon1) * Math.PI / 180;
    var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) * Math.sin(dLon / 2) * Math.sin(dLon / 2);
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    var d = EarthRadiusMeters * c;
    return d;
}

google.maps.LatLng.prototype.latRadians = function() {
    return this.lat() * Math.PI / 180;
}

google.maps.LatLng.prototype.lngRadians = function() {
    return this.lng() * Math.PI / 180;
}

// === A method which returns the length of a path in metres ===
google.maps.Polygon.prototype.Distance = function() {
    var dist = 0;
    for (var i = 1; i < this.getPath().getLength(); i++) {
        dist += this.getPath().getAt(i).distanceFrom(this.getPath().getAt(i - 1));
    }
    return dist;
}

// === A method which returns a GLatLng of a point a given distance along the path ===
// === Returns null if the path is shorter than the specified distance ===
google.maps.Polygon.prototype.GetPointAtDistance = function(metres) {
    // some awkward special cases
    if (metres == 0) return this.getPath().getAt(0);
    if (metres < 0) return null;
    if (this.getPath().getLength() < 2) return null;
    var dist = 0;
    var olddist = 0;
    for (var i = 1;
        (i < this.getPath().getLength() && dist < metres); i++) {
        olddist = dist;
        dist += this.getPath().getAt(i).distanceFrom(this.getPath().getAt(i - 1));
    }
    if (dist < metres) {
        return null;
    }
    var p1 = this.getPath().getAt(i - 2);
    var p2 = this.getPath().getAt(i - 1);
    var m = (metres - olddist) / (dist - olddist);
    return new google.maps.LatLng(p1.lat() + (p2.lat() - p1.lat()) * m, p1.lng() + (p2.lng() - p1.lng()) * m);
}

// === A method which returns an array of GLatLngs of points a given interval along the path ===
google.maps.Polygon.prototype.GetPointsAtDistance = function(metres) {
    var next = metres;
    var points = [];
    // some awkward special cases
    if (metres <= 0) return points;
    var dist = 0;
    var olddist = 0;
    for (var i = 1;
        (i < this.getPath().getLength()); i++) {
        olddist = dist;
        dist += this.getPath().getAt(i).distanceFrom(this.getPath().getAt(i - 1));
        while (dist > next) {
            var p1 = this.getPath().getAt(i - 1);
            var p2 = this.getPath().getAt(i);
            var m = (next - olddist) / (dist - olddist);
            points.push(new google.maps.LatLng(p1.lat() + (p2.lat() - p1.lat()) * m, p1.lng() + (p2.lng() - p1.lng()) * m));
            next += metres;
        }
    }
    return points;
}

// === A method which returns the Vertex number at a given distance along the path ===
// === Returns null if the path is shorter than the specified distance ===
google.maps.Polygon.prototype.GetIndexAtDistance = function(metres) {
        // some awkward special cases
        if (metres == 0) return this.getPath().getAt(0);
        if (metres < 0) return null;
        var dist = 0;
        var olddist = 0;
        for (var i = 1;
            (i < this.getPath().getLength() && dist < metres); i++) {
            olddist = dist;
            dist += this.getPath().getAt(i).distanceFrom(this.getPath().getAt(i - 1));
        }
        if (dist < metres) {
            return null;
        }
        return i;
    }
    // === Copy all the above functions to GPolyline ===
google.maps.Polyline.prototype.Distance = google.maps.Polygon.prototype.Distance;
google.maps.Polyline.prototype.GetPointAtDistance = google.maps.Polygon.prototype.GetPointAtDistance;
google.maps.Polyline.prototype.GetPointsAtDistance = google.maps.Polygon.prototype.GetPointsAtDistance;
google.maps.Polyline.prototype.GetIndexAtDistance = google.maps.Polygon.prototype.GetIndexAtDistance;

var icons = new Array();
icons["red"] = new google.maps.MarkerImage("mapIcons/marker_red.png",
    // This marker is 20 pixels wide by 34 pixels tall.
    new google.maps.Size(20, 34),
    // The origin for this image is 0,0.
    new google.maps.Point(0, 0),
    // The anchor for this image is at 9,34.
    new google.maps.Point(9, 34));
var iconShape = {
    coord: [9, 0, 6, 1, 4, 2, 2, 4, 0, 8, 0, 12, 1, 14, 2, 16, 5, 19, 7, 23, 8, 26, 9, 30, 9, 34, 11, 34, 11, 30, 12, 26, 13, 24, 14, 21, 16, 18, 18, 16, 20, 12, 20, 8, 18, 4, 16, 2, 15, 1, 13, 0],
    type: 'poly'
};

function getMarkerImage(iconStr, visitstatus) {

    if ((typeof(iconStr) == "undefined") || (iconStr == null)) {
        iconStr = "red";
    }
    if (!icons[iconStr]) {
        var markerimg = "https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=" + iconStr + "%7Cff776b";

        if (visitstatus == 1) { /* In-complete */
            // var markerimg = "https://www.googlemapsmarkers.com/v1/"+ iconStr +"/ff776b/";
            var color = 'ff776b';
            var markerimg = "https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=" + iconStr + "%7Cff776b";
        }
        if (visitstatus == 2) { /* complete */
            //var markerimg = "https://www.googlemapsmarkers.com/v1/"+ iconStr +"/009900/";
            var color = '00ff00';
            var markerimg = "https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=" + iconStr + "%7C00ff00";
        }
        if (visitstatus == 3) { /* Skip */
            //var markerimg = "https://www.googlemapsmarkers.com/v1/"+ iconStr +"/f9f900/";
            var color = '0099ff';
            var markerimg = "https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=" + iconStr + "%7C0099ff";
        }
        if (visitstatus == 4) { /* Cancel */
            //var markerimg = "https://www.googlemapsmarkers.com/v1/"+ iconStr +"/0099FF/";
            var color = ' cccccc';
            var markerimg = "https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=" + iconStr + "%7CCCCCCC";
        }
        if (visitstatus == 5) { /* Postpone */
            //var markerimg = "https://www.googlemapsmarkers.com/v1/"+ iconStr +"/0099FF/";
            var color = 'ff33cc';
            var markerimg = "https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=" + iconStr + "%7Cff33cc";
        }
        if (visitstatus == 6) { /* Return */
            //var markerimg = "https://www.googlemapsmarkers.com/v1/"+ iconStr +"/0099FF/";
            var color = 'f9f900';
            var markerimg = "https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=" + iconStr + "%7Cf9f900";
        }
        if (visitstatus == 7) { /* Return */
            //var markerimg = "https://www.googlemapsmarkers.com/v1/"+ iconStr +"/0099FF/";
            var color = 'fff';
            var markerimg = "https://chart.googleapis.com/chart?chst=d_map_pin_letter&chld=" + iconStr + "%7Cfff";
        }

        icons[iconStr] = new google.maps.MarkerImage(markerimg,

            // This marker is 20 pixels wide by 34 pixels tall.
            new google.maps.Size(20, 34),
            // The origin for this image is 0,0.
            new google.maps.Point(0, 0),
            // The anchor for this image is at 6,20.
            new google.maps.Point(9, 34));
    }
    //alert(JSON.stringify(icons[iconStr]);
    return icons[iconStr];

}