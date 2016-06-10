<?php

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->command->info("Users table seeded :)");
        $this->call(DiscoverMethodsTableSeeder::class);
        $this->command->info("Discover methods table seeded :)");
        $this->call(ProfessionTableSeeder::class);
        $this->command->info("Profession methods table seeded :)");
    }
}
