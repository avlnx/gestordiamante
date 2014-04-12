<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

	public function tenant()
	{
		return $this->belongsTo('Tenant');
	}

	/** Overload methods to account for tenants **/

	public static function all($columns = array('*'))
	{
		/*
		$users = parent::all();
		$users = $users->filter(function($user)
		{
			if ($user->tenant_id == Auth::user()->tenant_id && $user->is_alive == true) {
				return $user;
			}
		});

		return $users;
		*/
		return parent::where('tenant_id', '=', Auth::user()->tenant_id)->where('is_alive','=',true)->get();
	}

}