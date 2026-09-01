<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MySubmissionController extends Controller
{
    function index(Request $request)
    {
        $student = $request->user()->student;
        $submissions = $student->submissions()
            ->with('assessment.course')
            ->latest()
            ->paginate(9);

        return Inertia::render('my-submissions/index', compact('submissions'));
        // return response()->json(compact('submissions'));
    }
}
