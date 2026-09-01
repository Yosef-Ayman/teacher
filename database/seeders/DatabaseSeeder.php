<?php

namespace Database\Seeders;

use App\Enums\QuestionType;
use App\Models\Admin;
use App\Models\Assessment;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Markdown;
use App\Models\Option;
use App\Models\Question;
use App\Models\Student;
use App\Models\Super;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Yosef Ayman',
            'email' => 'yosefaymanahmedya@gmail.com',
            'is_blocked' => false,
            'username' => 'yosefayman',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);
        Student::create([
            'user_id' => 1,
            'balance' => 1000000,
            'created_by' => null,
        ]);
        Admin::create([
            'user_id' => 1,
            'created_by' => null,
        ]);
        Super::create([
            'user_id' => 1,
            'created_by' => null,
        ]);
    }
}
