import { Head, Link, router, usePage } from '@inertiajs/react';
import { ArrowLeft, Clock3, FileQuestion, Target, Trophy } from 'lucide-react';
import { AppBreadcrumbs } from '@/components/app-breadcrumbs';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
    AssessmentTypeNames,
    SubmissionStatus,
    SubmissionStatusNames,
} from '@/types/enums';
import type { Course, Assessment, Submission } from '@/types/models';

type PageProps = {
    course: Course;
    assessment: Assessment;
    attempts_used: number;
    last_submission: Submission | null;
};

export default function AssessmentShow() {
    const { course, assessment, attempts_used, last_submission } =
        usePage<PageProps>().props;

    const attemptsLeft = assessment.attempts - attempts_used;
    const canContinue = last_submission?.status === SubmissionStatus.PROGRESS;
    const canViewResult =
        last_submission?.status === SubmissionStatus.SUBMITTED ||
        last_submission?.status === SubmissionStatus.GRADED;
    const canReattempt = attemptsLeft > 0;

    const handleStart = () => {
        router.post(
            `/course/${course.slug}/assessment/${assessment.slug}/submissions`,
        );
    };

    return (
        <>
            <Head title={assessment.title} />

            <div className="max-w-3xl space-y-6 p-4 md:p-6">
                <AppBreadcrumbs
                    items={[
                        { title: 'All Courses', href: '/courses' },
                        { title: course.title, href: `/course/${course.slug}` },
                        { title: 'Lessons', href: `/course/${course.slug}/lessons` },
                        { title: assessment.title },
                    ]}
                />


                <div className="space-y-2">
                    <Badge variant="secondary">
                        {AssessmentTypeNames[assessment.type]}
                    </Badge>

                    <h1 className="text-3xl font-bold tracking-tight">
                        {assessment.title}
                    </h1>

                    {assessment.description && (
                        <p className="text-muted-foreground">
                            {assessment.description}
                        </p>
                    )}
                </div>

                <Card>
                    <CardHeader>
                        <CardTitle>Assessment information</CardTitle>
                    </CardHeader>

                    <CardContent className="grid gap-4 sm:grid-cols-2">
                        <div className="flex items-center gap-3 rounded-lg border p-4">
                            <div className="rounded-md bg-primary/10 p-2">
                                <FileQuestion className="h-5 w-5 text-primary" />
                            </div>
                            <div>
                                <p className="text-sm text-muted-foreground">
                                    Questions
                                </p>
                                <p className="font-semibold">
                                    {assessment.questions_count}
                                </p>
                            </div>
                        </div>

                        <div className="flex items-center gap-3 rounded-lg border p-4">
                            <div className="rounded-md bg-primary/10 p-2">
                                <Trophy className="h-5 w-5 text-primary" />
                            </div>
                            <div>
                                <p className="text-sm text-muted-foreground">
                                    Total points
                                </p>
                                <p className="font-semibold">
                                    {assessment.total_points}
                                </p>
                            </div>
                        </div>

                        <div className="flex items-center gap-3 rounded-lg border p-4">
                            <div className="rounded-md bg-primary/10 p-2">
                                <Clock3 className="h-5 w-5 text-primary" />
                            </div>
                            <div>
                                <p className="text-sm text-muted-foreground">
                                    Duration
                                </p>
                                <p className="font-semibold">
                                    {assessment.duration_minutes} minutes
                                </p>
                            </div>
                        </div>

                        <div className="flex items-center gap-3 rounded-lg border p-4">
                            <div className="rounded-md bg-primary/10 p-2">
                                <Target className="h-5 w-5 text-primary" />
                            </div>
                            <div>
                                <p className="text-sm text-muted-foreground">
                                    Attempts
                                </p>
                                <p className="font-semibold">
                                    {attemptsLeft} / {assessment.attempts} left
                                </p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                {assessment.passing_score > 0 && (
                    <Card className="border-primary/20 bg-primary/5">
                        <CardContent className="p-4 text-sm">
                            You need at least{' '}
                            <span className="font-semibold">
                                {assessment.passing_score} points
                            </span>{' '}
                            to pass this assessment.
                        </CardContent>
                    </Card>
                )}

                {last_submission && (
                    <Card>
                        <CardContent className="flex items-center justify-between p-4">
                            <div>
                                <p className="font-medium">Last attempt</p>
                                <p className="text-sm text-muted-foreground">
                                    {last_submission.status === SubmissionStatus.PROGRESS &&
                                        'You have an unfinished attempt.'}
                                    {(last_submission.status === SubmissionStatus.SUBMITTED ||
                                        last_submission.status === SubmissionStatus.GRADED) &&
                                        `Score: ${last_submission.score}/${last_submission.assessment_score}`}
                                </p>
                            </div>

                            <Badge
                                variant={
                                    last_submission.status === SubmissionStatus.SUBMITTED ||
                                    last_submission.status === SubmissionStatus.GRADED
                                        ? 'default'
                                        : 'secondary'
                                }
                            >
                                {SubmissionStatusNames[last_submission.status]}
                            </Badge>
                        </CardContent>
                    </Card>
                )}

                <div className="flex flex-col gap-3 sm:flex-row">
                    {canContinue ? (
                        <Button asChild size="lg">
                            <Link
                                href={`/submission/${last_submission!.id}`}
                                className="sm:flex-1"
                            >
                                Continue attempt
                            </Link>
                        </Button>
                    ) : canViewResult ? (
                        <>
                            <Button asChild size="lg">
                                <Link
                                    href={`/submission/${last_submission!.id}/result`}
                                >
                                    View result
                                </Link>
                            </Button>

                            {canReattempt && (
                                <Button
                                    onClick={handleStart}
                                    size="lg"
                                    variant="secondary"
                                    className="cursor-pointer sm:flex-1"
                                >
                                    Reattempt
                                </Button>
                            )}
                        </>
                    ) : (
                        <Button
                            onClick={handleStart}
                            size="lg"
                            className="sm:flex-1"
                            disabled={!canReattempt}
                        >
                            {canReattempt
                                ? 'Start assessment'
                                : 'No attempts left'}
                        </Button>
                    )}

                    <Button asChild variant="outline" size="lg">
                        <Link href={`/course/${course.slug}`}>
                            Back to course
                        </Link>
                    </Button>
                </div>

                <Link
                    href={`/course/${course.slug}/assessment/${assessment.slug}/submissions`}
                    className="inline-block cursor-pointer text-sm text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                >
                    View your assessment submissions →
                </Link>
            </div>
        </>
    );
}
