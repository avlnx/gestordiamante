<?php

use Illuminate\Database\Migrations\Migration;

class AddUserToSnapshotsAndSales extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('snapshots', function($table)
		{
		    $table->integer('user_id')->default(2);
		});
		Schema::table('sales', function($table)
		{
		    $table->integer('user_id')->default(2);
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
		Schema::table('snapshots', function($table)
		{
		    $table->dropColumn('user_id');
		});
		Schema::table('sales', function($table)
		{
		    $table->dropColumn('user_id');
		});
	}

}