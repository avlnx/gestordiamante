<?php

class Part extends Eloquent
{
	protected $fillable = array('tenant_id');
	
	public function tenant()
	{
		return $this->belongsTo('Tenant');
	}

	public function product()
	{
		return $this->belongsTo('Product');
	}

}