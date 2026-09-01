<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class MyCourseController extends Controller
{
    function index(Request $request)
    {
        $courses = $request->user()->enrollments()
            ->with('course')
            ->latest()
            ->paginate(9);

        return Inertia::render('my-courses/index', compact('courses'));
        // return response()->json(compact('courses'));
    }
}
