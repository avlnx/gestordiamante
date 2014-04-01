<?php

class Item extends Eloquent
{
	protected $guarded = array('id');
	
	public function tenant()
	{
		return $this->belongsTo('Tenant');
	}

	public function sale()
	{
		return $this->belongsTo('Sale');
	}

	public function product()
	{
		return $this->belongsTo('Product');
	}
}