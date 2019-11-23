<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AcceptingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
	DB::table('acceptings')->insert([
		[
			'id' => 2,
			'name' => 'couples'
		],[
			'id' => 3,
			'name' => 'gender_pref_m'
		],[
			'id' => 4,
			'name' => 'gender_pref_f'
		],[
			'id' => 5,
			'name' => 'pets'
		],[
			'id' => 6,
			'name' => 'reference'
		],[
			'id' => 7,
			'name' => 'smoking'
		],[
			'id' => 8,
			'name' => 'students'
		],[
			'id' => 9,
			'name' => 'occupation'
		]
	]);
    }
}
