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
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/brands', function() {
    return view('app.brands');
})->name('brands')->middleware('auth');

Route::get('/clients', function() {
    return view('app.clients');
})->name('clients')->middleware('auth');

Route::get('/cars', function() {
    return view('app.cars');
})->name('cars')->middleware('auth');

Route::get('/rents', function() {
    return view('app.rents');
})->name('rents')->middleware('auth');

Route::get('/modelos', function() {
    return view('app.modelos');
})->name('modelos')->middleware('auth');
