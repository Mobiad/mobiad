<?php

use Illuminate\Http\Request;

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
Route::post('validate', [\App\Http\Controllers\Controller::class, 'validate']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/verify/otp', 'CustomerController@verifyPhoneOtp');
Route::post('/payment/init', 'CustomerController@paymentInit');

Route::post('/payment/simulate', 'CustomerController@simulateSubmission');



/*** Codeblocks API**/
Route::get('/payment/confirm/callback', 'CustomerController@confirmPayment');
Route::post('/payment/confirm/callback', 'CustomerController@confirmPayment');

/*** [end] Codeblocks **/



/*** Test **/
Route::post('/test/sms', 'NotificationController@sendSms');
/*** [end] Test **/


