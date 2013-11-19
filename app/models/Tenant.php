<?php

class Tenant extends Eloquent
{
	public function users()
	{
		return $this->hasMany('User');
	}

	public function products()
	{
		return $this->hasMany('Product');
	}

	public function categories()
	{
		return $this->hasMany('Category');
	}

	public function generate_default_products()
	{
		$company = $this->company;
		$model_tenant = Tenant::where('is_model','=',true)->where('company','=',$company)->where('is_alive','=',true)->first();

		$model_categories = $model_tenant->categories()->get();

		foreach ($model_categories as $category) {
			$products_for_this_cat = $category->products()->get();

			$category->purge('id');
			$category->tenant_id = $this->id;
			$category->exists = false;
			$category->save();

			foreach ($products_for_this_cat as $product) {
				$product->purge('id');
				$product->tenant_id = $this->id;
				$product->category_id = $category->id;
				$product->exists = false;
				$product->save();
			}
		}
	}

	public function generate_admin_account($password)
	{
		$this->email;
		$user = new User;

		$user->email = $this->email;

		$user = new User;
		$user->tenant_id = $this->id;
		$user->name = 'Admin '.Input::get('email');
		$user->email = Input::get('email');
		$user->password = Hash::make($password);
		$user->is_root = False;
		$user->is_admin = True;
		$user->is_alive = True;

		$user->save();

		// TODO email admin
	}

	/** Overload methods to account for tenants **/

	public static function all()
	{
		return parent::where('is_alive','=',true)->get();
	}
}