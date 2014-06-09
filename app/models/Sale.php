<?php

class Sale extends Eloquent
{
	protected $guarded = array('id');
	protected $appends = array('total_value', 'creator','pretty_date','pretty_created_at','pretty_order_number','meta','pretty_total_value','delete_link');
	public static $clear_tenants;

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

	public static function getSuperAdminSales($tenants_ids)
	{
		// Security: check if all ids in list sent via javascript are tenants controlled by this superadmin
		// If we are here we are superadmins
		$saemail = Auth::user()->email;

		// get all tenants_ids controlled by this superadmin
		Sale::$clear_tenants = Tenant::where('email','=',$saemail)->lists('id');

		// Build an array from sent String tenants ids (10,23,43,4)
		$sent_tenants_list = explode(',', $tenants_ids);

		// filter sent_tenants_list by tenants actually controlled by this superadmin
		$filtered_tenants_list = array_filter($sent_tenants_list,function ($tenant){
			return in_array($tenant, Sale::$clear_tenants);
		});

		// get all sales whose tenant_id is in list of tenants above
		$sales = Sale::whereIn('tenant_id', $filtered_tenants_list)
						->orderBy('created_at','desc')
						->get();

      return $sales;
	}

	public static function all($columns = array('*'))
	{
		return parent::where('tenant_id', '=', Auth::user()->tenant_id)
		->orderBy('created_at', 'desc')
		->get();
	}
	// Mutators & Ajax Attributes
	public function getOldDeleteLinkAttribute()
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

	public function getDeleteLinkAttribute()
	{
		if($this->is_alive) {
			if (Auth::user()->is_admin) {
				# code...
				$link = link_to_route('sales.delete', "Deletar", 
				$parameters = array($this->id), 
				$attributes = array('class' => 'btn btn-mini disabled delete-link'));


			} else {
				$link = link_to_route('sales.delete', "Deletar", 
				$parameters = array($this->id), 
				$attributes = array('class' => 'btn btn-mini disabled delete-link'));
			}
			$edit_link = link_to_route('sales.edit', "Editar", 
				$parameters = array($this->id), 
				$attributes = array('class' => 'btn btn-mini disabled edit-link'));
			$link .= ' ' . $edit_link;
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
	public function getPrettyCreatedAtAttribute()
	{
		return $this->created_at->format('d/m/Y à\s H:i:s');
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
		$user = User::find($this->user_id);
      if($user)
      {
         $return = $user->name;
      } else {
         $return = "(Deletado)";
      }
      return $return;
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
		if ($this->is_alive) { 
			$user = User::find($this->user_id);
		} else {
			$user = User::find($this->deleted_by);
		}
		
      if($user)
      {
         $user_name = $user->name;
      } else {
         $user_name = "(Deletado)";
      }

		if (!$this->is_alive) {
			$meta = "<span class='deleted-item'></span> <span class='label label-important'>Deletado</span> por <strong>" . $user_name . "</strong>";
		} else {
			$meta = "Registrado por <strong>" . $user_name . "</strong>";
		}
		return $meta;
	}

	public function safe_delete()
	{
		
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