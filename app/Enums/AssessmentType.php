<?php

namespace App\Enums;

enum AssessmentType: string
{
    case QUIZ = 'quiz';
    case HOMEWORK = 'homework';
    case SHORT_QUIZ = 'short_quiz';
    case EXAM = 'exam';
}
