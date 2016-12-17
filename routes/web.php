<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    
    event(new App\Events\Message("UserJoined", "", date('Y-m-d H:i:s')));

    return view('welcome');
});

Route::post('send_message', function (\Illuminate\Http\Request $request) {
    event(new App\Events\Message($request->type, $request->content, date('Y-m-d H:i:s')));
    
    return response()->json([
        'status' => 'success'
    ]);
});
