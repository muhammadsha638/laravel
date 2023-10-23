<?php

use Illuminate\Support\Facades\Route;

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



// Route::group(['namespace'=>'App\Http\Controllers'],function(){
    Route::get('/','FirstController@index')->name('index');
    Route::get('user-register', 'FirstController@user_register')->name('user.register');
    Route::get('user-login', 'FirstController@user_login')->name('user.login');
    Route::post('user-submit-login', 'FirstController@user_submit_login')->name('submit.login');
    Route::post('user-save', 'FirstController@save')->name('save.user');
    Route::get('logout', 'FirstController@user_logout')->name('user.logout');
  Route::group(['middleware'=>'check_login'],function(){
    Route::get('alluser', 'FirstController@all_user')->name('all.user');
    Route::get('transcribe', 'GoogleController@user_transcribtion')->name('user.transcribtion.form');
    Route::post('transcribe-action', 'GoogleController@user_transcribe_action')->name('user.transcribe.api');
    Route::get('speech', 'GoogleController@speech')->name('speech.api');
    Route::get('translate', 'GoogleController@translate')->name('translate.api');
    Route::get('duration', 'GoogleController@duration')->name('duration.api');
    // Route::get('audio', 'AudioController@audio')->name('audio');
    // Route::post('upload-audio', 'AudioController@uploadAudio')->name('audio.upload');

    Route::post('translate-ajax-route', 'GoogleController@translate_ajaxMethod')->name('translate.ajax-route');
    Route::post('save-transcibe', 'GoogleController@save_usertranscibe')->name('save.transcribe');
    Route::get('user-history', 'GoogleController@users_filehistory')->name('user.history');
    Route::get('edit-history/{fileId}', 'GoogleController@users_editfilehistory')->name('user.edit.history');
    Route::post('update-history', 'GoogleController@users_updatefilehistory')->name('update.transcribe');
    Route::get('delete-history/{fileId}', 'GoogleController@users_deletefilehistory')->name('user.delete.history');



  });


// });

