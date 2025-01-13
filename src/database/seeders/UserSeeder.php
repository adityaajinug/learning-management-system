<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        User::create([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'email' => 'teacher1@example.com',
            'username' => 'teacherjohn',
            'password' => Hash::make('password'), // Default password
            'roles' => User::ROLE_TEACHER,
        ]);

        User::create([
            'firstname' => 'Emily',
            'lastname' => 'Clark',
            'email' => 'teacher2@example.com',
            'username' => 'teacheremily',
            'password' => Hash::make('password'), // Default password
            'roles' => User::ROLE_TEACHER,
        ]);

        // Default Students
        User::create([
            'firstname' => 'Michael',
            'lastname' => 'Smith',
            'email' => 'student1@example.com',
            'username' => 'studentmichael',
            'password' => Hash::make('password'), // Default password
            'roles' => User::ROLE_STUDENT,
        ]);

        User::create([
            'firstname' => 'Sarah',
            'lastname' => 'Johnson',
            'email' => 'student2@example.com',
            'username' => 'studentsarah',
            'password' => Hash::make('password'), // Default password
            'roles' => User::ROLE_STUDENT,
        ]);
    }
}
