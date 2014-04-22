<?php

use Illuminate\Database\Migrations\Migration;

class AddDeletedByToSnapshots extends Migration {

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
		    $table->integer('deleted_by')->nullable();	// User Model
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
		    $table->dropColumn('deleted_by');	// User Model
		});
	}

}