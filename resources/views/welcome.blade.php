<!DOCTYPE html>
<html lang="en">
<head>
    <title>Chat with Laravel + Socket.IO</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.2/css/bootstrap.min.css" integrity="sha384-y3tfxAZXuh4HwSYylfB+J125MxIs6mR5FOHamPBG064zB+AFeWH94NdvaCBm8qnd" crossorigin="anonymous">
    <style>
    .list-group.chatlist {
        display: block;
        position: absolute;
        width: 100%;
        height: 100%;
        padding-bottom: 60px;
        overflow: scroll;
    }
    .container-fluid.message {
        width: 100%;
        display: block;
        position: fixed;
        bottom: 0px;
        padding: 10px;
        background: antiquewhite;
    }
    </style>
</head>
<body>

<div id="ChatApp">
    <ul class="list-group chatlist">
        <li class="list-group-item" v-for="message in messages">@{{ message.type+" "+message.content }}</li>
    </ul>
    <div class="container-fluid message">
        <div class="Chat col-md-12">
            <form v-on:submit.prevent="send" class="Chat--focused input-group">
                <input v-model="message" type="text" class="form-control Chat__textArea" placeholder="Send your message!">
                <span class="input-group-btn">
                    <button class="btn btn-secondary Chat__submitButton" type="submit">Send</button>
                </span>
            </form>
        </div>
    </div>
</div>


<script src="{{ asset('plugins/vue.js/vue.min.js') }}"></script>
<script src="{{ asset('plugins/socket.io/socket.io.min.js') }}"></script>
<script language="JavaScript">
var socket_port = {{ env('SOCKET_PORT') }};
var socket_host = 'http://127.0.0.1';
var socket_channel = 'private-chat-channel';

var socket = io(socket_host + ":" + socket_port);

var chatApp = new Vue({
    el: '#ChatApp',
    data: {
        message: '',
        messages: []
    },
    mounted: function() {
        this.$nextTick(function () {
            console.log("Setting socket on "+socket_host+":"+socket_port+" with channel "+socket_channel+"...");

            socket.on(socket_channel, function(event) {
                console.log(event);

                this.messages.push(event);

                // Scroll down on DOM Update after Push
                this.$nextTick(function () {
                    var container = this.$el.querySelector(".chatlist");
                    console.log("scrollToBottom", container.scrollTop, container.clientHeight, container.scrollHeight);
                    container.scrollTop = container.scrollHeight;
                });
            }.bind(this));
        });
    },
    methods: {
        send: function(event) {
            var message_obj = {
                type: "UserMessage",
                content: this.message,
                time: ''
            };
            socket.emit(socket_channel, message_obj);
            this.message = '';
        }
    }
});
</script>
</body>
</html>
