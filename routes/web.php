<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\OTPController;
use Modules\Booking\Controllers\BookingController;

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
Route::get('/intro', 'LandingpageController@index');
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');
Route::post('/install/check-db', 'HomeController@checkConnectDatabase');

// Social Login
Route::get('social-login/{provider}', 'Auth\LoginController@socialLogin');
Route::get('social-callback/{provider}', 'Auth\LoginController@socialCallBack');

// Logs
Route::get(config('admin.admin_route_prefix') . '/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware(['auth', 'dashboard', 'system_log_view'])->name('admin.logs');

Route::get('/install', 'InstallerController@redirectToRequirement')->name('LaravelInstaller::welcome');
Route::get('/install/environment', 'InstallerController@redirectToWizard')->name('LaravelInstaller::environment');
Route::fallback([\Modules\Core\Controllers\FallbackController::class, 'FallBack']);

// Hide page update default
Route::get('/update', 'InstallerController@redirectToHome');
Route::get('/update/overview', 'InstallerController@redirectToHome');
Route::get('/update/database', 'InstallerController@redirectToHome');


//OTP
Route::post('/send-otp', [OTPController::class, 'sendOTP'])->name('send.otp');
Route::post('/verify-otp', [OTPController::class, 'verifyOTP'])->name('verify.otp');
Route::post('/save/passangerDetails', [BookingController::class, 'doCheckout'])->name('doCheckout');
Route::post('/save/bookingpassanger', [BookingController::class, 'bookPassengerDetails'])->name('bookPassengerDetails');

Route::get('/get/passengerDetails/{code}/{id}', [BookingController::class, 'getPassengerDetails'])->name('getPassengerDetails');

Route::post('/booking/store', [BookingController::class, 'store'])->name('booking.store');



