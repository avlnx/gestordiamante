<?php

class Part extends Eloquent
{
	protected $guarded = array('id');

	public function tenant()
	{
		return $this->belongsTo('Tenant');
	}

	public function product()
	{
		return $this->belongsTo('Product');
	}

}