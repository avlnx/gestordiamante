<?php

class Sale extends Eloquent
{
	protected $guarded = array('id');

	public function tenant()
	{
		return $this->belongsTo('Tenant');
	}

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function items()
	{
		return $this->hasMany('Item');
	}

	public static function all($columns = array('*'))
	{
		return parent::where('tenant_id', '=', Auth::user()->tenant_id)
			//->where('is_alive','=',true)
			->orderBy('created_at', 'desc')
			//->take(10)
			->get();
	}

	public function total_value()
	{
		$items = $this->items()->get();
		$total = 0;
		foreach ($items as $item) {
			$total += ($item->current_price * $item->quantity);
		}
		return $total;
	}

	public function get_payments()
	{
		$debit = $this->debit;
		$credit = $this->credit;
		$deposit = $this->deposit;
		$cash = $this->cash;
		$bonus = $this->bonus;

		$payments = array(
			'Débito'	=>	$this->debit,
			'Crédito'	=>	$this->credit,
			'Depósito'	=>	$this->deposit,
			'Dinheiro'	=>	$this->cash,
			'Bônus'	=>	$this->bonus
			);
		$filtered_payments = array_filter($payments, function($value) {
			return ($value > 0);
		});

		return $filtered_payments;
	}

	public function deleted_by_user()
	{
		return User::findOrFail($this->deleted_by);

	}
}