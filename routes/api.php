<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Tightenco\Ziggy\Ziggy;
use App\Http\Controllers;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
 * Informational Routes
 */
Route::get('routes', fn () => response()->json(new Ziggy()));

Route::get('i18n', [Controllers\TranslationController::class, 'index'])
	->name('i18n');

/*
 * Auth Routes
 */
Route::name('auth.')
	->group(function () {
		Route::post('register', [Controllers\AuthController::class, 'register'])
			->name('register');
	
		Route::post('verify', [Controllers\AuthController::class, 'verify'])
			->name('verify');
	});

/*
 * Password Routes
 */
Route::prefix('password')
	->name('password.')
	->group(function () {
		Route::post('send', [Controllers\PasswordController::class, 'send'])
			->name('send');
	
		Route::post('reset', [Controllers\PasswordController::class, 'reset'])
			->name('reset');
	});

/*
 * Application Routes
 */
Route::delete('session', [Controllers\SessionController::class, 'destroy'])
	->name('session.destroy');

Route::resource('session', Controllers\SessionController::class)
	->only(['index', 'store']);

Route::resource('authors', Controllers\AuthorController::class)
	->only(['index', 'show', 'store', 'update', 'destroy']);

Route::resource('books', Controllers\BookController::class)
	->only(['index', 'show', 'store', 'update', 'destroy']);

/*
 * Authenticated Routes
 */
Route::middleware('auth')
	->group(function () {
		Route::post('/profile', [Controllers\ProfileController::class, 'update'])
			->name('profile.update');
	});
