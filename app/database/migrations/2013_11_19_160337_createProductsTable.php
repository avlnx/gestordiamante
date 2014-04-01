<?php

use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('products', function($table) {
			$table->increments('id');
			$table->integer('tenant_id');
			$table->integer('category_id');
			$table->string('name');
			$table->string('slug');
			$table->text('description')->nullable();
			$table->decimal('price', 10, 2);
			$table->float('margin')->nullable();
			$table->integer('box')->nullable();
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
		Schema::drop('products');
	}

}