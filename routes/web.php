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
    return view('welcome');
});

Auth::routes();

Route::get('/createRole', 'HomeController@createRole');

Route::get('/user', 'UserController@show');

Route::get('/home', 'HomeController@create');

Route::post('/tasks', 'HomeController@parkingReload');

Route::get('/home/create', 'HomeController@create');
Route::post('/home/store', 'HomeController@store');

/*
 * Uber controller
*/

Route::get('/uberlogin', function () {

    $query = http_build_query([
        'client_id' => 'klO9TBNgHsNgW6HsktMpzp0TUet3ekfk',
       'redirect_uri' => 'https://valetu.com/callback',
        'response_type' => 'code',
        'scope' => 'profile'
    ]);

    return redirect('https://login.uber.com/oauth/v2/authorize?'.$query);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::get('/callback', function (Illuminate\Http\Request $request) {
    $http = new \GuzzleHttp\Client;

    $response = $http->post('https://login.uber.com/oauth/v2/token', [
        'form_params' => [
            'client_id' => 'klO9TBNgHsNgW6HsktMpzp0TUet3ekfk',
            'client_secret' => 'GRhRu8aqUHOzNnqKRTZR2QkHrhfd09fxbLy0WSp3',
            'grant_type' => 'authorization_code',
            'redirect_uri' => 'https://valetu.com/callback',
            'code' => $request->code,
        ],
    ]);
   /* $responseString = json_decode($response->getBody(), 200);

     $response = $http->get('https://api.uber.com/v1/me', [
    	'headers' => [	
	        'Accept' => 'application/json',
	        'Authorization' => 'Bearer '.$responseString['access_token'],
        ],
    ]);*/

  //  return '<pre>' . json_encode((string) $response->getBody(), true) . '</pre>';
    return json_encode((string) $response->getBody(), true);
});

Route::post('/uber/v1/savetoken', 'UberController@savetoken');

Route::get('/uber/v1/findnearby', 'UberController@findnearby');