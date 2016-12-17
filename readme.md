# Laravel 5.3 Chat application using Socket.IO, Redis, Vue.js and Event Broadcasting

This is simple chat application using standard libraries except 'laravel-echo-server' which I think completely unnecessary.

## Installation

Clone this repository into your machine.

```
git clone https://github.com/gdbhosale/chat-laravel-socketio-redis-vuejs.git
```

Install Laravel
```
composer install
```

Make sure you have your `.env` configuration as below:
```
SOCKET_PORT=3000

BROADCAST_DRIVER=redis
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_DRIVER=redis
```

Install Redis server
```
sudo apt-get install redis-server
```

Run Redis server in new terminal window / tab
```
redis-server
```

This application needs Laravel Queue to Run Events Broadcasting. Make sure you have used `QUEUE_DRIVER=redis`.
Run Laravel Queue Command in new terminal window / tab
```
php artisan queue:listen
```

Now you need to download Socket.IO server and other dependencies via npm.
```
npm install
```

Now start Socket.IO server
```
node socket.js
```

Now Check your application in Browser.

Follow me on [Twitter](https://twitter.com/gdbhosale) or [Github](https://github.com/gdbhosale). Check Laravel Admin Panel created by me: [LaraAdmin](http://laraadmin.com)
