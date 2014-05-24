<?php

use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('sales', function($table)
		{
			$table->increments('id');
			$table->integer('tenant_id');
			//$table->text('data');	// id-quantidade_unitaria-preço; [...]
			$table->boolean('is_alive')->default(true);
			$table->timestamps();
			$table->decimal('debit', 6,2);
			$table->decimal('credit', 6,2);
			$table->decimal('bonus', 6,2);
			$table->decimal('deposit', 6,2);
			$table->decimal('cash', 6,2);
			$table->string('order_number');
		});

		Schema::create('items', function($table)
		{
			$table->increments('id');
			$table->integer('tenant_id');
			$table->integer('sale_id');
			$table->integer('product_id');
			$table->decimal('current_price',12,2);
			$table->integer('quantity');	// quantidade unitaria
			$table->boolean('is_alive')->default(true);
			$table->timestamps();
			
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
		Schema::drop('sales');
		Schema::drop('items');
	}

}