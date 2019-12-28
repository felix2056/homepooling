<?php

use Illuminate\Database\Seeder;

use App\User;

class add1User extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	echo PHP_EOL , 'adding a single user...';

        /*$user = User::where('id', 27);
        $user->email = 'test@test.com';
        $user->password = Hash::make('password');
        
        $user->save();*/
        $user= User::create(
            [
                'name' => 'test',
                'family_name' => 'tester',
                'email' => 'test@tester.com',
                'password' => bcrypt('tester'),
                'gender' => 'm',
                'msg_in_remain' => 9999,
                'early_bird' => 0
            ]
        );
    }
}
