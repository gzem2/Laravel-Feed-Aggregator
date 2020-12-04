<?php

use App\Http\Controllers\FeedController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;

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

/*
Route::get('/', function () {
    return view('welcome');
});
*/


Route::get('/', [SiteController::class, 'index']);
Route::get('/addfeed', [SiteController::class, 'addFeed']);

Route::resource('feed', FeedController::class);
Route::get('/feed/{feed}/refresh', [feedController::class, 'refresh']);
Route::get('/feed/{feed}/page', [feedController::class, 'page']);