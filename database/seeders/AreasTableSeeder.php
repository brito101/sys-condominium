<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('areas')->insert([
            [
                'allowed' => 1,
                'title' => 'Academia',
                'days' => '1,2,4,5',
                'start' => '06:00:00',
                'end' => '22:00:00',
                'created_at' => new DateTime('now')
            ],
            [
                'allowed' => 1,
                'title' => 'Piscina',
                'days' => '1,3,4,5,6,7',
                'start' => '06:00:00',
                'end' => '22:00:00',
                'created_at' => new DateTime('now')
            ],
            [
                'allowed' => 1,
                'title' => 'Churrasqueira',
                'days' => '2,3,4,5,6,7',
                'start' => '06:00:00',
                'end' => '22:00:00',
                'created_at' => new DateTime('now')
            ],
        ]);
    }
}
