<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DiscoverMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sentences = [];
        $sentences [] = 'Par un ami';
        $sentences [] = 'Par mon docteur';
        $sentences [] = 'sur internet';

        foreach ($sentences as $sentence) {
            DB::table('discover_methods')->insert(['discover_string'=>$sentence]);
        }
    }
}