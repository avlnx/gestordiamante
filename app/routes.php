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

Route::get('/', array('before' => 'auth', 'uses' => 'home@getIndex', 'as' => 'home.index'));

// Root routes
Route::get('tenants', array('before' => 'auth|root_only', 'uses' => 'TenantsController@getIndex', 'as' => 'tenants.index'));
Route::get('tenants/new', array('before' => 'auth|root_only', 'uses' => 'TenantsController@getNew', 'as' => 'tenants.new'));
Route::post('tenants/new', array('before' => 'auth|root_only', 'uses' => 'TenantsController@postNew', 'as' => 'tenants.new'));
Route::get('tenants/{id}', array('before' => 'auth|root_only', 'uses' => 'TenantsController@getFocus', 'as' => 'tenants.focus'));

// Account routes
Route::get('account', array('uses' => 'AccountController@getLogin', 'as' => 'account.login'));
Route::get('account/logout', array('uses' => 'AccountController@getLogout', 'as' => 'account.logout'));
Route::post('account/check', array('uses' => 'AccountController@getCheckCredentials', 'as' => 'account.check_credentials'));

// Products routes
Route::get('products', array('before' => 'auth', 'uses' => 'ProductsController@getIndex', 'as' => 'products.index'));
Route::get('products/new', array('before' => 'auth|admins_only', 'uses' => 'ProductsController@getNew', 'as' => 'products.new'));
Route::post('products/new', array('before' => 'auth|admins_only', 'uses' => 'ProductsController@postNew', 'as' => 'products.new'));
Route::get('products/edit/{id}', array('before' => 'auth|admins_only|check_tenant:Product', 'uses' => 'ProductsController@getEdit', 'as' => 'products.edit'));
Route::post('products/edit/{id}', array('before' => 'auth|admins_only|check_tenant:Product', 'uses' => 'ProductsController@postEdit', 'as' => 'products.edit'));
Route::get('products/delete/{id}', array('before' => 'auth|admins_only|check_tenant:Product', 'uses' => 'ProductsController@getDelete', 'as' => 'products.delete'));
Route::get('products/{id}', array('before' => 'auth|check_tenant:Product', 'uses' => 'ProductsController@getFocus', 'as' => 'products.focus'));

// Categories routes
Route::get('products/categories', array('before' => 'auth', 'uses' => 'CategoriesController@getIndex', 'as' => 'categories.index'));
Route::get('products/categories/new', array('before' => 'auth|admins_only', 'uses' => 'CategoriesController@getNew', 'as' => 'categories.new'));
Route::post('products/categories/new', array('before' => 'auth|admins_only', 'uses' => 'CategoriesController@postNew', 'as' => 'categories.new'));
Route::get('products/categories/edit/{id}', array('before' => 'auth|admins_only|check_tenant:Category', 'uses' => 'CategoriesController@getEdit', 'as' => 'categories.edit'));
Route::post('products/categories/edit/{id}', array('before' => 'auth|admins_only|check_tenant:Category', 'uses' => 'CategoriesController@postEdit', 'as' => 'categories.edit'));
Route::get('products/categories/delete/{id}', array('before' => 'auth|admins_only|check_tenant:Category', 'uses' => 'CategoriesController@getDelete', 'as' => 'categories.delete'));
Route::get('products/categories/{id}', array('before' => 'auth|check_tenant:Category', 'uses' => 'CategoriesController@getFocus', 'as' => 'categories.focus'));

// Users routes
Route::get('users', array('before' => 'auth', 'uses' => 'UsersController@getIndex', 'as' => 'users.index'));
Route::get('users/new', array('before' => 'auth|admins_only', 'uses' => 'UsersController@getNew', 'as' => 'users.new'));
Route::post('users/edit/{id}', array('before' => 'auth|admins_only|check_tenant:User', 'uses' => 'UsersController@postEdit', 'as' => 'users.edit'));
Route::get('users/edit/{id}', array('before' => 'auth|admins_only|check_tenant:User', 'uses' => 'UsersController@getEdit', 'as' => 'users.edit'));
Route::get('users/delete/{id}', array('before' => 'auth|admins_only|check_tenant:User', 'uses' => 'UsersController@getDelete', 'as' => 'users.delete'));
Route::post('users/new', array('before' => 'auth|admins_only', 'uses' => 'UsersController@postNew', 'as' => 'users.new'));

// Snapshots routes
Route::get('snapshots', array('before' => 'auth|admins_only', 'uses' => 'SnapshotsController@getIndex', 'as' => 'snapshots.index'));
//Route::get('snapshots/new', array('before' => 'auth|admins_only', 'uses' => 'SnapshotsController@getnew', 'as' => 'snapshots.new'));
//Route::post('snapshots/new', array('before' => 'auth|admins_only', 'uses' => 'SnapshotsController@getnew', 'as' => 'snapshots.new'));
Route::get('snapshots/new/(:any?)', array('before' => 'auth|admins_only', 'uses' => 'SnapshotsController@getNew', 'as' => 'snapshots.new'));
Route::post('snapshots/new/(:any?)', array('before' => 'auth|admins_only', 'uses' => 'SnapshotsController@postNew', 'as' => 'snapshots.new'));
//Route::get('snapshots/entry/new/{id}', array('before' => 'auth|admins_only|check_tenant:Snapshot', 'uses' => 'SnapshotsController@getnew_entry', 'as' => 'snapshots.new_entry'));
//Route::post('snapshots/entry/new/{id}', array('before' => 'auth|admins_only|check_tenant:Snapshot', 'uses' => 'SnapshotsController@getnew_entry', 'as' => 'snapshots.new_entry'));
Route::get('snapshots/{id}', array('before' => 'auth|admins_only|check_tenant:Snapshot', 'uses' => 'SnapshotsController@getFocus', 'as' => 'snapshots.focus'));

// Sales routes
Route::get('sales', array('before' => 'auth', 'uses' => 'SalesController@getIndex', 'as' => 'sales.index'));
Route::get('sales/new', array('before' => 'auth', 'uses' => 'SalesController@getNew', 'as' => 'sales.new'));
Route::post('sales/new', array('before' => 'auth', 'uses' => 'SalesController@postNew', 'as' => 'sales.new'));
Route::get('sales/{id}', array('before' => 'auth|check_tenant:Sale', 'uses' => 'SalesController@getFocus', 'as' => 'sales.focus'));

