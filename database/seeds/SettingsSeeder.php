<?php

use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('settings')->delete();

    	DB::table('settings')->insert([
    		[
		        'key' => 'site_name',
		        'value' => 'RentCubo'
		    ],
		    [
		        'key' => 'tag_name',
		        'value' => 'Rental'
		    ],
		    [
		        'key' => 'site_logo',
		        'value' => envfile('APP_URL').'/logo.png'
		    ],
		    [
		        'key' => 'site_icon',
		        'value' => envfile('APP_URL').'/favicon.png'
		    ],
			[
		        'key' => 'frontend_url',
		        'value' => envfile('APP_URL')
		    ],
		    [
		        'key' => 'currency',
		        'value' => '$'
		    ],
		    [
		        'key' => 'currency_code',
		        'value' => 'USD'
		    ],
		    [
		        'key' => 'tax_percentage',
		        'value' => 10
		    ],
		    [
		    	'key' => 'admin_take_count',
		    	'value' => 12,
		    ],
		    [
	            'key' => 'is_demo_control_enabled', // For demo purpose
			    'value' => 0       	
			],
		    [
		        'key' => 'installation_steps',
		        'value' => 0
		    ],    
        	[
        		'key' => 'token_expiry_hour',
        		'value' => 10,
        	],
        	[
	            'key' => "copyright_content",
	            'value' => "Copyrights Date('Y-m-d') . All rights reserved.",
        	],

		    [
		        'key' => 'demo_admin_email',
		        'value' => 'admin@rentcubo.com'
		    ],

		    [
		        'key' => 'demo_admin_password',
		        'value' => 123456
		    ],
		    [
		        'key' => 'demo_user_email',
		        'value' => 'user@rentcubo.com'
		    ],
		    [
		        'key' => 'demo_user_password',
		        'value' => 123456
		    ],
		    [
		        'key' => 'demo_provider_email',
		        'value' => 'user@rentcubo.com'
		    ],
		    [
		        'key' => 'demo_provider_password',
		        'value' => 123456
		    ],
		    [
		    	'key' =>'per_base_price',
		    	'value' => 1
		    ]
		]);
		
    }
}
