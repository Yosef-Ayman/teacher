import { Head, Link, usePage } from '@inertiajs/react';
import { ArrowLeft, Calendar, Clock, History } from 'lucide-react';
import { AppBreadcrumbs } from '@/components/app-breadcrumbs';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { parseUtcDate } from '@/lib/date';
import { SubmissionStatus } from '@/types/enums';
import type { Course, Assessment, Submission } from '@/types/models';

type PageProps = {
    course: Course;
    assessment: Assessment;
    submissions: Submission[];
};

function getStatusBadge(status: Submission['status']) {
    if (status === SubmissionStatus.GRADED){
        return <Badge variant="default">Graded</Badge>;
    }

    if (status === SubmissionStatus.SUBMITTED){
        return <Badge variant="default">Submitted</Badge>;
    }

    if (status === SubmissionStatus.PROGRESS){
        return <Badge variant="secondary">In Progress</Badge>;
    }

    return <Badge variant="outline">{status}</Badge>;
}

export default function SubmissionsIndex() {
    const { course, assessment, submissions } = usePage<PageProps>().props;

    return (
        <>
            <Head title={`Submissions - ${assessment.title}`} />

            <div className="max-w-4xl space-y-6 p-4 md:p-6">
                <AppBreadcrumbs
                    items={[
                        { title: 'All Courses', href: '/courses' },
                        { title: course.title, href: `/course/${course.slug}` },
                        {
                            title: assessment.title,
                            href: `/course/${course.slug}/assessment/${assessment.slug}`,
                        },
                        { title: 'Submissions' },
                    ]}
                />

                <div className="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 className="text-3xl font-bold tracking-tight">
                            Attempt History
                        </h1>
                        <p className="text-muted-foreground">
                            {assessment.title}
                        </p>
                    </div>

                    <Button asChild variant="outline">
                        <Link
                            href={`/course/${course.slug}/assessment/${assessment.slug}`}
                        >
                            <ArrowLeft className="h-4 w-4" />
                            Back to Assessment
                        </Link>
                    </Button>
                </div>

                <div className="space-y-4">
                    {submissions.length === 0 ? (
                        <Card>
                            <CardContent className="flex flex-col items-center justify-center p-8 text-center">
                                <History className="mb-3 h-12 w-12 text-muted-foreground/50" />
                                <p className="font-medium">No attempts found</p>
                                <p className="text-sm text-muted-foreground">
                                    You haven't taken this assessment yet.
                                </p>
                            </CardContent>
                        </Card>
                    ) : (
                        submissions.map((sub, index) => (
                            <Card
                                key={sub.id}
                                className="transition-all hover:border-primary/50"
                            >
                                <CardContent className="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between">
                                    <div className="space-y-1">
                                        <div className="flex items-center gap-2">
                                            <span className="font-semibold">
                                                Attempt #{index + 1}
                                            </span>
                                            {getStatusBadge(sub.status)}
                                        </div>
                                        <div className="flex flex-wrap items-center gap-4 text-xs text-muted-foreground">
                                            <span className="flex items-center gap-1">
                                                <Calendar className="h-3.5 w-3.5" />
                                                Started:{' '}
                                                {parseUtcDate(
                                                    sub.started_at,
                                                ).toLocaleString()}
                                            </span>
                                            {sub.submitted_at && (
                                                <span className="flex items-center gap-1">
                                                    <Clock className="h-3.5 w-3.5" />
                                                    Finished:{' '}
                                                    {parseUtcDate(
                                                        sub.submitted_at,
                                                    ).toLocaleString()}
                                                </span>
                                            )}
                                        </div>
                                    </div>

                                    <div className="flex items-center justify-between gap-4 sm:justify-end">
                                        {sub.status !==
                                            SubmissionStatus.PROGRESS && (
                                            <div className="text-right">
                                                <p className="text-xs text-muted-foreground">
                                                    Score
                                                </p>
                                                <p className="text-lg font-bold">
                                                    {sub.score} /{' '}
                                                    {sub.assessment_score}
                                                </p>
                                            </div>
                                        )}

                                        <Button
                                            asChild
                                            className="cursor-pointer"
                                            size="sm"
                                            variant={
                                                sub.status ===
                                                SubmissionStatus.PROGRESS
                                                    ? 'default'
                                                    : 'outline'
                                            }
                                        >
                                            <Link
                                                href={
                                                    sub.status ===
                                                    SubmissionStatus.PROGRESS
                                                        ? `/submission/${sub.id}`
                                                        : `/submission/${sub.id}/result`
                                                }
                                            >
                                                {sub.status ===
                                                SubmissionStatus.PROGRESS
                                                    ? 'Continue'
                                                    : 'View Result'}
                                            </Link>
                                        </Button>
                                    </div>
                                </CardContent>
                            </Card>
                        ))
                    )}
                </div>
            </div>
        </>
    );
}
