<?php
/*
 * LaraClassifier - Classified Ads Web Application
 * Copyright (c) BeDigit. All Rights Reserved
 *
 * Website: https://laraclassifier.com
 * Author: BeDigit | https://bedigit.com
 *
 * LICENSE
 * -------
 * This software is furnished under a license and may be used and copied
 * only in accordance with the terms of such license and with the inclusion
 * of the above copyright notice. If you Purchased from CodeCanyon,
 * Please read the full License from here - https://codecanyon.net/licenses/standard
 */

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Docs\DocsController;
use App\Http\Controllers\TinkoffController;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// install
Route::namespace('Install')->group(__DIR__ . '/web/install.php');

Route::middleware(['installed'])
	->group(function () {
		// admin
		$prefix = config('larapen.admin.route', 'admin');
		Route::namespace('Admin')->prefix($prefix)->group(__DIR__ . '/web/admin.php');

		// public
		Route::namespace('Public')->group(__DIR__ . '/web/public.php');
	});

Route::group(['prefix' => 'docs'], function () {
    Route::post('dkp', [DocsController::class, 'makeDkp']);
});

// Маршрут для отправки платежа
Route::post('/tinkoff/payment', [TinkoffController::class, 'sendPayment'])->name('tinkoff.payment');

// Маршрут для успешного платежа
Route::get('/tinkoff/success', [TinkoffController::class, 'paymentSuccess'])->name('tinkoff.success');

// Маршрут для неудачного платежа
Route::get('/tinkoff/fail', [TinkoffController::class, 'paymentFail'])->name('tinkoff.fail');

// Маршрут для callback (notification) от Тинькофф
Route::post('/tinkoff/callback', [TinkoffController::class, 'handleCallback'])->name('tinkoff.callback');
