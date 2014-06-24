<?php

use Illuminate\Database\Migrations\Migration;

class AddNotesToSnapshots extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::table('snapshots', function($table)
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
		Schema::table('snapshots', function($table)
		{
				$table->dropColumn('notes');
		});
	}

}