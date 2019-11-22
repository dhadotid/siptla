<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Administrator',
            'nip' => 123,
            'email' => 'admin@email.com',
            'password' => bcrypt('123'),
            'flag' => 1,
            'telepon' => '-',
            'level' => 0
        ]);
    }
}
