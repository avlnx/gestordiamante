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

   public function virtual_real_or_ambos()
   {

      $virtual = $this->virtual_quantity;
      $real = $this->quantity;

      if ($virtual == 0 && $real != 0) {
         // pedido apenas real
         $return = 'real';
      } else if ($real == 0 && $virtual != 0) {
         // pedido apenas virtual
         $return = 'virtual';
      } else {
         $return = 'ambos';
      }
      return $return;
   }

}