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

Route::get('/', 'HomeController@index')->name('home');

Route::resource('profiles','ProfileController',['parameters'=>[
	'profiles'=>'user'
]]);

Route::resource('wanteds','WantedController')->except(['create']);
Route::resource('properties','PropertyController');
Route::post('/properties/fetch', 'PropertyController@fetch');
Route::post('/wanteds/fetch', 'WantedController@fetch');


Route::middleware(['auth'])->group(function(){
	Route::post('/profiles/fetch', 'ProfileController@fetch');

// 	Reviewing system routes
// 	Route::get('/properties/{property}/review/create','PropertyController@writeReview');
// 	Route::get('/properties/{property}/review/edit','PropertyController@editReview');
// 	
// 	Route::post('/properties/{property}/review','PropertyController@storeReview');
// 	Route::put('/properties/{property}/review','PropertyController@updateReview');
// 	Route::delete('/properties/{property}/review','PropertyController@destroyReview');

	Route::get('/properties/{property}/report','PropertyController@createReport');
	Route::post('/properties/{property}/report','PropertyController@sendReport');

	Route::get('/profiles/{user}/report','ProfileController@createReport');
	Route::post('/profiles/{user}/report','ProfileController@sendReport');
	
// Messaging system routes
	Route::get('/messages', 'MessageController@index');
	Route::get('/messages/send/to/{user}', 'MessageController@create');
	Route::post('/messages/fetch', 'MessageController@fetch');
	Route::post('/messages/send', 'MessageController@store');
	Route::post('/messages/reply', 'MessageController@reply');

	Route::resource('/conversations', 'ConversationController');

	Route::post('/properties/{property}/fave', 'PropertyController@fave');
});
Route::middleware(['ownoradmin'])->group(function(){
	Route::get('/profiles/{user}/early_bird', 'ProfileController@early');
	Route::post('/profiles/{user}/early_bird', 'ProfileController@earlyOrder');
	Route::get('/profiles/{user}/premium_seeker', 'ProfileController@premium');
	Route::post('/profiles/{user}/premium_seeker', 'ProfileController@premiumOrder');
	Route::post('/profiles/{user}/sms', 'ProfileController@sms');
	Route::post('/profiles/{user}/verify', 'ProfileController@verify');

	Route::get('/properties/{property}/packages', 'PropertyController@showPackages');
	Route::post('/properties/{property}/packages', 'PropertyController@choosePackage');
	Route::get('/wanteds/{wanted}/packages', 'WantedController@showPackages');
	Route::post('/wanteds/{wanted}/packages', 'WantedController@choosePackage');
	
// Invites system routes
	Route::get('/properties/{property}/invites', 'InviteController@index');
	Route::post('/properties/{property}/invites', 'InviteController@send');
	Route::delete('/properties/{property}/invites', 'InviteController@destroy');

});

Route::middleware(['auth','isadmin'])->group(function(){
// Admin routes
	Route::get('/back-office', 'AdminController@home')->name('admin.home');
	Route::get('/back-office/profiles', 'AdminController@profiles')->name('admin.users');
	Route::get('/back-office/profiles/{user}', 'AdminController@profile')->name('admin.userProfile');
	Route::get('/back-office/wanteds', 'AdminController@wanteds')->name('admin.wanteds');
	Route::get('/back-office/wanteds/{wanted}', 'AdminController@wanted')->name('admin.wanted');
	Route::get('/back-office/properties', 'AdminController@properties')->name('admin.properties');
	Route::get('/back-office/properties/{property}', 'AdminController@property')->name('admin.property');
	Route::get('/back-office/orders', 'AdminController@orders')->name('admin.orders');
	Route::get('/back-office/reports', 'AdminController@reports')->name('admin.reports');
	Route::delete('/back-office/reports/{report}', 'AdminController@destroyReport')->name('admin.report');
	Route::any('/back-office/settings', 'AdminController@settings')->name('admin.settings');
	Route::any('/back-office/gallery', 'ImagesController@index')->name('admin.gallery');
	Route::any('/back-office/contact_us', 'AdminController@contactUs')->name('admin.contact_us');
	Route::any('/back-office/terms', 'AdminController@terms')->name('admin.terms');
	Route::any('/back-office/timeline', 'AdminController@timeline')->name('admin.timeline');
	Route::post('/back-office/timeline/{id}/delete', 'AdminController@timelineDelete')->name('admin.timeline_delete');
	Route::any('/back-office/analytics', 'AdminController@analytics')->name('admin.analytics');

	Route::group(['prefix' => 'back-office'], function () {
		Route::resource('slider','SliderController');
	});
	/*Route::any('/back-office/sliders', 'SliderController@index')->name('admin.sliders');
	Route::get('/back-office/slider/{id}/edit', 'SliderController@edit')->name('admin.slider.edit');
	Route::post('/back-office/slider/{id}/delete', 'SliderController@destroy')->name('admin.slider.destroy');*/
});


// FB Login routes

Route::get('/redirect','ProfileController@FBredirect');
Route::get('/callback','ProfileController@FBcallback');

Route::get('/clear', function() {
    Artisan::call('cache:clear');
    return "Cache is cleared";
});