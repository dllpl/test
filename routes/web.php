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
use App\Http\Controllers\DealController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\WithdrawController;




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


Route::middleware(['auth'])->group(function () {
    Route::get('/my-deals', [DealController::class, 'myDeals'])->name('deals.my');
	Route::get('/deals/{id}', [DealController::class, 'view'])->name('deals.view');

	Route::get('deal/create/{id}', [DealController::class, 'create'])->name('deal.create');
	Route::post('deal/store', [DealController::class, 'store'])->name('deal.store');

	Route::post('/deals/{id}/accept', [DealController::class, 'accept'])->name('deal.accept');
	Route::post('/deals/{id}/reject', [DealController::class, 'reject'])->name('deal.reject');
	Route::post('/deals/{id}/complete', [DealController::class, 'complete'])->name('deal.complete');
	Route::post('/deals/{id}/request-cancel', [DealController::class, 'requestCancel'])->name('deal.request_cancel');

	// История операций
	Route::get('/bill/history/income-expenses', [TransactionController::class, 'incomeExpenses'])->name('transactions.income_expenses'); // Приходы и расходы
	Route::get('/bill/history/withdraw-requests', [TransactionController::class, 'withdrawRequests'])->name('transactions.withdraw_requests'); // Запросы на вывод

	// Мои счета
	Route::get('/bill/accounts', [BillController::class, 'list'])->name('bill.list'); // Список счетов
	Route::get('/bill/accounts/create', [BillController::class, 'create'])->name('bill.add'); // Форма добавления счета
	Route::post('/bill/accounts', [BillController::class, 'store'])->name('bill.store'); // Добавление счета
	Route::delete('/bill/accounts/{id}', [BillController::class, 'destroy'])->name('bill.destroy'); // Удаление счета

	Route::post('/bill/withdraw', [WithdrawController::class, 'submitWithdraw'])->name('bill.withdraw.submit');
	Route::get('/bill/history', [TransactionController::class, 'transactionHistory'])->name('transactions.history');

});

Route::middleware('auth')->group(function () {
    Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');
});

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
