require('dotenv').config();

var socket_port = process.env.SOCKET_PORT;
var socket_channel = 'private-chat-channel';

var server = require('http').Server();
var io = require('socket.io')(server);
var Redis = require('ioredis');

var redis = new Redis();

redis.subscribe(socket_channel);

// Send Messages from Server to Client
redis.on('message', function(channel, message) {
    console.log("redis.on", channel, message);
    message = JSON.parse(message);
    io.emit(channel, message.data);
});

// Get Client Messages and BroadCast
io.on('connection', function(socket) {
    console.log("io.on.connection");

    socket.on(socket_channel, function(message){
        console.log("socket.on", message);
        // Emit commented to send messages via Laravel
        // io.emit(socket_channel, message);
    });
});

console.log("Starting Socket.IO Server on port "+socket_port+" and channel "+socket_channel+"... ");

server.listen({
    port: socket_port
});