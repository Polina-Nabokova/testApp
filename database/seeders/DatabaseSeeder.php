<?php

namespace Database\Seeders;

use App\Models\Users;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {        
        //run only one time     
        if(!DB::table('positions')->count()) {
            DB::table('positions')->insert([
                ["id" => 1, "name" => "Lawyer"],
                ["id" => 2, "name" => "Content manager"],
                ["id" => 3, "name" => "Security"],
                ["id" => 4, "name" => "Designer"]
            ]);
        }       
       
        Users::factory()->count(1)->create();       
    }
}
