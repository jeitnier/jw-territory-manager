<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

// authentication routes
Route::group(array('prefix' => 'auth'), function()
{
	Route::any('login', array(
		'as'   => 'auth.login',
		'uses' => 'AuthController@login'
	));

	Route::any('logout', array(
		'as'   => 'auth.logout',
		'uses' => 'AuthController@logout'
	));

	Route::any('forgot-password', array(
		'as'   => 'auth.forgot-password',
		'uses' => 'AuthController@forgotPassword'
	));

	Route::get('reset-password', array(
		'as'   => 'auth.reset-password',
		'uses' => 'AuthController@resetPassword'
	));

	Route::post('reset-password', array(
		'as'   => 'auth.reset-password',
		'uses' => 'AuthController@resetPassword'
	));
});

// AJAX routes
Route::group(array('prefix' => 'ajax', 'before' => 'sentry'), function()
{
	Route::post('delete', array(
		'as'   => 'ajax.delete',
		'uses' => 'AjaxController@delete'
	));

	Route::any('generate', array(
		'as'   => 'ajax.generate',
	    'uses' => 'AjaxController@generate'
	));

	Route::get('map_embed_id', array(
		'uses' => 'AjaxController@mapEmbedId'
	));

	Route::get('mapbox-api-key', [
		'uses' => 'AjaxController@getMapboxApiKey'
	]);
});

// unauthorized routes
Route::group(array('before' => 'guest'), function()
{
	Route::any('/', function()
	{
		if ( ! Sentry::check())
		{
			return View::make('auth.login');
		}
		else
		{
			$user = Sentry::getUser();

			if ($user->hasAccess('admin'))
			{
				return Redirect::route('admin.index');
			}
			elseif ($user->hasAccess('publisher'))
			{
				return Redirect::route('congregation.index');
			}
		}
	});

	// help
	Route::get('help', [
		'as'   => 'help',
		'uses' => 'HelpController@index'
	]);

	// territory email viewer
	Route::get('territories/email-viewer/{territory_id}/{key}', array(
		'as'   => 'territories.email-viewer',
		'uses' => 'TerritoriesSignOutController@emailTerritoryViewer'
	));
});


// admin routes
Route::group(array('prefix' => 'admin', 'before' => 'sentry'), function()
{
	Route::get('/', array(
		'as'   => 'admin.index',
	    'uses' => 'AdminController@index'
	));

	// publishers
	Route::get('publishers/search', array(
		'as'   => 'admin.publishers.search',
		'uses' => 'AjaxController@publishersSearch'
	));

	Route::Resource('publishers', 'PublishersController');

	// territories sign out
	Route::get('territories/sign-out', array(
		'as'   => 'admin.territories.sign-out.index',
		'uses' => 'TerritoriesSignOutController@index'
	));

	Route::get('territories/sign-out/house-to-house', array(
		'as'   => 'admin.territories.sign-out.house-to-house',
	    'uses' => 'TerritoriesSignOutController@houseToHouse'
	));

	Route::get('territories/sign-out/letter-writing-phone', array(
		'as'   => 'admin.territories.sign-out.letter-writing-phone',
		'uses' => 'TerritoriesSignOutController@letterWritingPhone'
	));

	Route::get('territories/sign-out/business', array(
		'as'   => 'admin.territories.sign-out.business',
		'uses' => 'TerritoriesSignOutController@business'
	));

	Route::get('territories/sign-out/{territory_id}/create', array(
		'as'   => 'admin.territories.sign-out.create',
		'uses' => 'TerritoriesSignOutController@create'
	));

	Route::post('territories/sign-out/{territory_id}', array(
		'as'   => 'admin.territories.sign-out.store',
		'uses' => 'TerritoriesSignOutController@store'
	));

	Route::get('territories/sign-out/{territory_id}', array(
		'as'   => 'admin.territories.sign-out.show',
		'uses' => 'TerritoriesSignOutController@show'
	));

	Route::get('territories/sign-out/{territory_id}/print', array(
		'as'   => 'admin.territories.sign-out.print',
		'uses' => 'TerritoriesSignOutController@printTerritory'
	));

	Route::post('territories/sign-out/{territory_id}/email', array(
		'as'   => 'admin.territories.sign-out.email',
		'uses' => 'TerritoriesSignOutController@emailTerritory'
	));

	// territories sign in
	Route::get('territories/{territories}/show-publisher-territories', array(
		'as'   => 'admin.territories.show-publisher-territories',
	    'uses' => 'TerritoriesSignInController@showPublisherTerritories'
	));

	Route::get('territories/sign-in', array(
		'as'   => 'admin.territories.sign-in',
		'uses' => 'TerritoriesSignInController@create'
	));

	Route::post('territories/sign-in', array(
		'as'   => 'admin.territories.sign-in',
		'uses' => 'TerritoriesSignInController@store'
	));

	// territories
	Route::get('territories/search', array(
		'as'   => 'admin.territories.search',
	    'uses' => 'AjaxController@territoriesSearch'
	));

	Route::Resource('territories', 'TerritoriesController');

	// do not calls
	Route::get('do-not-calls/{do_not_calls}/print', array(
		'as'   => 'admin.do-not-calls.print',
		'uses' => 'DoNotCallController@printDoNotCall'
	));

	Route::Resource('do-not-calls', 'DoNotCallController');

	// reports
	Route::get('reports', array(
		'as'   => 'admin.reports.index',
	    'uses' => 'ReportsController@index'
	));

	Route::post('reports/generate', array(
		'as'   => 'admin.reports.generate',
	    'uses' => 'ReportsController@generate'
	));

	Route::get('reports/{report}', array(
		'as'   => 'admin.reports.show',
	    'uses' => 'ReportsController@show'
	));

	// settings
	Route::get('settings/edit', array(
		'as'   => 'admin.settings.edit',
	    'uses' => 'SettingsController@edit'
	));

	Route::put('settings/update', array(
		'as'   => 'admin.settings.update',
		'uses' => 'SettingsController@update'
	));
});

// user routes
Route::group(array('prefix' => 'user', 'before' => 'sentry'), function()
{
	Route::get('edit', array(
		'as'   => 'user.edit',
	    'uses' => 'UserController@edit'
	));

	Route::post('update', array(
		'as'   => 'user.update',
	    'uses' => 'UserController@update'
	));
});

// utility routes
Route::group(array('prefix' => 'utility', 'before' => 'sentry'), function()
{
	// find territory by address
	Route::post('geocode-address', array(
		'uses' => 'AjaxController@geocodeAddress'
	));

	Route::get('territories/maps-list', array(
		'uses' => 'TerritoriesController@mapsList'
	));

	Route::get('territory-resolver', array(
		'uses' => 'UtilityController@territoryResolver'
	));

	Route::post('territory-resolver', array(
		'as'   => 'utility.territory-resolver',
		'uses' => 'UtilityController@territoryResolver'
	));

	// show maps list
	Route::get('all-maps', array(
		'as'   => 'utility.all-maps',
		'uses' => 'UtilityController@allMaps'
	));
});