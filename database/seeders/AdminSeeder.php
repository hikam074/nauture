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
                'no_telp' => '101010101010',

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        if (!DB::table('users')->where('email', 'pegawai@nauture.com')->exists()) {
            DB::table('users')->insert([
                'name' => 'Dummy Pegawai',
                'email' => 'pegawai@nauture.com',
                'password' => Hash::make('pegawai'),
                'role_id' => 2,
                'isSuspended' => false,
                'no_telp' => '202020202020',

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        if (!DB::table('users')->where('email', 'customer@gmail.com')->exists()) {
            DB::table('users')->insert([
                'name' => 'Dummy Customer',
                'email' => 'customer@gmail.com',
                'password' => Hash::make('customer'),
                'role_id' => 3,
                'isSuspended' => false,
                'no_telp' => '303030303030',

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
                'no_telp' => '212121212121',

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        if (!DB::table('users')->where('email', '3@3')->exists()) {
            DB::table('users')->insert([
                'name' => '3 Customer',
                'email' => '3@3',
                'password' => Hash::make('3'),
                'role_id' => 2,
                'isSuspended' => false,
                'no_telp' => '313131313131',

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
