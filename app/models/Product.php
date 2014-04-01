<?php

class Product extends Eloquent
{

	public function tenant()
	{
		return $this->belongsTo('Tenant');
	}

	public function category()
	{
		return $this->belongsTo('Category');
	}

	public function pretty_margin()
	{
		return $this->margin * 100;
	}

	/** Overload methods to account for tenants **/

	public static function all($columns = array('*'))
	{
		return parent::where('tenant_id', '=', Auth::user()->tenant_id)
		->where('is_alive','=',true)
		->orderBy('category_id')
		->orderBy('name')
		->get();
	}
	
}