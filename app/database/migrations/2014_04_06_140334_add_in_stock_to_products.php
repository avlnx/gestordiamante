<?php

use Illuminate\Database\Migrations\Migration;

class AddInStockToProducts extends Migration {

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
		    $table->integer('quantity_in_stock')->default(0);
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
		    $table->dropColumn('quantity_in_stock');
		});
	}

}