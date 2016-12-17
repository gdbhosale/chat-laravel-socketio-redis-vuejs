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
