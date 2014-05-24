<?php

class Product extends Eloquent
{
	protected $appends = array('category_slug','pretty_difference_in_stock');

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

	public function getCategorySlugAttribute()
	{
		return $this->category->slug;
	}

	public function getPrettyDifferenceInStockAttribute()
	{
		$real = $this->quantity_in_stock;
		$virtual = $this->quantity_in_virtual;
		$dif = $virtual-$real;
		if ($dif == 0 ) {
			return "<span class='label label-success'><small>OK</small>";
		}

		if ($virtual > $real) {
			$inner = "Sobrando $dif";
			$class = 'info';
		} else {
			$inner = "Faltando ".-$dif;
			$class = 'warning';
		} 

		$html = "<span class='label label-$class'>$inner</span> <small>unidades no virtual</small>";

		return $html;
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

	public function update_quantities($qtd_real,$qtd_virtual,$type)
	{
		/*
		if ($qtd_virtual == 'not_set') {
			// default to same as $qtd_real
			$qtd_virtual = $qtd_real;
		}
		*/
		if ($type == 'add') {
			$this->quantity_in_stock += $qtd_real;
			$this->quantity_in_virtual += $qtd_virtual;
		} else {
			$this->quantity_in_stock -= $qtd_real;
			$this->quantity_in_virtual -= $qtd_virtual;
		}
		$this->save();
	}
	
}