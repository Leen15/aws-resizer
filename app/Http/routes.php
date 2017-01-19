<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('upload', function() {
    return view('upload');
});

Route::post('apply/upload', 'UploadController@uploadFileToS3');

Route::get('/dimensions', ['uses' => 'DimensionsController@index']);

Route::get('/get/{id}', ['uses' => 'GetImageController@get']);
