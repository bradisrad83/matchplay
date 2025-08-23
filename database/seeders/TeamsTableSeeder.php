<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Team; 

class TeamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Team::create([
            'name' => 'TEAM RAMROD',
            'league_id' => 1,
        ]);

        Team::create([
            'name' => 'WEiRD ROOSTAHS',
            'league_id' => 1,
        ]);  

    }
}
