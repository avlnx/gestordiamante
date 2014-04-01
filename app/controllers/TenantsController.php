<?php

class TenantsController extends BaseController
{
	public $restful = false;

	public function getIndex()
	{
		$tenants = Tenant::where('is_model','!=',true)->get();

		$view = View::make('tenants.index');
		$view->tenants = $tenants;

		return $view;
	}

	public function getNew()
	{
		$view = View::make('tenants.new');

		return $view;
	}

	public function postNew()
	{
		// Process post of new categoria in MODEL

		$rules = array(
			'email'		=>	'required|email',
			'password'	=>	'required',
			'company'	=>	'required'
		);

		$messages = array(
		);

		$validation = Validator::make(Input::all(), $rules, $messages);

		if ($validation->fails())
	    {
	        return Redirect::route('tenants.new')->withErrors($validation);
	    }

		$tenant = new Tenant;
		$tenant->email = Input::get('email');
		$tenant->company = Input::get('company');
		if(!Input::get('is_model')) { $is_model = False; } else { $is_model = True; }
		$tenant->is_model = $is_model;

		$tenant->save();

		if (!$tenant->is_model) {
			$tenant->generate_default_products();
			$tenant->generate_admin_account(Input::get('password'));
		}

		// TODO: email tenant

		return Redirect::route('tenants.index', array())
				->with('notice', 'Tenant criado com sucesso');
	}

	public function getFocus($id)
	{
		$tenant = Tenant::find($id);

		$view = View::make('tenants.focus');
		$view->tenant = $tenant;

		return $view;
	}
}