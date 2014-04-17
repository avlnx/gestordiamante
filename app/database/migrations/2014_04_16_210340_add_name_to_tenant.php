<?php

use Illuminate\Database\Migrations\Migration;

class AddNameToTenant extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('tenants', function($table)
		{
		    $table->string('account_name')->default('Sem Nome');
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
		Schema::table('tenants', function($table)
		{
		    $table->dropColumn('account_name');
		});
	}

}