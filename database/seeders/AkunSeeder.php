<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;

class AkunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(20)->create();

        if (!DB::table('users')->where('email', 'owner@example.com')->exists()) {
            DB::table('users')->insert([
                'name' => 'Owner',
                'email' => 'owner@example.com',
                'password' => Hash::make('1'),
                'role_id' => 1,
                'isSuspended' => false,
                'no_telp' => '081234567890',

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        if (!DB::table('users')->where('email', '2@2')->exists()) {
            DB::table('users')->insert([
                'name' => 'Pegawai Percobaan',
                'email' => '2@2',
                'password' => Hash::make('2'),
                'role_id' => 2,
                'isSuspended' => false,
                'no_telp' => '081029384756',

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        if (!DB::table('users')->where('email', 'hikam@example.com')->exists()) {
            DB::table('users')->insert([
                'name' => 'Mohammad Al Hikam',
                'email' => 'hikam@example.com',
                'password' => Hash::make('3'),
                'role_id' => 3,
                'isSuspended' => false,
                'no_telp' => '081331178493',

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
