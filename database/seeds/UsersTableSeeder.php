<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


require 'vendor/fzaninotto/Faker/src/autoload.php';

class UsersTableSeeder extends Seeder
{
    private $faker;
    private $type;
    private $typeReadable;

    public function __construct()
    {
        $this->type = ['App\PublicUser', 'App\AdminUser', 'App\ExpertUser', 'App\PraticianUser'];
        $this->typeReadable = ['Publique', 'Admin', 'Expert', 'Praticien'];
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'civility' => 'M',
            'name' => 'Danguiral',
            'firstname' => 'Florent',
            'phone' => '06',
            'discover_method_id' => 1,
            'email' => 'florent.danguiral@gmail.com',
            'password' => bcrypt('florent'),
            'enable' => true,
            'userable_type' => 'App\PublicUser',
            'userable_type_readable' => 'publique',
        ]);

        DB::table('users')->insert([
            'civility' => 'M',
            'name' => 'Legris',
            'firstname' => 'Yves',
            'phone' => '06',
            'discover_method_id' => 1,
            'email' => 'ylegris94@gmail.com',
            'password' => bcrypt('azerty'),
            'enable' => true,
            'userable_type' => 'App\PublicUser',
            'userable_type_readable' => 'publique',
        ]);

        for ($i = 0; $i < 50; $i++) {

            DB::table('users')->insert($this->generateFakeUser());
        }

    }

    function generateFakeUser()
    {
        $faker = Faker\Factory::create('fr_FR');
        $rand = rand(0, 3);
        return [
            'civility' => $faker->title('M' | 'Mme'),
            'name' => $faker->lastName,
            'firstname' => $faker->firstName,
            'phone' => $faker->phoneNumber,
            'discover_method_id' => 1,
            'email' => $faker->email,
            'password' => bcrypt('secret'),
            'enable' => true,
            'userable_type' => $this->type[$rand],
            'userable_type_readable' => $this->typeReadable[$rand],
        ];
    }


}