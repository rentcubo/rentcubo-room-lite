<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
		$this->call(AdminSeeder::class);
        $this->call(DemoDataSeeder::class);
        $this->call(SettingsSeeder::class);
        $this->call(StaticPageDemoSeeder::class);
        $this->call(LookupSeeder::class);
        $this->call(AddIsEmailNotificationSettings::class);
    }
}
