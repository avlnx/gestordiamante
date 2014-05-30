<?php

class Snapshot extends Eloquent
{
	protected $guarded = array('id');
	protected $appends = array('pretty_date','creator','virtual_real_or_ambos');

	public function tenant()
	{
		return $this->belongsTo('Tenant');
	}

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function parts()
	{
		return $this->hasMany('Part');
	}

	public function total_value()
	{
		$parts = $this->parts()->get();
      $part = $parts->first();

      $type = $part->virtual_real_or_ambos();

		$total = 0;
		//print_r($parts);
		foreach ($parts as $part) {
         switch ($type) {
            case 'virtual':
               $quantity = $part->virtual_quantity;
               break;
            default:
               $quantity = $part->quantity;
               break;
         }
			$total += ($part->current_price * $quantity);

		}
		return $total;
	}

	public function num_of_products()
	{
		$parts = $this->parts()->get();
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

   public function getVirtualRealOrAmbosAttribute()
   {
      
      $part = Part::where('snapshot_id','=',$this->id)->first();

      $type = $part->virtual_real_or_ambos();
      $html = '';
      if ($type != 'ambos') {
         $ctype = strtoupper($type);
         $html = "<span class='label label-important'>$ctype</span>";
      }
      
      return $html;
   }


	/** Overload methods to account for tenants **/

	public static function all($columns = array('*'))
	{
		return parent::where('tenant_id', '=', Auth::user()->tenant_id)->where('is_alive','=',true)->orderBy('created_at', 'desc')->get();
	}
}
