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

		$this->call('GroupsTablesSeeder');
		$this->command->info('Groups Seeded');

		$this->call('UserTableSeeder');
		$this->command->info('Admin User Seeded');

		$this->call('TerritoriesTypesSeeder');
		$this->command->info('Territories Types Seeded');

		$this->call('TerritoriesAreaTypesSeeder');
		$this->command->info('Territories Area Types Seeded');

		$this->call('SettingsSeeder');
		$this->command->info('Settings Seeded');
	}

}

class GroupsTablesSeeder extends Seeder {

	public function run()
	{
		Sentry::createGroup([
			'name'        => 'Admins',
		    'permissions' => [
			    'admin' => 1
		    ]
		]);
		Sentry::createGroup([
            'name'        => 'Publishers',
            'permissions' => [
                'publisher' => 1
            ]
        ]);
	}
}

class UserTableSeeder extends Seeder {

	public function run()
	{
		// create the user
		$user = Sentry::createUser([
			'first_name' => '',
			'last_name'  => '',
			'email'      => 'admin@territorymanager.org',
			'username'   => 'admin',
			'password'   => 'territorymanager',
			'activated'  => TRUE
		]);

		$admin_group = Sentry::findGroupById(1);

		$user->addGroup($admin_group);
	}
}

class TerritoriesTypesSeeder extends Seeder {

	public function run()
	{
		TerritoriesTypes::create(['name' => 'house_to_house', 'label' => 'House to House']);
		TerritoriesTypes::create(['name' => 'business', 'label' => 'Business']);
		TerritoriesTypes::create(['name' => 'letter-writing', 'label' => 'Letter Writing']);
	}
}

class TerritoriesAreaTypesSeeder extends Seeder {

	public function run()
	{
		TerritoriesAreaTypes::create(['name' => 'urban', 'label' => 'Urban']);
		TerritoriesAreaTypes::create(['name' => 'suburb', 'label' => 'Suburb']);
		TerritoriesAreaTypes::create(['name' => 'rural', 'label' => 'Rural']);
		TerritoriesAreaTypes::create(['name' => 'rural_development', 'label' => 'Rural Development']);
		TerritoriesAreaTypes::create(['name' => 'na', 'label' => 'N/A']);
	}
}

class SettingsSeeder extends Seeder {

	public function run()
	{
		Settings::create(['option_name' => 'admin_email', 'option_value' => NULL]);
		Settings::create(['option_name' => 'mapbox_api_key', 'option_value' => NULL]);
		Settings::create(['option_name' => 'territory_due_date_months', 'option_value' => '4']);
		Settings::create(['option_name' => 'territory_not_worked_interval_months', 'option_value' => 6]);
		Settings::create(['option_name' => 'territory_amount_available_house', 'option_value' => 15]);
		Settings::create(['option_name' => 'territory_amount_available_business', 'option_value' => 0]);
		Settings::create(['option_name' => 'territory_amount_available_lwp', 'option_value' => 5]);
		Settings::create(['option_name' => 'number_territories_allowed', 'option_value' => 3]);
		Settings::create(['option_name' => 'territory_soon_days', 'option_value' => 21]);
		Settings::create(['option_name' => 'reports_force_page_break', 'option_value' => 'true']);
		Settings::create(['option_name' => 'number_daily_dropbox_backups', 'option_value' => 5]);
		Settings::create(['option_name' => 'number_weekly_dropbox_backups', 'option_value' => 5]);
	}
}