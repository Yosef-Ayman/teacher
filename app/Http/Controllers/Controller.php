<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

abstract class Controller
{
    public static function isEnrolled($user_id, $course_id): bool
    {
        $enrolled = DB::table('enrolled_courses')
            ->where('user_id', $user_id)
            ->where('course_id', $course_id)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            })
            ->exists();

        if ($enrolled) {
            return true;
        }

        return false;
    }
}
