<?php
/**
 * Shift-specific routes and routing, rules and filter definitions.
 */

// Register all /api/ routes. All application requests for data basically
// go via the API route group collection.
Route::group(['prefix' => Config::get('shift.api.url')], function() {
	Route::collection('roles', 'Tectonic\Shift\Modules\Security\Controllers\RoleController');
	Route::collection('users', 'Tectonic\Shift\Modules\Accounts\Controllers\UserController');
    Route::collection('customfields', 'Tectonic\Shift\Modules\CustomFields\Controllers\CustomFieldController');
});

Route::filter('shift.view', 'Tectonic\Shift\Library\Filters\ViewFilter');
Route::filter('shift.account', 'Tectonic\Shift\Library\Filters\AccountFilter');

Route::when('*', 'shift.account');
Route::when('*', 'shift.view');
