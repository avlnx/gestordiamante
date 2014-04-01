<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		// Create root account
		$this->call('TenantTableSeeder');

		// Create root user
		$this->call('UserTableSeeder');
		// $this->command->info('Root User Seeded...');

		// Create Model UP Tenant
		$this->call('ModelUpSeeder');
		// $this->command->info('Model UP! Tenant Seeded...');

	}

}