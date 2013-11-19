<?php

use Illuminate\Database\Migrations\Migration;

class CreateProductsCategoriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('categories', function($table) {
			$table->increments('id');
			$table->integer('tenant_id');
			$table->string('name');
			$table->string('slug');
			$table->text('description')->nullable();
			$table->boolean('is_alive');
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
		// drop categories table
		Schema::drop('categories');
	}

}