<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = array();
        for ($i = 1; $i <= 10; $i++)
        {
            $users[] = array(
                "name" => "Admin {$i}",
                "username" => "admin{$i}",
                "email" => "admin{$i}@tsalary.com",
                // "password" => Str::random(8),
                "password" => Hash::make("12345678"),
                "created_at" => Carbon::now()->format('Y-m-d H:i:s')
            );
        }

        DB::table("users")->insert($users);
    }
}
