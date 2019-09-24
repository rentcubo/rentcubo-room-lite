<?php

use Illuminate\Database\Seeder;

class StaticPageDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(Schema::hasTable('static_pages')) {

        	$static_pages = json_decode(json_encode(['privacy' , 'terms' , 'help']));

        	foreach ($static_pages as $key => $value) {

    			$page_details = DB::table('static_pages')->where('type' ,$value)->count();

    			if(!$page_details) {

    				DB::table('static_pages')->insert([

    	         		[
    				        'title' => $value,
    				        'description' => $value,
    				        'type' => $value,
                            'status' => 1,
    				        'created_at' => date('Y-m-d H:i:s'),
    				        'updated_at' => date('Y-m-d H:i:s')
    				    ],
    				]);


    			}

        	}

		}
    }
}
