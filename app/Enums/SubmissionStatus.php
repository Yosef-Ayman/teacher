<?php

namespace App\Enums;

enum SubmissionStatus: string
{
    case PROGRESS = 'in_progress';
    case SUBMITTED = 'submitted';
    case GRADED = 'graded';
}
