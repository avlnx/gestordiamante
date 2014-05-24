<?php

class TenantsController extends BaseController
{
	public $restful = false;

	public function getUpdateModelItems()
	{
		/*
		LISTA DE CATEGORIAS
		$categories = array(
			'Perfumes Masculinos'		=>	'Perfumes Masculinos',
			'Perfumes Femininos'		=>	'Perfumes Femininos',
			'Perfumes Unisex'			=>	'Perfumes Unisex',
			'Flaconetes Masculinos'		=>	'Flaconetes Masculinos',
			'Flaconetes Femininos'		=>	'Flaconetes Femininos',
			'Flaconetes Unisex'			=>	'Flaconetes Unisex',
			'Cremes'					=>	'Cremes',
			'Linha Bucal'				=>	'Linha Bucal',
			'Linha UP Hair'				=>	'Linha UP Hair',
			'Kits'			=>	'Kits (concessões) Oficiais e Upgrades UP!',
			'Acessórios para Kits UP!'	=>	'Acessórios para Kits UP!',
			'Acessórios em Geral'		=>	'Acessórios gerais',
			'Amostras'					=>	'Amostras',
			'Livros'					=>	'Livros',
			'Combos'					=>	'Combos Promocionais UP!'
		);
		*/
		$model_tenants = Tenant::where('is_model','=',true)->get();
		$regular_tenants = Tenant::where('is_model','!=',true)->where('company','!=', 'root')->get();

		foreach ($model_tenants as $model_tenant) {
			// get all categories of model_tenant
			$categories = $model_tenant->categories;
			$products = $model_tenant->products;

			// foreach regular_tenants with a company equal to this model_tenant's company
			
			foreach ($regular_tenants as $regular_tenant) {
				if ($regular_tenant->company == $model_tenant->company) {
					// foreach categories in model_tenant if category doesn't exist yet for regular_tenant, create it
					// if it does exist, update it to match the one in model_tenant
					foreach ($categories as $model_tenant_category) {
						$name = $model_tenant_category->name;
						//$users = DB::table('users')->whereIn('id', array(1, 2, 3))->get();
						$regular_cat = Category::where('tenant_id','=',$regular_tenant->id)
							->where('name','=',$name)->first();
						if ($regular_cat == NULL) {
							// Category doesn't exist yet for this tenant, copy it
							$new_regular_cat = $model_tenant_category->replicate();
							$new_regular_cat->tenant_id = $regular_tenant->id;
							$new_regular_cat->is_protected = true;
							$new_regular_cat->save();
						} else {
							// Category exists, update it
							$regular_cat->name = $model_tenant_category->name;
							$regular_cat->slug = $model_tenant_category->slug;
							$regular_cat->description = $model_tenant_category->description;
							$regular_cat->is_protected = true;
							$regular_cat->save();
						}
					}

					// Now, do the same for the products
					foreach ($products as $model_tenant_product) {
						$ref = $model_tenant_product->ref;
						$category_in_regular_tenant = Category::where('tenant_id','=',$regular_tenant->id)
							->where('name','=',$model_tenant_product->category->name)->first();

						$regular_product = Product::where('tenant_id','=',$regular_tenant->id)
							->where('ref','=',$ref)->first();
						if ($regular_product == NULL) {
							// Product doesn't exist yet for this tenant, copy it
							$new_regular_product = $model_tenant_product->replicate();
							$new_regular_product->category_id = $category_in_regular_tenant->id;
							$new_regular_product->tenant_id = $regular_tenant->id;
							$new_regular_product->is_protected = true;
							$new_regular_product->quantity_in_stock = 0;
							$new_regular_product->save();
						} else {
							// Product exists, update it
							$regular_product->ref = $model_tenant_product->ref;
							$regular_product->category_id = $category_in_regular_tenant->id;
							$regular_product->name = $model_tenant_product->name;
							$regular_product->slug = $model_tenant_product->slug;
							$regular_product->price = $model_tenant_product->price;
							$regular_product->margin = $model_tenant_product->margin;
							$regular_product->box = $model_tenant_product->box;
							$regular_product->description = $model_tenant_product->description;
							$regular_product->is_protected = true;
							$regular_product->save();
						}
					}

				}
			}
		}

		return Redirect::route('tenants.index', array())
				->with('notice', 'Sucesso!');

	}

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
		if (!$tenant->is_model) {
			$superadmin = User::where('email','=',$tenant->email)->first();
			$superadmins_tenant = $superadmin->tenant;

			// is this the only tenant with this superadmin?
			$count = Tenant::where('email','=',$tenant->email)->count();
			// if yes, delete the superadmin
			if ($count <= 1) {
				// there are no other tenants, delete superadmin
				$superadmin->delete();
			} else {
				// Is this superadmin set to this tenant? If yes, set it to one of the other remaining tenants
				if ($superadmins_tenant->id == $tenant->id) {
					$other_tenants_id = Tenant::where('email','=',$tenant->email)->where('id','!=',$tenant->id)->first()->id;
					$superadmin->tenant_id = $other_tenants_id;
					$superadmin->save();
				}
			}
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

		$data = array(
			'email'	=> 	$tenant->email
		);
		// TODO: email superadmin
		Mail::send('emails.new_tenant', $data, function($message)
		{
		    $message->to($tenant->email, $tenant->account_name)->subject('Seja bem vindo ao Gestor Diamante!');
		});

		//return Redirect::route('tenants.index', array())
		//		->with('notice', 'Tenant criado com sucesso. Atualize os modelos.');
		return Redirect::route('tenants.update_from_model', array())
			->with('notice', 'Tenant criado com sucesso. Modelos atualizados.');
	}

	public function getFocus($id)
	{
		$tenant = Tenant::find($id);

		$view = View::make('tenants.focus');
		$view->tenant = $tenant;

		return $view;
	}
}