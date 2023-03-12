<?php

namespace Database\Seeders;

use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WallNoticesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('wall_notices')->insert([
            [
                'title' => 'Título aviso',
                'body' => 'bla bla bla',
                'created_at' => new DateTime('now')
            ],
        ]);
    }
}
