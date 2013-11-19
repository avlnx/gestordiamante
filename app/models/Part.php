<?php

class Part extends Eloquent
{
	public function tenant()
	{
		return $this->belongsTo('Tenant');
	}

	public function product()
	{
		return $this->belongsTo('Product');
	}

}