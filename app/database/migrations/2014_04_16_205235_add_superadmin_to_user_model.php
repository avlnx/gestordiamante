<?php

use Illuminate\Database\Migrations\Migration;

class AddSuperadminToUserModel extends Migration {

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
		    $table->boolean('is_superadmin')->default(false);
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
		    $table->dropColumn('is_superadmin');
		});
	}

}