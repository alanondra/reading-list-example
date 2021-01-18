<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers;

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

$defaultAction = [Controllers\AppController::class, 'index'];

Route::prefix('auth')
	->name('auth.')
	->group(function () {
		Route::get('verify/{id}/{hash}', [Controllers\AuthController::class, 'verify'])
			->middleware(['auth', 'signed'])
			->name('verify');
	
		Route::get('resend')
			->middleware(['auth', 'throttle:6,1'])
			->name('verification.verify');
	});

Route::get('login', $defaultAction)
	->name('login');

Route::get('/{any?}/{all?}', $defaultAction)
	->name('home');
