<?php

class AccountController extends BaseController
{
	public $restful = true;

	public function getLogin()
	{	
		if (Auth::check())
		{
			// user is already logged in
			return Redirect::route('home.index')
				->with('notice', 'Você já está logado!');
		}
		else
		{
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
				return Redirect::route('home.index');
			} else {
				return Redirect::route('home.index');
			}
			
		} 
		else 
		{
			// not found
			return Redirect::route('account.login')
				->with('login_errors', true);
		}
	}

	public function getLogout()
	{
		Auth::logout();
		return Redirect::route('account.login');
	}

}