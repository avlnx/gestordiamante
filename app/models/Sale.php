<?php

class Sale extends Eloquent
{
	protected $guarded = array('id');
	protected $appends = array('total_value', 'creator','pretty_date','pretty_order_number','meta','pretty_total_value','delete_link');

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
			->orderBy('updated_at', 'desc')
			//->take(10)
			->get();
	}
	// Mutators & Ajax Attributes
	public function getDeleteLinkAttribute()
	{
		if($this->is_alive) {
			$link = link_to_route('sales.delete', "Deletar", 
				$parameters = array($this->id), 
				$attributes = array('class' => 'btn btn-mini disabled delete-link'));
		} else {
			$link = "";
		}
		return $link;
	}
	public function getPrettyDateAttribute()
	{
		if($this->is_alive) { $date = $this->created_at;} else { $date = $this->updated_at;}
		$eng_date = $date->diffForHumans();
		$eng = array("years", "year","months","month","days","day","hours","hour","minutes","minute","seconds","second","ago");
		$pt = array("anos","ano","meses","mês","dias","dia","horas","hora","minutos","minuto","segundos","segundo","atrás");
		$pt_date = str_replace($eng, $pt, $eng_date);
		return $pt_date;
	}
	public function getTotalValueAttribute()
	{
		return $this->debit + $this->credit + $this->deposit + $this->cash + $this->bonus;
	}
	public function getPrettyTotalValueAttribute()
	{
		if (!$this->is_alive) {
			return "<span class='deleted'>" . $this->total_value() . "</span>";
		} else {
			return $this->total_value();
		}
		return $value;
	}
	public function getCreatorAttribute()
	{
		return $this->user->name;
	}
	public function getPrettyOrderNumberAttribute()
	{
		if ($this->is_alive) {
			$order = link_to_route('sales.focus', "#".$this->order_number, 
				$parameters = array($this->id), 
				$attributes = array('class' => ''));
		} else {
			$order = "<span style='text-decoration: line-through'>#".$this->order_number_before_delete . "</span>";
		}
		return $order;
	}
	public function getMetaAttribute()
	{
		if (!$this->is_alive) {
			$deleter = User::find($this->deleted_by)->name;
			$meta = "<span class='label label-important'>Deletado</span> por <strong>" . $deleter . "</strong>";
		} else {
			$meta = "Registrado por <strong>" . $this->user->name . "</strong>";
		}
		return $meta;
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