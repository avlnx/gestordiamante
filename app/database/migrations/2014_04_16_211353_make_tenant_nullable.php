<?php

use Illuminate\Database\Migrations\Migration;

class MakeTenantNullable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('users', function($table)
		{
		    $table->dropColumn('tenant_id');
		});
		Schema::table('users', function($table)
		{
		    $table->integer('tenant_id')->nullable();
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
		Schema::table('users', function($table)
		{
			$table->dropColumn('tenant_id');
		});
		Schema::table('users', function($table)
		{
		    $table->integer('tenant_id');
		});
	}

}