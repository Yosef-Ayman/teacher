import type {
    AssessmentType,
    ChapterContentType,
    QuestionType,
    SubmissionStatus,
    VideoProvider,
} from '@/types/enums';

export interface Course {
    id: number;
    title: string;
    slug: string;
    description: string | null;
    price: number;
    access_duration_days: number | null;
    thumbnail_url: string | null;
    publish_at: string;
    created_by: number | null;
    created_at: string;
    updated_at: string;
    lessons?: Lesson[];
    assessments?: Assessment[];
}

export interface Lesson {
    id: number;
    course_id: number;
    title: string;
    slug: string;
    position: number;
    hidden: boolean;
    created_by: number | null;
    created_at: string;
    updated_at: string;
    chapters?: ChapterSummary[];
}

export interface ChapterSummary {
    id: number;
    lesson_id: number;
    position: number;
    type: ChapterContentType;
    title: string;
}

export interface Video {
    id: number;
    title: string;
    provider: VideoProvider;
    external_id: string;
    hidden: boolean;
    embed_url: string;
    created_by: number | null;
    created_at: string;
    updated_at: string;
}

export interface Markdown {
    id: number;
    title: string;
    body: string | null;
    hidden: boolean;
    created_by: number | null;
    created_at: string;
    updated_at: string;
}

export interface Assessment {
    id: number;
    course_id: number;
    lesson_id: number | null;
    title: string;
    description: string | null;
    slug: string;
    type: AssessmentType;
    hidden: boolean;
    duration_minutes: number;
    total_points: number;
    attempts: number;
    passing_score: number;
    shuffle_questions: boolean;
    shuffle_answers: boolean;
    show_result: boolean;
    show_correct_answers: boolean;
    starts_at: string | null;
    ends_at: string | null;
    created_at: string;
    updated_at: string;
    course?: Course;
    lesson?: Lesson | null;
    questions_count?: number;
    questions?: Question[];
}

export interface Question {
    id: number;
    assessment_id: number;
    title: string;
    type: QuestionType;
    points: number;
    position: number;
    created_at: string;
    updated_at: string;
    options: Option[];
}

export interface Option {
    id: number;
    question_id: number;
    title: string;
    is_correct?: boolean;
    position: number;
    created_at?: string;
    updated_at?: string;
}

export interface Chapter {
    id: number;
    lesson_id: number;
    video_id: number | null;
    markdown_id: number | null;
    assessment_id: number | null;
    position: number;
    created_by: number | null;
    created_at: string;
    updated_at: string;
    video: Video | null;
    markdown: Markdown | null;
    assessment: Assessment | null;
}

export interface Answer {
    id: number;
    submission_id: string;
    question_id: number;
    question_type: Question['type'];
    option_id: number | null;
    answer_text: string | null;
    is_correct: boolean | null;
    earned_points: number | null;
    question_points: number;
    created_at: string;
    updated_at: string;
    question?: Question;
    option?: Option | null;
}

export interface Submission {
    id: string;
    assessment_id: number;
    student_id: number;
    assessment_score: number;
    score: number;
    status: SubmissionStatus;
    started_at: string;
    submitted_at: string | null;
    created_at: string;
    updated_at: string;
    assessment: Assessment;
    answers: Answer[];
}

export interface Enrollment {
    id: number;
    user_id: number;
    course_id: number;
    expires_at: string | null;
    created_by: number | null;
    created_at: string;
    updated_at: string;
    course?: Course;
}
