<?php

use Illuminate\Database\Migrations\Migration;

class ChangePartsDecimalPoints extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('parts', function($table)
		{
				$table->dropColumn('current_price');
		});
		Schema::table('parts', function($table)
		{
				$table->decimal('current_price',12,2);
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
		Schema::table('parts', function($table)
		{
				$table->dropColumn('current_price');
		});
		Schema::table('parts', function($table)
		{
				$table->decimal('current_price',5,2);
		});
	}

}