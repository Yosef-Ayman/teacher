import { Head, Link, usePage } from '@inertiajs/react';
import { ArrowLeft, Clock3, FileQuestion, Target, Trophy } from 'lucide-react';
import { AppBreadcrumbs } from '@/components/app-breadcrumbs';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { AssessmentTypeNames } from '@/types/enums';
import type { Course, Assessment } from '@/types/models';

type PageProps = {
    course: Course;
    assessments: Assessment[];
};

export default function AssessmentsIndex() {
    const { course, assessments } = usePage<PageProps>().props;
    const hasAssessments = assessments.length > 0;

    return (
        <>
            <Head title={`${course.title} - Assessments`} />

            <div className="max-w-6xl space-y-6 p-4 md:p-6">
                <AppBreadcrumbs
                    items={[
                        { title: 'All Courses', href: '/courses' },
                        { title: course.title, href: `/course/${course.slug}` },
                        { title: 'Assessments' },
                    ]}
                />

                <div className="flex flex-col gap-2 border-b pb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 className="text-3xl font-bold tracking-tight">
                            {course.title} — Assessments
                        </h1>
                        <p className="text-sm text-muted-foreground">
                            Available exams and quizzes for this course.
                        </p>
                    </div>

                    <div className="flex flex-col items-end justify-center gap-4 sm:flex-row sm:items-center">
                        <Button asChild>
                            <Link href={`/course/${course.slug}/lessons`}>
                                <ArrowLeft className="h-4 w-4" />
                                Back to course lessons
                            </Link>
                        </Button>

                        <Button asChild variant="outline">
                            <Link href={`/course/${course.slug}`}>
                                <ArrowLeft className="h-4 w-4" />
                                Back to course
                            </Link>
                        </Button>
                    </div>
                </div>

                {!hasAssessments ? (
                    <Card className="flex min-h-[240px] flex-col items-center justify-center gap-2 p-8 text-center">
                        <div className="flex h-12 w-12 items-center justify-center rounded-full bg-muted">
                            <FileQuestion className="h-6 w-6 text-muted-foreground" />
                        </div>
                        <h3 className="text-sm font-semibold">
                            No assessments available
                        </h3>
                        <p className="max-w-xs text-xs text-muted-foreground">
                            There are currently no active exams or quizzes for
                            this course.
                        </p>
                    </Card>
                ) : (
                    <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        {assessments.map((assessment) => (
                            <Card
                                key={assessment.id}
                                className="flex flex-col justify-between transition-shadow hover:shadow-md"
                            >
                                <CardContent className="flex flex-1 flex-col gap-4 p-5">
                                    <div>
                                        <div className="mb-3 flex items-center justify-between">
                                            <Badge
                                                variant="secondary"
                                                className="capitalize"
                                            >
                                                {
                                                    AssessmentTypeNames[
                                                        assessment.type
                                                    ]
                                                }
                                            </Badge>

                                            {assessment.duration_minutes >
                                                0 && (
                                                <span className="flex items-center gap-1 text-xs text-muted-foreground">
                                                    <Clock3 className="h-3.5 w-3.5" />
                                                    {
                                                        assessment.duration_minutes
                                                    }{' '}
                                                    min
                                                </span>
                                            )}
                                        </div>

                                        <h2 className="mb-1 text-lg font-semibold">
                                            {assessment.title}
                                        </h2>

                                        {assessment.description && (
                                            <p className="line-clamp-2 text-sm text-muted-foreground">
                                                {assessment.description}
                                            </p>
                                        )}
                                    </div>

                                    <div className="mt-auto space-y-4 border-t pt-4">
                                        <div className="grid grid-cols-2 gap-3 text-xs text-muted-foreground">
                                            <span className="flex items-center gap-1.5">
                                                <FileQuestion className="h-3.5 w-3.5" />
                                                {assessment.questions_count ??
                                                    0}{' '}
                                                questions
                                            </span>
                                            <span className="flex items-center gap-1.5">
                                                <Trophy className="h-3.5 w-3.5" />
                                                {assessment.total_points} pts
                                            </span>
                                            <span className="flex items-center gap-1.5">
                                                <Target className="h-3.5 w-3.5" />
                                                {assessment.passing_score > 0
                                                    ? `${assessment.passing_score} to pass`
                                                    : 'No passing score'}
                                            </span>
                                            <span className="flex items-center gap-1.5">
                                                {assessment.attempts} attempt
                                                {assessment.attempts !== 1 &&
                                                    's'}
                                            </span>
                                        </div>

                                        <Button asChild className="w-full">
                                            <Link
                                                href={`/course/${course.slug}/assessment/${assessment.slug}`}
                                            >
                                                Go to Assessment
                                            </Link>
                                        </Button>
                                    </div>
                                </CardContent>
                            </Card>
                        ))}
                    </div>
                )}
            </div>
        </>
    );
}
