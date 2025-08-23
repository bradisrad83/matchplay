<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Format;

class FormatsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Format::create([
            'name' => 'Captains Choice',
            'description' => 'Each player will drive off the teebox and the team picks the best one. Everyone then hits their next shot from that spot. This continues until the ball is holed.'
        ]);

        Format::create([
            'name' => 'Alternate Shot',
            'description' => 'Before the round you much determine who will be teeing off odd and even holes.  After the tee shot its alternate shots until the ball is holed out.  It does not matter who makes the final putt or chip, you will tee off based on the odd and even decision pre round.'
        ]);

        Format::create([
            'name' => 'Four Ball',
            'description' => 'Everyone plays their own ball the entire hole.  Best score per team gets carded.'
        ]);

        Format::create([
            'name' => 'Texas Scramble',
            'description' => 'Everyone hits off of the tee and from there each player plays their own shots until holed.  Best score is carded.'
        ]);
        
        Format::create([
            'name' => 'Singles',
            'description' => 'Everyone plays and keeps their own score.  Even though you are in a cart with your teammate you are playing solely your opponent.'
        ]);  
    }
}
