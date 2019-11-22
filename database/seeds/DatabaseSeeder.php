<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Models\MasterDinas;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        // $this->call(KelompokTemuanTableSeeder::class);
        //$this->call(RekomendasiTableSeeder::class);

        // User::create([
        //     'name' => 'Administrator',
        //     'nip' => 123,
        //     'email' => 'admin@email.com',
        //     'password' => bcrypt('123'),
        //     'flag' => 1,
        //     'level' => 1
        // ]);

    }
}
