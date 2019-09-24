<?php

use Illuminate\Database\Seeder;

class AddIsEmailNotificationSettings extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
    		[
		    	'key' =>'is_email_notification',
		    	'value' => 1
		    ]
		]);
    }
}
