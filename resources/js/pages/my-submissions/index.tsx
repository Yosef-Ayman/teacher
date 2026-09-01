import { Head, Link, usePage } from '@inertiajs/react';
import { Calendar, Clock, History } from 'lucide-react';
import Pagination from '@/components/pagination';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { parseUtcDate } from '@/lib/date';
import { AssessmentTypeNames, SubmissionStatus } from '@/types/enums';
import type { Submission } from '@/types/models';
import type { Paginated } from '@/types/pagination';

type PageProps = {
    submissions: Paginated<Submission>;
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

export default function MySubmissionsIndex() {
    const { submissions } = usePage<PageProps>().props;

    return (
        <>
            <Head title="My Submissions" />

            <div className="flex flex-col gap-6 p-4 md:p-6">
                <div>
                    <h1 className="text-2xl font-bold tracking-tight">
                        Attempt History
                    </h1>
                    <p className="text-sm text-muted-foreground">
                        All your assessment attempts across courses.
                    </p>
                </div>

                <div className="space-y-4">
                    {submissions.data.length === 0 ? (
                        <Card>
                            <CardContent className="flex flex-col items-center justify-center p-8 text-center">
                                <History className="mb-3 h-12 w-12 text-muted-foreground/50" />
                                <p className="font-medium">No attempts found</p>
                                <p className="text-sm text-muted-foreground">
                                    You haven't taken any assessments yet.
                                </p>
                            </CardContent>
                        </Card>
                    ) : (
                        submissions.data.map((sub) => (
                            <Card
                                key={sub.id}
                                className="transition-all hover:border-primary/50"
                            >
                                <CardContent className="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between">
                                    <div className="space-y-1">
                                        <Badge variant="secondary">
                                            {
                                                AssessmentTypeNames[
                                                    sub.assessment.type
                                                ]
                                            }
                                        </Badge>
                                        <div className="flex items-center gap-2">
                                            <span className="font-semibold">
                                                {sub.assessment.title}
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
                                                    {sub.submitted_at &&
                                                        parseUtcDate(
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

                <Pagination links={submissions.links} />
            </div>
        </>
    );
}
