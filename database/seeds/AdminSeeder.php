<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(Schema::hasTable('admins')) {

            $check_admin_details = DB::table('admins')->where('email' , 'admin@rentcubo.com')->count();

            if(!$check_admin_details) {

            	DB::table('admins')->insert([
            		[
        		        'name' => 'Admin',
        		        'email' => 'admin@rentcubo.com',
        		        'password' => \Hash::make('123456'),
        		        'picture' => envfile('APP_URL')."/placeholder.jpg",
                        'status' => 1,
        		        'created_at' => date('Y-m-d H:i:s'),
        		        'updated_at' => date('Y-m-d H:i:s')
        		    ]
                ]);

            }

            $check_test_admin_details = DB::table('admins')->where('email' , 'test@rentcubo.com')->count();

            if(!$check_test_admin_details) {

                DB::table('admins')->insert([

                    [
                        'name' => 'Test',
                        'email' => 'test@rentcubo.com',
                        'password' => \Hash::make('123456'),
                        'picture' => envfile('APP_URL')."/placeholder.jpg",
                        'status' => 1,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ],
    		    ]);
            }
        }
    }
}
