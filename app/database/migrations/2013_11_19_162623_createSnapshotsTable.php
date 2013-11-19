<?php

use Illuminate\Database\Migrations\Migration;

class CreateSnapshotsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Create snapshots table
		/* 	A snapshot has many parts
			When added together, all the parts value tells the snapshot's value
		*/
		Schema::create('snapshots', function($table)
		{
			$table->increments('id');
			$table->integer('tenant_id');
			$table->boolean('is_alive')->default(true);
			$table->string('type')->default('snapshot');	// snapshot, entry, baixas
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
		/* 	A 'part' belongs to a snapshot
			A 'part' holds information regarding one product and it's quantity on this lot
		*/
 		Schema::create('parts', function($table)
		{
			$table->increments('id');
			$table->integer('tenant_id');
			$table->integer('snapshot_id');
			$table->integer('product_id');
			$table->decimal('current_price',5,2);
			$table->integer('quantity');	// quantidade unitaria
			$table->boolean('is_alive')->default(true);
			$table->timestamps();
		});
	}

}