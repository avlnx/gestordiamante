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

Route::pattern('id', '[0-9]+');

Route::get('/', array('before' => 'auth', 'uses' => 'HomeController@getIndex', 'as' => 'home.index'));

// Stats routes
Route::get('stats', array('before' => 'auth|admins_only', 'uses' => 'StatsController@getIndex', 'as' => 'stats.index'));

// Root routes
Route::get('tenants', array('before' => 'auth|root_only', 'uses' => 'TenantsController@getIndex', 'as' => 'tenants.index'));
Route::post('tenants/new', array('before' => 'auth|root_only', 'uses' => 'TenantsController@postNew', 'as' => 'tenants.postNew'));
Route::get('tenants/new', array('before' => 'auth|root_only', 'uses' => 'TenantsController@getNew', 'as' => 'tenants.new'));
Route::get('tenants/update_from_model', array('before' => 'auth|root_only', 'uses' => 'TenantsController@getUpdateModelItems', 'as' => 'tenants.update_from_model'));

Route::get('tenants/{id}', array('before' => 'auth|root_only', 'uses' => 'TenantsController@getFocus', 'as' => 'tenants.focus'));
Route::get('tenants/delete/{id}', array('before' => 'auth|root_only', 'uses' => 'TenantsController@getDelete', 'as' => 'tenants.delete'));

// Account routes
Route::get('account', array('uses' => 'AccountController@getLogin', 'as' => 'account.login'));
Route::get('account/logout', array('uses' => 'AccountController@getLogout', 'as' => 'account.logout'));
Route::post('account/check', array('uses' => 'AccountController@postCheckCredentials', 'as' => 'account.check_credentials'));

// Products routes
Route::get('products', array('before' => 'auth', 'uses' => 'ProductsController@getIndex', 'as' => 'products.index'));
Route::get('products/admin', array('before' => 'auth', 'uses' => 'ProductsController@getAdminProducts', 'as' => 'products.admin'));
Route::post('products/new', array('before' => 'auth|admins_only', 'uses' => 'ProductsController@postNew', 'as' => 'products.postNew'));
Route::get('products/new', array('before' => 'auth|admins_only', 'uses' => 'ProductsController@getNew', 'as' => 'products.new'));

Route::get('products/edit/{id}', array('before' => 'auth|admins_only|check_tenant:Product|protected_item:Product', 'uses' => 'ProductsController@getEdit', 'as' => 'products.edit'));
Route::post('products/edit/{id}', array('before' => 'auth|admins_only|check_tenant:Product|protected_item:Product', 'uses' => 'ProductsController@postEdit', 'as' => 'products.postEdit'));
Route::get('products/delete/{id}', array('before' => 'auth|admins_only|check_tenant:Product|protected_item:Product', 'uses' => 'ProductsController@getDelete', 'as' => 'products.delete'));
Route::get('products/{id}', array('before' => 'auth|check_tenant:Product', 'uses' => 'ProductsController@getFocus', 'as' => 'products.focus'));

// Categories routes
Route::get('products/categories', array('before' => 'auth', 'uses' => 'CategoriesController@getIndex', 'as' => 'categories.index'));
Route::post('products/categories/new', array('before' => 'auth|admins_only', 'uses' => 'CategoriesController@postNew', 'as' => 'categories.postNew'));
Route::get('products/categories/new', array('before' => 'auth|admins_only', 'uses' => 'CategoriesController@getNew', 'as' => 'categories.new'));

Route::get('products/categories/edit/{id}', array('before' => 'auth|admins_only|check_tenant:Category|protected_item:Category', 'uses' => 'CategoriesController@getEdit', 'as' => 'categories.edit'));
Route::post('products/categories/edit/{id}', array('before' => 'auth|admins_only|check_tenant:Category|protected_item:Category', 'uses' => 'CategoriesController@postEdit', 'as' => 'categories.postEdit'));
Route::get('products/categories/delete/{id}', array('before' => 'auth|admins_only|check_tenant:Category|protected_item:Category', 'uses' => 'CategoriesController@getDelete', 'as' => 'categories.delete'));
Route::get('products/categories/{id}', array('before' => 'auth|check_tenant:Category', 'uses' => 'CategoriesController@getFocus', 'as' => 'categories.focus'));

