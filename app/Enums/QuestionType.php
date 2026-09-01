<?php

namespace App\Enums;

enum QuestionType: string
{
    case MCQ = 'mcq';
    case MULTIPLE = 'multiple_select';
    case TRUE_FALSE = 'true_false';
    case SHORT_ANSWER = 'short_answer';
}
