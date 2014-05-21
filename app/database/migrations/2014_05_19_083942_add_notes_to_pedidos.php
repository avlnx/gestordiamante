<?php

use Illuminate\Database\Migrations\Migration;

class AddNotesToPedidos extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('sales', function($table)
		{
				$table->text('notes')->nullable();
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
		Schema::table('sales', function($table)
		{
				$table->dropColumn('notes');
		});
	}

}
