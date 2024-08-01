<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // team_member
        for ($i = 0; $i < 30; $i++) {
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => 'team_member'.$i.'@gmail.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
                'email_verified_at' => now(),
                'role' => 'team_member'
            ]);
        }

        // project managers
        for ($i = 0; $i < 10; $i++) {
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => 'project_manager'.$i.'@gmail.com',
                'password' => Hash::make('password'),
                'created_at' => now(),
                'updated_at' => now(),
                'email_verified_at' => now(),
                'role' => 'project_manager'
            ]);
        }

        // admin
        DB::table('users')->insert([
            'name' => 'CocoAdmin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'created_at' => now(),
            'updated_at' => now(),
            'email_verified_at' => now(),
            'role' => 'admin'
        ]);
    }
}
