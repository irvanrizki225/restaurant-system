<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // for ($i=0; $i < 50; $i++) { 
        //     $faker = Faker::create('id_ID');
        //     DB::table('tb_menus')->insert([
        //         'category_id' => '1',
        //         'name' => $faker->name,
        //         'description' => $faker->text,
        //         'price' => $faker->numberBetween(10000, 50000),
        //         'created_at' => date('Y-m-d H:i:s'),
        //         'created_by' => '1',
        //     ]);
        // }

        for ($i=0; $i < 1; $i++) { 
            $faker = Faker::create('id_ID');

            DB::table('tb_employees')->insert([
                'user_id' => '1',
                'name' => $faker->name,
                'position' => 'Pelayan',
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => 'admin',
            ]);

        }
        
    }
}
