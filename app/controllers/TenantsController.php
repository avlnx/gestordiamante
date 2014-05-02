<?php

class TenantsController extends BaseController
{
	public $restful = false;

	public function getIndex()
	{
		//$tenants = Tenant::where('is_model','!=',true)->get();
		$tenants = Tenant::all();

		$view = View::make('tenants.index');
		$view->tenants = $tenants;

		return $view;
	}

	public function getDelete($id)
	{
		$tenant = Tenant::findOrFail($id);
		
		// Delete users that are not superadmins
		$users = $tenant->users->filter(function($user){
			if(!$user->is_superadmin) {
				$user->delete();
			}
		});

		// Delete superadmin?:
		// Get this tenant's superadmin
		$superadmin = User::where('email','=',$tenant->email)->first();
		// is this the only tenant with this superadmin?
		$count = Tenant::where('email','=',$tenant->email)->count();
		// if yes, delete the superadmin
		if ($count <= 1) {
			// there are no other tenants, delete superadmin
			$superadmin->delete();
		}

        Category::where('tenant_id',$tenant->id)->delete();
        Product::where('tenant_id',$tenant->id)->delete();
        Snapshot::where('tenant_id',$tenant->id)->delete();
        Sale::where('tenant_id',$tenant->id)->delete();
        Part::where('tenant_id',$tenant->id)->delete();

		$tenant->delete();

		return Redirect::route('tenants.index')
				->with('notice', 'Tenant deletado com sucesso');
	}

	public function getNew()
	{
		$view = View::make('tenants.new');
		$superadmins = User::where('is_superadmin', '=', true)->get();
		$superadmins_array = [NULL];
		foreach ($superadmins as $superadmin) {
			$superadmins_array[$superadmin->id] = $superadmin->name;
		}
		$view->superadmins_array = $superadmins_array;
		return $view;
	}

	public function postNew()
	{
		// Process post of new categoria in MODEL
		$superadmin_id = Input::get('superadmin_id');

		if ($superadmin_id) {
			// Existing superadmin
			$rules = array(
			'company'	=>	'required',
			'superadmin_id' => 'required',
			'account_name' => 'required'
			);
		} else {
			// New superadmin
			$rules = array(
			'company'	=>	'required',
			'account_name' => 'required',
			'email'	=>	'required|email',
			'password'	=>	'required'
			);
			
		}

		$messages = array(
		);

		$validation = Validator::make(Input::all(), $rules, $messages);

		if ($validation->fails())
	    {
	        return Redirect::route('tenants.new')->withErrors($validation);
	    }

	    // 

		$tenant = new Tenant;
		$generate_new_admin = false;
		if ($superadmin_id) {
			$superadmin_user = User::find($superadmin_id);
			$tenant->email = $superadmin_user->email;
		} else {
			$generate_new_admin = true;
			$tenant->email = Input::get('email');
		}

		$tenant->account_name = Input::get('account_name');
		$tenant->company = Input::get('company');
		if(!Input::get('is_model')) { $is_model = False; } else { $is_model = True; }
		$tenant->is_model = $is_model;

		$tenant->save();

		if (!$tenant->is_model) {
			$tenant->generate_default_products();
		}
		if ($generate_new_admin) {
			$tenant->generate_admin_account(Input::get('password'));
		}

		// TODO: email superadmin

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