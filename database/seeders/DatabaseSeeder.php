<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Employee;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
//     public function run(): void
//     {
//         \App\Models\User::factory(10)->create();

//         \App\Models\User::factory()->create([
//             'name' => 'priss',
//             'email' => 'test@example.com',
//             'password' => bcrypt('12345'),
//         ]);
//    }


public function run()
{
    // use App\Models\Employee;

    Employee::create([
        'name' => 'Joyce',
        'email' => 'joyc@gmail.com',
        'password' => bcrypt('123456'),
    ]);

}


}
