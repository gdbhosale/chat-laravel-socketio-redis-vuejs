# Laravel 5.3 Chat application - Basic Changes Required

Here i have given all necessary changes to be done to Make Basic Socket.IO application work

#### composer.json
```
"require-dev": {
    ...
    "predis/predis": "^1.1"
},
```

#### .env
```
SOCKET_PORT=3000

BROADCAST_DRIVER=redis
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_DRIVER=redis
```

#### app/Events/Message.php
```
<?php
namespace App\Events;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Message implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;
    public $type;
    public $content;
    public $time;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($type, $content, $time)
    {
        $this->type = $type;
        $this->content = $content;
        $this->time = $time;
    }
    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        \Log::log('debug', 'Message::broadcastOn - chat-channel - '.json_encode($this));
        return new PrivateChannel('chat-channel');
    }
}
```

#### package.json
```
"dependencies": {
    "express": "~4.14.0",
    "socket.io": "~1.7.2",
    "ioredis": "^2.4.3",
    "dotenv": "^2.0.0"
}
```

#### public/plugins/socket.io/*
#### public/plugins/vue.js/*

#### resources/views/welcome.blade.php
```
<!DOCTYPE html>
<html lang="en">
<head>    
</head>
<body>
<h1>Messages</h1>
<ul id="message-list">
    <li v-for="message in messages">@{{ message }}</li>
</ul>
<script src="{{ asset('plugins/vue.js/vue.min.js') }}"></script>
<script src="{{ asset('plugins/socket.io/socket.io.min.js') }}"></script>
<script language="JavaScript">
var socket_port = {{ env('SOCKET_PORT') }};
var socket_host = 'http://127.0.0.1';
var socket_channel = 'private-chat-channel';

var socket = io(socket_host + ":" + socket_port);

new Vue({
    el: '#message-list',
    data: {
        messages: []
    },
    mounted: function() {
        this.$nextTick(function () {
            console.log("Setting socket on "+socket_host+":"+socket_port+" with channel "+socket_channel+"...");

            socket.on(socket_channel, function(event) {
                console.log(event);

                this.messages.push(event.data.type+" "+event.data.content+" "+event.data.time);
            }.bind(this));
        });
    }
});
</script>
</body>
</html>
```

#### routes/web.php
```
Route::get('/', function () {
    
    event(new App\Events\Message("UserJoined", "", date('Y-m-d H:i:s')));

    return view('welcome');
});
```

#### socket.js
```
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
```

Follow me on [Twitter](https://twitter.com/gdbhosale) or [Github](https://github.com/gdbhosale). Check Laravel Admin Panel created by me: [LaraAdmin](http://laraadmin.com)
