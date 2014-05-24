<?php

use Illuminate\Database\Migrations\Migration;

class AddVirtualQuantityToItems extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('items', function($table)
		{
				$table->integer('virtual_quantity')->default(0);
		});
		Schema::table('parts', function($table)
		{
				$table->integer('virtual_quantity')->default(0);
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
		Schema::table('items', function($table)
		{
				$table->dropColumn('virtual_quantity');
		});
		Schema::table('parts', function($table)
		{
				$table->dropColumn('virtual_quantity');
		});
	}

}