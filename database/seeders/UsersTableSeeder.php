<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
//use Database\Factories\UserFactory;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::factory()->count(50)->create();
    }
}
