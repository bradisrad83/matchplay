<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Team;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $commish = User::create([
            'name' => 'Anthony Schambers',
            'nickname' => 'The Commish',
            'email' => 'anthschambers@gmail.com', 
            'phone_number' => '9102283875',
            'role' => 'captain',
            'password' => bcrypt('dsi-2025'),
            'team_id' => 1,
        ]);
        $cappy = User::create([
            'name' => 'Kevin Curran',
            'nickname' => 'Cappy',
            'email' => 'shamrokk11@gmail.com', 
            'phone_number' => '4075580839',
            'role' => 'captain',
            'password' => bcrypt('dsi-2025'),
            'team_id' => 2,
        ]);

        $ramrod = Team::find(1);
        $ramrod->update([
            'user_id' => $commish->id
        ]);

        $roostah = Team::find(2);
        $roostah->update([
            'user_id' => $cappy->id
        ]);
     
        User::create([
            'name' => 'Jesse Helsley',
            'nickname' => 'The Nature Boy',
            'email' => 'helsley.jd@gmail.com', 
            'phone_number' => '8146515995',
            'role' => 'player',
            'password' => bcrypt('dsi-2025'),
            'team_id' => 1,
        ]);

        User::create([
            'name' => 'Steve Sharma', 
            'nickname' => 'MVP',
            'email' => 'sharma@gmail.com', 
            'phone_number' => '9192800659',
            'role' => 'player',
            'password' => bcrypt('dsi-2025'),
            'team_id' => 1,
        ]);

        User::create([
            'name' => 'Matt Christman',
            'nickname' => 'Shiner Boch',
            'email' => 'mchristman23@hotmail.com', 
            'phone_number' => '4075926997',
            'role' => 'player',
            'password' => bcrypt('dsi-2025'),
            'team_id' => 1,
        ]);        

        User::create([
            'name' => 'Jesse Godson',
            'nickname' => 'MacGyver',
            'email' => 'jessegoodson@live.com', 
            'phone_number' => '4074083417',
            'role' => 'player',
            'password' => bcrypt('dsi-2025'),
            'team_id' => 1,
        ]);        

        User::create([
            'name' => 'Rob Jeffreys',
            'nickname' => 'Cartgirl Whisperer',
            'email' => 'robert.jeffreys89@gmail.com', 
            'phone_number' => '8144906186',
            'role' => 'player',
            'password' => bcrypt('dsi-2025'),
            'team_id' => 1,
        ]);

        User::create([
            'name' => 'Adam Cavallini',
            'nickname' => 'Grande',
            'email' => 'adamcav58@gmail.com', 
            'phone_number' => '4077016530',
            'role' => 'player',
            'password' => bcrypt('dsi-2025'),
            'team_id' => 1,
        ]);

        User::create([
            'name' => 'Doug Allen',
            'nickname' => 'Down The Middle',
            'email' => 'dnainnc@yahoo.com', 
            'phone_number' => '9105389485',
            'role' => 'player',
            'password' => bcrypt('dsi-2025'),
            'team_id' => 1,
        ]);

        User::create([
            'name' => 'Chad Saar', 
            'nickname' => 'Ball Far',
            'email' => 'csaar73@gmail.com', 
            'phone_number' => '4074927115',
            'role' => 'player',
            'password' => bcrypt('dsi-2025'),
            'team_id' => 1,
        ]);        

        User::create([
            'name' => 'Jimmy Bartlett',
            'nickname' => 'Smash Ball',
            'email' => 'jb9102973580@gmail.com', 
            'phone_number' => '9102973580',
            'role' => 'player',
            'password' => bcrypt('dsi-2025'),
            'team_id' => 1,
        ]);

        User::create([
            'name' => 'Tony Phillips',
            'nickname' => 'Wrecking Ball',
            'email' => 'tphillipscounty@yahoo.com', 
            'phone_number' => '9104449202',
            'role' => 'player',
            'password' => bcrypt('dsi-2025'),
            'team_id' => 1,
        ]);

        User::create([
            'name' => 'Scott Powers', 
            'nickname' => 'Kenny',
            'email' => 'scott@gmail.com', 
            'phone_number' => '4074927115',
            'role' => 'player',
            'password' => bcrypt('dsi-2025'),
            'team_id' => 2,
        ]);      
        
        User::create([
            'name' => 'Rob Clark', 
            'nickname' => 'Iceman',
            'email' => 'robclark00@gmail.com', 
            'phone_number' => '4076164210',
            'role' => 'player',
            'password' => bcrypt('dsi-2025'),
            'team_id' => 2,
        ]);   
        
        User::create([
            'name' => 'Anthony Fitzick',
            'nickname' => 'Three Mile Island',
            'email' => 'afitzick@unitedpaper.net', 
            'phone_number' => '9102321220',
            'role' => 'player',
            'active' => false,
            'password' => bcrypt('dsi-2025'),
            'team_id' => 2,
        ]);
        
        User::create([
            'name' => 'Chris (Brian) Nichols', 
            'nickname' => 'Skeeter',
            'email' => 'skeeter@gmail.com', 
            'phone_number' => '2222222222',
            'role' => 'player',
            'password' => bcrypt('dsi-2025'),
            'team_id' => 2,
        ]);  

        User::create([
            'name' => 'Mark Cavallini',
            'nickname' => 'Skinny Grande',
            'email' => 'markcavallini@gmail.com', 
            'phone_number' => '4072190376',
            'role' => 'player',
            'password' => bcrypt('dsi-2025'),
            'team_id' => 2,
        ]);  

        User::create([
            'name' => 'Carter Wilcoxson', 
            'nickname' => 'TexArKanaZona',
            'email' => 'carter@csifg.com', 
            'phone_number' => '3529782746',
            'role' => 'player',
            'password' => bcrypt('dsi-2025'),
            'team_id' => 2,
        ]);  

        User::create([
            'name' => 'Chuck Bomer',
            'nickname' => "Jesse's Dad",
            'email' => 'bomer492@comcast.com', 
            'phone_number' => '7249892171',
            'role' => 'player',
            'password' => bcrypt('dsi-2025'),
            'team_id' => 2,
        ]);  

        User::create([
            'name' => 'Brendan Rafferty',
            'nickname' => 'Happy Meal',
            'email' => 'rafferty.brendan@yahoo.com', 
            'phone_number' => '4074927115',
            'role' => 'player',
            'password' => bcrypt('dsi-2025'),
            'team_id' => 2,
        ]);  

        User::create([
            'name' => 'Tyler Coyle',
            'nickname' => 'Who Needs Sleep',
            'email' => 'tylercoyle13@gmail.com', 
            'phone_number' => '9192795382',
            'role' => 'player',
            'password' => bcrypt('dsi-2025'),
            'team_id' => 2,
        ]);          

        User::create([
            'name' => 'Brian Mitchell', 
            'nickname' => 'Here for Umbertos',
            'email' => 'mitchell@gmail.com', 
            'phone_number' => '3333333333',
            'role' => 'player',
            'password' => bcrypt('dsi-2025'),
            'team_id' => 2,
        ]);  

        User::create([
            'name' => 'Anthony Cerniglia',
            'nickname' => 'Digital',
            'email' => 'digital@gmail.com', 
            'phone_number' => '4444444444',
            'role' => 'player',
            'password' => bcrypt('dsi-2025'),
            'team_id' => 2,
        ]);  
    }
}
