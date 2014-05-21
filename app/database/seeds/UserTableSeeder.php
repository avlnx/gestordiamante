<?php

class UserTableSeeder extends Seeder
{
	public function run()
	{

		// Get main tenant
		$tenant_id = DB::table('tenants')->where('company', 'root')->pluck('id');
		
		// Insert root user if it doesn't exist yet
		$user_id = DB::table('users')
			->where('is_root', true)
			->where('email', 'thferreira@gmail.com')
			->pluck('id');

		if ($user_id == NULL) {
			DB::table('users')->insert(array(
				'email'			=>	'thferreira@gmail.com',
				'tenant_id'		=>	$tenant_id,
				'password'		=>	Hash::make('ntmesfecjo19'),
				'is_admin'		=> true,
				'is_root'		=>	true,
				'is_superadmin'=> true,
				'name'			=> 'Equipe Gestor Diamante',
				'created_at'	=>	date('Y-m-d H:m:s'),
				'updated_at'	=>	date('Y-m-d H:m:s')
			));
		}
		
	}
}