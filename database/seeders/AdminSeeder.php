<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!DB::table('users')->where('email', 'owner@nauture.com')->exists()) {
            DB::table('users')->insert([
                'name' => 'Owner',
                'email' => 'owner@nauture.com',
                'password' => Hash::make('owner'),
                'role_id' => 1,
                'isSuspended' => false,

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        if (!DB::table('users')->where('email', 'dummy@nauture.com')->exists()) {
            DB::table('users')->insert([
                'name' => 'Dummy Pegawai',
                'email' => 'dummy@nauture.com',
                'password' => Hash::make('pegawai'),
                'role_id' => 2,
                'isSuspended' => false,

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        if (!DB::table('users')->where('email', 'dummy@gmail.com')->exists()) {
            DB::table('users')->insert([
                'name' => 'Dummy Customer',
                'email' => 'dummy@gmail.com',
                'password' => Hash::make('customer'),
                'role_id' => 3,
                'isSuspended' => false,

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (!DB::table('users')->where('email', '2@2')->exists()) {
            DB::table('users')->insert([
                'name' => '2 Pegawai',
                'email' => '2@2',
                'password' => Hash::make('2'),
                'role_id' => 2,
                'isSuspended' => false,

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
