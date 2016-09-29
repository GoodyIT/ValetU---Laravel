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


Route::get('/uploadfile', 'FileuploadingController@index');
Route::post('/uploadfile', 'FileuploadingController@showfileupload');

/*
 * Uber controller
*/

Route::get('/uberlogin', function () {

    $query = http_build_query([
        'client_id' => 'klO9TBNgHsNgW6HsktMpzp0TUet3ekfk',
       'redirect_uri' => 'http://localhost:8000/callback',
        'response_type' => 'code',
        'scope' => 'request'
    ]);

    return redirect('https://login.uber.com/oauth/v2/authorize?'.$query);
});


Route::get('/callback', function (Illuminate\Http\Request $request) {
    $http = new \GuzzleHttp\Client;

    $response = $http->post('https://login.uber.com/oauth/v2/token', [
        'form_params' => [
            'client_id' => 'klO9TBNgHsNgW6HsktMpzp0TUet3ekfk',
            'client_secret' => 'GRhRu8aqUHOzNnqKRTZR2QkHrhfd09fxbLy0WSp3',
            'grant_type' => 'authorization_code',
            'redirect_uri' => 'http://localhost:8000/callback',
            'code' => $request->code,
        ],
    ]);
/*    $responseString = json_decode($response->getBody(), 200);

     $response = $http->get('https://api.uber.com/v1/me', [
    	'headers' => [	
	        'Accept' => 'application/json',
	        'Authorization' => 'Bearer '.$responseString['access_token'],
        ],
    ]);

  //  return '<pre>' . json_encode((string) $response->getBody(), true) . '</pre>';*/
    return json_encode((string) $response->getBody(), true);
});

Route::get('/price', function (Illuminate\Http\Request $request) {
    $http = new \GuzzleHttp\Client;

    $response = $http->get('https://api.uber.com/v1/estimates/price', [
        'form_params' => [
            'start_latitude' => '37.625732',
            'start_longitude' => '-122.377807',
            'end_latitude' => '37.785',
            'end_longitude' => '-122.406677',
            'server_token' => 'VcR8_A-Xex3YhVGTUvjDWBQhDa3ygeBFHBXU73L7',
        ],
    ]);
  /*  $responseString = json_decode($response->getBody(), 200);

     $response = $http->get('https://api.uber.com/v1/me', [
        'headers' => [  
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$responseString['access_token'],
        ],
    ]);*/

  //  return '<pre>' . json_encode((string) $response->getBody(), true) . '</pre>';
    return '<pre>' .json_encode((string) $response->getBody(), true). '</pre>';
});

Route::get('/images/{filename}', function ($filename)
{
    $path = public_path() . '/uploads/' . $filename;

    if(!File::exists($path)) abort(404);

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);

    return $response;
});

Route::post('/uber/v1/savetoken', 'UberController@savetoken');

Route::get('/uber/v1/logintoken', 'UberController@logintoken');

// Route::get('/uber/v1/test', 'UberController@test');

Route::get('/uber/v1/findnearby', 'UberController@findnearby');

Route::get('/uber/v1/notifynewdest', function () {
    return 'Hello World';
});

Route::post('/uber/v1/savereview', 'UberController@savereview');

Route::post('/uber/v1/savecomment', 'UberController@savecomment');
