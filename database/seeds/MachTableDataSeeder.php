<?php

use Illuminate\Database\Seeder;
use App\Match;

class MachTableDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Match::create([
            'teamA' => '1',
            'teamB' => '2',
            'winner' => '2',
            'points' => '2',
        ],[
            'teamA' => '1',
            'teamB' => '2',
            'winner' => '1',
            'points' => '2',
        ]);
    }
}
