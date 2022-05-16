<?php

//login and register

use Illuminate\Support\Facades\Route;

Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');

//genres
Route::get('/genres','GenreController@index');

//movies
Route::post('/movies','MovieController@index');
Route::post('/search','MovieController@index');
Route::get('/movie/{movie}/images','MovieController@getImages');
Route::get('/movie/{movie}/actors','MovieController@getActors');
Route::get('/movie/{movie}/relatedMovie','MovieController@relatedMovie');

Route::middleware('auth:sanctum')->group(function () {

    //add/remove movie to favorite
    Route::get('/movie/favorite','MovieController@favoriteMovie');

    //user route
    Route::get('/user', 'AuthController@user');
});
