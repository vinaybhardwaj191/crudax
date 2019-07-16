<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        App\User::create([
            'name'              =>  'VINAY',
            'email'             =>  'vinaybhardwaj191@gmail.com',
            'password'          =>  Hash::make( 'password' ),
            'type'              =>  'admin',
            'remember_token'    =>  str_random( 10 )
        ]);

        factory( App\User::class , 36 )->create();
    }
}
