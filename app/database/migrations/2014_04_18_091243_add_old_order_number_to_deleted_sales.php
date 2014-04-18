<?php

use Illuminate\Database\Migrations\Migration;

class AddOldOrderNumberToDeletedSales extends Migration {

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
		    $table->string('order_number_before_delete')->nullable();
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
		    $table->dropColumn('order_number_before_delete');
		});
	}

}