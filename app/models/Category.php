<?php

class Category extends Eloquent
{
	public function tenant()
	{
		return $this->belongsTo('Tenant');
	}

	public function products()
	{
		return $this->hasMany('Product');
	}

	/** Overload methods to account for tenants **/

	public static function all()
	{
		return parent::where('tenant_id', '=', Auth::user()->tenant_id)->where('is_alive','=',true)->get();
	}

	public function num_products()
	{
		return $this->products()->count();
	}
}