<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class ProfessionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $professions = [];
        $professions [] = 'Acupuncture';
        $professions [] = 'Moxibustion';
        $professions [] = 'Aérobic ';

        foreach ($professions as $profession) {
            DB::table('professions')->insert(['profession'=>$profession]);
        }
    }
}