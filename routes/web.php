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

Auth::routes();
Route::get('logout', 'Auth\LoginController@logout');
Route::get('/auth/success', [
    'as'   => 'auth.success',
    'uses' => 'Auth\RegisterController@success'
]);

Route::get('/redirect', 'SocialAuthController@redirect');
Route::get('/callback', 'SocialAuthController@callback');
Route::get('/impressum', function () {return view('legal.impressum');});
Route::get('/datenschutz', function () {return view('legal.datenschutz');});

Route::group(['middleware' => ['auth']], function () {

    Route::get('/', function (){return redirect('/service');});
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('/mailtest', 'HomeController@mailtest');
    Route::get('pdf','HomeController@generatePDF');

    Route::prefix('superadmin')->group(function () {
        Route::get('user', 'UserController@index')->name('superadmin.user');
    });

    Route::resource('qualification', 'QualificationController');

    Route::resource('user', 'UserController');
    Route::match(['get', 'post'], 'user/approve/{id}', 'UserController@approve_user');

    Route::resource('client', 'ClientController');
    Route::get('/clientapply', 'ClientController@apply')->name('clientapply');
    Route::any('/client/{clientid}/apply', 'ClientController@applyrequest')->name('clientapplyrequest');
    Route::any('/client/{clientid}/apply/revert', 'ClientController@applyrevert')->name('clientapplyrevert');
    Route::any('/client/{clientid}/removeuser/{userid}', 'ClientController@removeuser')->name('clientremoveuser');
    Route::get('/changeclient/{client}', 'UserController@setcurrentclient')->name('changeclient');

    Route::resource('service', 'ServiceController');
    Route::get('service/{service}/delete', 'ServiceController@delete')->name('service.delete');

    Route::resource('news', 'NewsController');
    Route::get('news/{news}/delete', 'NewsController@delete')->name('news.delete');


    Route::post('qualification_user/create', 'QualificationController@createQualification_User');
    Route::match(['get', 'post'], 'qualification_user/delete/{user_id}/{qualification_id}', 'QualificationController@deleteQualification_User');

    Route::post('client_user/admin', 'ClientController@adminClient_User');

    Route::match(['get', 'post'], 'position/{id}/subscribe', 'PositionController@subscribe');
    Route::match(['get', 'post'], 'position/{id}/unsubscribe', 'PositionController@unsubscribe');
    Route::match(['get', 'post'], 'position/{id}/authorize', 'PositionController@authorizePos');
    Route::match(['get', 'post'], 'position/{id}/deauthorize', 'PositionController@deauthorizePos');
    Route::match(['get', 'post'], 'position/list_notAuthorized', 'PositionController@index_notAuthorized')->name('position.list_notAuthorized');
});
