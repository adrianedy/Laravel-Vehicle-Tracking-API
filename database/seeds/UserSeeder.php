<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name'       => 'user',
            'email'      => 'user',
            'password'   => bcrypt('user'),
            'pin'        => bcrypt('12345'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
