require('dotenv').config();

var socket_port = process.env.SOCKET_PORT;
var socket_channel = 'private-chat-channel';

var server = require('http').Server();
var io = require('socket.io')(server);
var Redis = require('ioredis');

var redis = new Redis();

redis.subscribe(socket_channel);

redis.on('message', function(channel, message) {
    console.log(channel, message);
    message = JSON.parse(message);
    io.emit(channel, message);
});

console.log("Starting Socket.IO Server on port "+socket_port+" and channel "+socket_channel+"... ");

server.listen({
    port: socket_port
});