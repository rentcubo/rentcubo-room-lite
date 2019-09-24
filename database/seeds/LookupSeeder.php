<?php

use Illuminate\Database\Seeder;

class LookupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	DB::table('lookups')->insert([
    		[
		        'type' => 'host_room_type',
		        'key' => 'entire_place',
		        'value' => 'Entire Place',
		    ],
		    [
		        'type' => 'host_room_type',
		        'key' => 'private_place',
		        'value' => 'Private Place',
		    ],
		    [
		        'type' => 'host_room_type',
		        'key' => 'shared_place',
		        'value' => 'Shared Place',
		    ]
		]);
		
    }
}