// Users routes
Route::get('users', array('before' => 'auth', 'uses' => 'UsersController@getIndex', 'as' => 'users.index'));
Route::get('users/new', array('before' => 'auth|admins_only', 'uses' => 'UsersController@getNew', 'as' => 'users.new'));
Route::post('users/edit/{id}', array('before' => 'auth|admins_only|check_tenant:User', 'uses' => 'UsersController@postEdit', 'as' => 'users.postEdit'));
Route::get('users/edit/{id}', array('before' => 'auth|admins_only|check_tenant:User', 'uses' => 'UsersController@getEdit', 'as' => 'users.edit'));
Route::get('users/delete/{id}', array('before' => 'auth|admins_only|check_tenant:User', 'uses' => 'UsersController@getDelete', 'as' => 'users.delete'));
Route::post('users/new', array('before' => 'auth|admins_only', 'uses' => 'UsersController@postNew', 'as' => 'users.postNew'));

// Snapshots/Pedidos/Estoque routes
Route::get('snapshots', array('before' => 'auth|admins_only', 'uses' => 'SnapshotsController@getIndex', 'as' => 'snapshots.index'));
Route::get('snapshots/new/{snapshot_type?}', array('before' => 'auth|admins_only', 'uses' => 'SnapshotsController@getNew', 'as' => 'snapshots.new'));
Route::post('snapshots/new/{snapshot_type?}', array('before' => 'auth|admins_only', 'uses' => 'SnapshotsController@postNew', 'as' => 'snapshots.postNew'));
Route::get('snapshots/{id}', array('before' => 'auth|admins_only|check_tenant:Snapshot', 'uses' => 'SnapshotsController@getFocus', 'as' => 'snapshots.focus'));
Route::get('snapshots/delete/{id}', array('before' => 'auth|admins_only|check_tenant:Snapshot', 'uses' => 'SnapshotsController@getDeleteSnapshot', 'as' => 'snapshots.delete'));
// Stock routes
Route::get('stock/{filter?}', array('before' => 'auth|admins_only', 'uses' => 'SnapshotsController@getStock', 'as' => 'snapshots.stock'));

// Sales routes
Route::get('sales', array('before' => 'auth', 'uses' => 'SalesController@getASIndex', 'as' => 'sales.asindex'));
Route::any('sales/filter/{filter?}', array('before' => 'auth', 'uses' => 'SalesController@getIndex', 'as' => 'sales.index'));
Route::post('sales/new', array('before' => 'auth', 'uses' => 'SalesController@postNew', 'as' => 'sales.postNew'));
Route::get('sales/new', array('before' => 'auth', 'uses' => 'SalesController@getNew', 'as' => 'sales.new'));
Route::get('sales/edit/{id}', array('before' => 'auth|check_tenant:Sale', 'uses' => 'SalesController@getEditSale', 'as' => 'sales.edit'));
Route::get('sales/delete/{id}', array('before' => 'auth|check_tenant:Sale', 'uses' => 'SalesController@getDeleteSale', 'as' => 'sales.delete'));
Route::get('sales/{id}', array('before' => 'auth|check_tenant:Sale', 'uses' => 'SalesController@getFocus', 'as' => 'sales.focus'));
// Assynchronous sales routes
Route::get('sales/ajax.json/date/{date}/payment_type/{payment_type}/tenants/{tenants_ids}', array('before' => 'auth', 'uses' => 'SalesController@getASSales', 'as' => 'sales.json'));

// Superadmin routes
Route::get('superadmin/choose', array('before' => 'auth|admins_only', 'uses' => 'SuperadminController@getChooseProfile', 'as' => 'superadmin.choose'));
Route::get('superadmin/choose/{id}', array('before' => 'auth|admins_only', 'uses' => 'SuperadminController@getSwitchProfile', 'as' => 'superadmin.switch'));
