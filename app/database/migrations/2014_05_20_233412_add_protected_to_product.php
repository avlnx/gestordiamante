<?php

use Illuminate\Database\Migrations\Migration;

class AddProtectedToProduct extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('products', function($table)
		{
				$table->boolean('is_protected')->default(true);
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
		Schema::table('products', function($table)
		{
				$table->dropColumn('is_protected');
		});
	}

}