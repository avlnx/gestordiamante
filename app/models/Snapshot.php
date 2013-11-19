<?php

class Snapshot extends Eloquent
{
	public function tenant()
	{
		return $this->belongsTo('Tenant');
	}

	public function parts()
	{
		return $this->hasMany('Part');
	}

	public function total_value()
	{
		$parts = $this->parts()->get();
		$total = 0;
		//print_r($parts);
		foreach ($parts as $part) {
			$total += ($part->current_price * $part->quantity);
		}
		return $total;
	}

	public function num_of_products()
	{
		$parts = $this->parts()->get();
	}

	/** Overload methods to account for tenants **/

	public static function all()
	{
		return parent::where('tenant_id', '=', Auth::user()->tenant_id)->where('is_alive','=',true)->order_by('created_at', 'desc')->get();
	}
}