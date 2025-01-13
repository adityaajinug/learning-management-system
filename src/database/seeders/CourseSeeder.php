<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = User::whereIn('id', [1, 2])->get();

    
        $teacher1 = $teachers->first();
        Course::create([
            'name' => 'PHP for Beginners',
            'description' => 'Learn the basics of PHP programming, ideal for beginners.',
            'price' => 100,
            'image' => 'https://ui-avatars.com/api/?name=PHP+Beginners&background=0D8ABC&color=fff',
            'url' => Str::slug('PHP for Beginners'),
            'quota' => 50,
            'teacher_id' => $teacher1->id,
        ]);

        Course::create([
            'name' => 'Advanced Laravel Development',
            'description' => 'Master Laravel framework and build complex web applications.',
            'price' => 150,
            'image' => 'https://ui-avatars.com/api/?name=Advanced+Laravel&background=0D8ABC&color=fff',
            'url' => Str::slug('Advanced Laravel Development'),
            'quota' => 40,
            'teacher_id' => $teacher1->id,
        ]);

        Course::create([
            'name' => 'Web Development with Vue.js',
            'description' => 'Learn modern web development using Vue.js for frontend and APIs for backend.',
            'price' => 120,
            'image' => 'https://ui-avatars.com/api/?name=Vue+Web+Development&background=0D8ABC&color=fff',
            'url' => Str::slug('Web Development with Vue.js'),
            'quota' => 30,
            'teacher_id' => $teacher1->id,
        ]);

      
        $teacher2 = $teachers->last();
        Course::create([
            'name' => 'Python for Data Science',
            'description' => 'Learn Python and its use in data analysis, data wrangling, and data visualization.',
            'price' => 100,
            'image' => 'https://ui-avatars.com/api/?name=Python+Data+Science&background=0D8ABC&color=fff',
            'url' => Str::slug('Python for Data Science'),
            'quota' => 50,
            'teacher_id' => $teacher2->id,
        ]);

        Course::create([
            'name' => 'Machine Learning Basics',
            'description' => 'An introductory course on machine learning and its algorithms.',
            'price' => 130,
            'image' => 'https://ui-avatars.com/api/?name=Machine+Learning+Basics&background=0D8ABC&color=fff',
            'url' => Str::slug('Machine Learning Basics'),
            'quota' => 40,
            'teacher_id' => $teacher2->id,
        ]);

        Course::create([
            'name' => 'Django for Web Development',
            'description' => 'Learn how to build powerful and scalable web applications using Django.',
            'price' => 140,
            'image' => 'https://ui-avatars.com/api/?name=Django+Web+Development&background=0D8ABC&color=fff',
            'url' => Str::slug('Django for Web Development'),
            'quota' => 35,
            'teacher_id' => $teacher2->id,
        ]);
        
    }
}
