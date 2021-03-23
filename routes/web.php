<?php

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
    return view('one', ['gifts' => ['Pesa', 'Muga wa maongezi', 'Kifurushi cha Data',]]);
});


/*** Customers **/
Route::post('/signup', 'CustomerController@signup')->name('signup');

Route::get('/customer/info', 'CustomerController@getCustomerInfo');
Route::get('/verification/form', 'CustomerController@showOtpForm');
Route::get('/verification/single/form', 'CustomerController@showSingleVerification');
Route::get('/payment/form', 'CustomerController@showPaymentForm');
/*** [end] Customers **/

/*** Terms and conditions **/
Route::get('/customer/export', 'TermsController@exportCustomerContract')->name('customer.export');
Route::get('/terms/download', 'TermsController@downloadTerm');

/*** [end] Terms and conditions **/









Route::get('/terms', function () { return view('terms'); })->name('terms');

Auth::routes(['register' => false,  'reset' => false, 'verify' => false,]);

Route::get('/verify/{phone}', 'CustomerController@customer')->name('verify.customer');

Route::get('/welcome', function () {return view('welcome'); })->name('welcome');

Route::post('/verify/resend', 'CustomerController@resend')->name('verify.resend');

Route::post('/verify', 'CustomerController@verify')->name('verify');

Route::get('/admin', 'CustomerController@admin')->name('admin')->middleware('auth');

Route::get('customers/export/', 'CustomerController@exportAll')->name('allcustomer-export');
Route::post('customers/export/', 'CustomerController@exportFrom')->name('export-from');
Route::post('customer/export/', 'CustomerController@exportCustomer')->name('customer-export');
