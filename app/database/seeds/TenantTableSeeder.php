<?php

class TenantTableSeeder extends Seeder
{
	public function run()
	{
		DB::table('tenants')->delete();

		// Seed root's tenant
		DB::table('tenants')->insert(array(
			'email'			=>	NULL,
			'created_at'	=>	date('Y-m-d H:m:s'),
			'updated_at'	=>	date('Y-m-d H:m:s'),
			'company'		=> 	'root'
		));

		// Seed models
		// UP
		DB::table('tenants')->insert(array(
			'email'			=>	NULL,
			'created_at'	=>	date('Y-m-d H:m:s'),
			'updated_at'	=>	date('Y-m-d H:m:s'),
			'company'		=> 	'up',
			'is_model'		=>	true
		));
	}
}