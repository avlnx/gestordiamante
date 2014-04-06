<?php

use Illuminate\Database\Migrations\Migration;

class CreateStaticStockTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('static_stocks', function($table)
		{
		    $table->increments('id');
		    $table->integer('tenant_id');

		    $table->timestamps();
		});

		// Add optional field 'static_stock' to Part model
		Schema::table('parts', function($table)
		{
		    $table->integer('static_stock_id')->nullable();
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
		Schema::drop('static_stocks');
		Schema::table('parts', function($table)
		{
		    $table->dropColumn('static_stock_id');
		});
	}

}
