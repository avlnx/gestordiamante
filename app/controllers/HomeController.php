<?php

class HomeController extends BaseController {


	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome()
	{
		return View::make('hello');
	}

	public function getIndex()
	{
		if (Auth::user()->is_root) {
			return Redirect::route('tenants.index');
		} else if (Auth::user()->tenant->is_model) {
			return Redirect::route('products.admin');
		} else if (Auth::user()->is_superadmin) {
			return Redirect::route('superadmin.choose');
		} else if (Auth::user()->is_admin) {
			return Redirect::route('snapshots.stock');
		} else {
			return Redirect::route('sales.new');
		}
	}

}