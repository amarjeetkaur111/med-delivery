<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        for ($i = 0; $i < 1000; $i++) {
        DB::table('customers')->insert([
            'FirstName' => $faker->name,
            'LastName' => $faker->name,
            'Status' => 1,
         ]);
        }
    }
}
