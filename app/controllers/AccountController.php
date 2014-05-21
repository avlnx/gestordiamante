<?php

class AccountController extends BaseController
{
	public $restful = true;

	public function getLogin()
	{	
		if (Auth::check())
		{
			// user is already logged in
			if (Auth::user()->is_root) {
				return Redirect::route('tenants.index')
					->with('notice', 'Você já está logado!');
			} else if (Auth::user()->tenant->is_model) {
				return Redirect::route('products.admin')
					->with('notice', 'Você já está logado!');
			} else if (Auth::user()->is_superadmin) {
				return Redirect::route('superadmin.choose')
					->with('notice', 'Você já está logado!');
			} else if (Auth::user()->is_admin) {
				return Redirect::route('snapshots.stock')
					->with('notice', 'Você já está logado!');
			} else {
				return Redirect::route('sales.new')
					->with('notice', 'Você já está logado!');
			}
		} else {
			return View::make('account.login');
		}
	}

	public function postCheckCredentials()
	{
		// get POST data
		$userdata = array(
			'email' => Input::get('email'),
			'password' => Input::get('password')
		);

		if (Auth::attempt($userdata))
		{
			//ok
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
		else 
		{
			// not found
			return Redirect::route('account.login')
				->with('login_errors', true)->withInput(Input::except('password'));
		}
	}

	public function getLogout()
	{
		Auth::logout();
		return Redirect::route('account.login');
	}

}