<?php

class UserTableSeeder extends Seeder
{
	public function run()
	{
		DB::table('users')->delete();

		// Get main tenant
		$tenant_id = DB::table('tenants')->where('company', 'root')->pluck('id');
		
		// Insert root user
		DB::table('users')->insert(array(
			'email'			=>	'thferreira@gmail.com',
			'tenant_id'		=>	$tenant_id,
			'password'		=>	Hash::make('ntmesfecjo19');,
			'is_admin'		=> 	true,
			'is_root'		=>	true,
			'name'			=> 	'Equipe Gestor Diamante',
			'created_at'	=>	date('Y-m-d H:m:s'),
			'updated_at'	=>	date('Y-m-d H:m:s')
		));
	}
}