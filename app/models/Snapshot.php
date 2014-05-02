<?php

class Snapshot extends Eloquent
{
	protected $guarded = array('id');
	protected $appends = array('pretty_date','creator');
	
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


	/** Overload methods to account for tenants **/

	public static function all($columns = array('*'))
	{
		return parent::where('tenant_id', '=', Auth::user()->tenant_id)->where('is_alive','=',true)->orderBy('created_at', 'desc')->get();
	}
}
