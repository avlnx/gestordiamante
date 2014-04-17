<?php

class TenantTableSeeder extends Seeder
{
	public function run()
	{
		// Does the root tenant exist?
		$rootTenant = DB::table('tenants')
			->where('company', 'root')
			->first();

		if ($rootTenant == NULL) {
			// Seed root's tenant, it doesn't exist yet
			DB::table('tenants')->insert(array(
				'email'			=>	NULL,
				'created_at'	=>	date('Y-m-d H:m:s'),
				'updated_at'	=>	date('Y-m-d H:m:s'),
				'company'		=> 	'root',
				'account_name'	=>	'Root'
			));
		}

		


	}
}