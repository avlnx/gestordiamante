<?php

use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create users table
		Schema::create('users', function($table) {
			$table->increments('id');
			$table->integer('tenant_id');
			$table->string('email', 128);
			$table->string('password', 64);
			$table->boolean('is_admin');
			$table->boolean('is_root');
			$table->string('name');
			$table->boolean('is_alive')->default(true);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
		Schema::drop('users');
	}

}