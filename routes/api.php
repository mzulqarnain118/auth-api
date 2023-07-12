<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// User registration
Route::post('/register', 'App\Http\Controllers\Auth\RegisterController@register');
Route::post('/sendVerificationEmail', 'App\Http\Controllers\Auth\RegisterController@sendVerificationEmail');
Route::get('/getUserRoles', 'App\Http\Controllers\Auth\RegisterController@getUserRoles');

// User login
Route::post('/login', 'App\Http\Controllers\Auth\LoginController@login');

// User logout
Route::post('/logout', 'App\Http\Controllers\Auth\LoginController@logout')->middleware('auth:api');

// Get authenticated user
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');
Route::get('/users', 'App\Http\Controllers\Auth\RegisterController@getAll');
Route::get('/users/{id}', 'App\Http\Controllers\Auth\LoginController@getById');
Route::put('/users/{id}', 'App\Http\Controllers\Auth\LoginController@updateById');
Route::get('/userRoles', 'App\Http\Controllers\Auth\RegisterController@getUserRoles');


// Password reset
Route::post('/password/reset', 'App\Http\Controllers\Auth\ForgotPasswordController@sendResetLinkEmail');
Route::post('/password/reset/{token}', 'App\Http\Controllers\Auth\ResetPasswordController@reset');
