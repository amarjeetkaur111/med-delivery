const express = require('express');
const res = require('express/lib/response');
const app = express();
const server = require('http').createServer(app);
const io = require('socket.io')(server, { cors: { origin: "*" } });
require('dotenv').config({ path: '../.env' });

const PORT = process.env.SOCKET_URL_PORT || 8080;

app.set('view engine', 'ejs');

app.get('/', (req, res) => {
    res.render('home');
})

server.listen(PORT, () => {
    console.log('Server running..', PORT)
})

io.on('connection', (socket) => {
    console.log("User Connected: " + socket.id);


    socket.on('join_map', (datas) => {
        console.log("datas", datas)
            // console.log(socket.id);

        if (datas.map) {
            var route_id = datas.RouteId;
            //console.log(datas.RouteId);
        } else {
            //var data = 
            var data = datas;
            var route_id = data.RouteId;
        }

        // var data = { "RouteId": 49, "UserId": 17, "lat": "51.04430800", "lng": "-114.06309140" };

        // const mapconnectID = route_id;

        //console.log(mapconnectID);

        // socket.join(mapconnectID);

        if (data) {

            var centerLat = data.lat;
            var centerLng = data.lng;
            var UserId = data.UserId;

            var latlng = {
                lat: centerLat,
                lng: centerLng,
                driver_id: UserId
            }

            console.log(latlng);
            // send event including me
            socket.broadcast.emit('coordinated_receive', latlng);

        }
        /* socket.on('clicked_on_map', latlng=> {
                 console.log(latlng);
                 socket.broadcast.to(mapconnectID).emit('coordinated_receive',latlng);
             }) */

        socket.on('map_updated', riddata => {
            //console.log(riddata);
            socket.broadcast.emit('map_reload', riddata);
        })


    })
})