<?php

class UsersController extends BaseController
{
	public $restful = true;

	public function getIndex()
	{

		$view = View::make('users.index');
		$view->users = User::all();

		return $view;
	}

	public function getNew()
	{
		$view = View::make('users.new');
		return $view;
	}

	public function postNew()
	{
		// Process post of new categoria in MODEL

		$rules = array(
			'name'	=>	'required',
			'email'	=>	'required|email',
			'password' => 'required'
		);

		$messages = array(
		);

		$validation = Validator::make(Input::all(), $rules, $messages);

		if ($validation->fails())
	    {
	        return Redirect::route('users.new')->withErrors($validation);
	    }

		$user = new User;
		$user->tenant_id = Auth::user()->tenant_id;
		$user->name = Input::get('name');
		$user->email = Input::get('email');
		$user->password = Hash::make(Input::get('password'));
		$user->is_root = False;
		$user->is_admin = Input::get('is_admin');
		$user->is_alive = True;

		$user->save();

		return Redirect::route('users.index', array())
				->with('notice', 'Usuário cadastrado com sucesso');
	}

	public function getFocus($id)
	{
		$user = User::find($id);
		

		if ($user->tenant_id != Auth::user()->tenant_id) {
			# user doesn't belong to this admin's account
			die('Acesso não autorizado');
		}

		$view = View::make('users.focus');
		$view->user = $user;

		return $view;
	}

	public function getEdit($id)
	{
		$user = User::find($id);
		$view = View::make('users.edit');
		$view->user = $user;
		return $view;
	}

	public function postEdit($id)
	{
		// Process post of new categoria in MODEL

		$rules = array(
			'name'	=>	'required',
			'email'	=>	'required|email',
		);

		$messages = array(
		);

		$validation = Validator::make(Input::all(), $rules, $messages);

		if ($validation->fails())
	    {
	        return Redirect::route('users.edit')->withErrors($validation);
	    }

		$user = User::find($id);
		$user->name = Input::get('name');
		$user->email = Input::get('email');
		if(Input::get('password'))
		{
			$user->password = Hash::make(Input::get('password'));
		}
		$user->is_admin = Input::get('is_admin');

		$user->save();

		return Redirect::route('users.index', array())
				->with('notice', 'Usuário atualizado com sucesso');
	}

	public function getDelete($id)
	{
		$user = User::find($id);
		$user->is_alive = False;
		$user->save();
		return Redirect::route('users.index', array())
				->with('notice', 'Usuário removido com sucesso');
	}

}