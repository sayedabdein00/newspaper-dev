<?php

use App\Http\Controllers\Backend\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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
})->name('frontend-home');

Auth::routes();


/*Backend Routes */
Route::prefix('admin')->middleware(['auth'])->group(function(){

    // Dashboard
    Route::get('/home', [HomeController::class, 'index'])->name('home');


    // Profile Management Routes
    Route::get('update-profile', [ProfileController::class, 'getUpdateProfile'])->name('getupdate.profile');
    Route::post('update-profile', [ProfileController::class, 'updateProfile'])->name('postupdate.profile');

    Route::get('update-password', [ProfileController::class, 'getUpdatePassword'])->name('getupdate.password');
    Route::post('update-password', [ProfileController::class, 'updatePassword'])->name('postupdate.password');




});

