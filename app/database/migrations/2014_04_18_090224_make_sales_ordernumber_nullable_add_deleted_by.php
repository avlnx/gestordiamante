<?php

use Illuminate\Database\Migrations\Migration;

class MakeSalesOrdernumberNullableAddDeletedBy extends Migration {

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
		    $table->dropColumn('order_number');
		});
		Schema::table('sales', function($table)
		{
		    $table->string('order_number')->unique()->nullable();
		});
		Schema::table('sales', function($table)
		{
		    $table->integer('deleted_by')->nullable();
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
		    $table->dropColumn('order_number');
		});
		Schema::table('sales', function($table)
		{
		    $table->string('order_number');
		});
		Schema::table('sales', function($table)
		{
		    $table->dropColumn('deleted_by');
		});
	}

}