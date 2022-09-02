<?php

namespace Database\Seeders;

use App\Models\User;
use DateTime;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('units')->insert([
            [
                'name'      => 'Ap 100',
                'owner'     => User::inRandomOrder()->first()->id,
                'created_at' => new DateTime('now')
            ],
            [
                'name'      => 'Ap 101',
                'owner'     => User::inRandomOrder()->first()->id,
                'created_at' => new DateTime('now')
            ],
            [
                'name'      => 'Ap 200',
                'owner'     => null,
                'created_at' => new DateTime('now')
            ],
            [
                'name'      => 'Ap 201',
                'owner'     => null,
                'created_at' => new DateTime('now')
            ],
        ]);
    }
}
