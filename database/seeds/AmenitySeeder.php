<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AmenitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	DB::table('amenities')->insert([
		[
			'id' => 1,
			'name' => 'balcony',
			'label' => 'Balcony / Roof Terrace'
		],[
			'id' => 2,
			'name' => 'disabled',
			'label' => 'Disabled Access'
		],[
			'id' => 3,
			'name' => 'furnishings',
			'label' => 'Furnishings'
		],[
			'id' => 4,
			'name' => 'garage',
			'label' => 'Garage'
		],[
			'id' => 5,
			'name' => 'garden',
			'label' => 'Garden / Patio'
		],[
			'id' => 6,
			'name' => 'ht',
			'label' => 'Home Theater'
		],[
			'id' => 7,
			'name' => 'internet',
			'label' => 'Internet'
		],[
			'id' => 8,
			'name' => 'living',
			'label' => 'Common / Living Room'
		],[
			'id' => 9,
			'name' => 'parking',
			'label' => 'Parking'
		],[
			'id' => 10,
			'name' => 'tv',
			'label' => 'TV'
		]
	]);
        //
    }
}
