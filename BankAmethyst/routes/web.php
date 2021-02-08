<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('login');
});


Route::post('/', function () {
    return view('login');
});

Route::get('/afterlogon', function() {
   return view('afterlogon');
});

Route::post('/afterlogon', function() {
    return view('afterlogon');
});

Route::get('/przelew.php', function() {
    return view('przelew');
});
Route::get('/historia', function() {
    return view('historia');
});
Route::get('/przelew', function() {
    return view('przelew');
});
Route::get('/potwierdz_przelew', function() {
    return view('potwierdz_przelew');
});
Route::redirect('/index.php','/');

