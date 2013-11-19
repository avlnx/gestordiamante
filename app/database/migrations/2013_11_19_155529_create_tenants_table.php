<?php

use Illuminate\Database\Migrations\Migration;

class CreateTenantsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create tenants table
		Schema::create('tenants', function($table){
			$table->increments('id');
			$table->string('email')->nullable();
			$table->timestamps();
			$table->boolean('is_alive')->default(true);
			$table->string('company')->default('up');
			$table->boolean('is_model')->default(false);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// Drop tenants table
		Schema::drop('tenants');
	}

}