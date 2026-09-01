import { Head, Link, usePage } from '@inertiajs/react';
import {
    AlertCircle,
    ArrowLeft,
    CheckCircle2,
    XCircle,
} from 'lucide-react';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import type { Submission } from '@/types/models';

type PageProps = {
    payload: {
        submission: Submission;
        calculated_score: number;
        total_points: number;
        passing_score: number;
        passed: boolean;
        pending_manual_grading: boolean;
    };
};

export default function SubmissionResult() {
    const { payload } = usePage<PageProps>().props;
    const {
        submission,
        calculated_score,
        total_points,
        passing_score,
        passed,
        pending_manual_grading,
    } = payload;

    const answers = submission.answers;

    return (
        <>
            <Head title="Assessment Results" />

            <div className="mx-auto max-w-3xl space-y-6 p-4 md:p-6">

                {submission.assessment.course && (
                    <div className="mb-4">
                        <Button className="cursor-pointer" asChild>
                            <Link
                                href={`/course/${submission.assessment.course.slug}/assessment/${submission.assessment.slug}`}
                            >
                                <ArrowLeft className="h-4 w-4" />
                                Back to {submission.assessment.title}
                            </Link>
                        </Button>
                    </div>
                )}

                <Card
                    className={`border-2 ${
                        passed
                            ? 'border-emerald-500/20 bg-emerald-500/5'
                            : 'border-destructive/20 bg-destructive/5'
                    }`}
                >
                    <CardContent className="flex flex-col items-center space-y-3 p-6 text-center">
                        {passed ? (
                            <CheckCircle2 className="h-16 w-16 text-emerald-500" />
                        ) : (
                            <XCircle className="h-16 w-16 text-destructive" />
                        )}

                        <div>
                            <h1 className="text-2xl font-bold">
                                {passed
                                    ? 'Congratulations! You Passed'
                                    : 'Assessment Failed'}
                            </h1>
                            {pending_manual_grading && (
                                <p className="mt-1 flex items-center justify-center gap-1 text-sm text-amber-600 dark:text-amber-400">
                                    <AlertCircle className="h-4 w-4" /> Some
                                    questions require manual grading.
                                </p>
                            )}
                        </div>

                        <div className="flex items-center gap-6 pt-2">
                            <div>
                                <p className="text-xs text-muted-foreground">
                                    Your Score
                                </p>
                                <p className="text-3xl font-extrabold">
                                    {calculated_score}{' '}
                                    <span className="text-base font-normal text-muted-foreground">
                                        / {total_points}
                                    </span>
                                </p>
                            </div>
                            {passing_score > 0 && (
                                <div className="border-l pl-6">
                                    <p className="text-xs text-muted-foreground">
                                        Passing Score
                                    </p>
                                    <p className="text-2xl font-bold text-muted-foreground">
                                        {passing_score} pts
                                    </p>
                                </div>
                            )}
                        </div>
                    </CardContent>
                </Card>

                {answers.length > 0 && (
                    <div className="space-y-4">
                        <h2 className="text-xl font-bold">Question Review</h2>

                        {answers.map((answer, idx) => {
                            const question = answer.question;

                            if (!question) {
                                return null;
                            }

                            const isCorrect = answer.is_correct;

                            return (
                                <Card
                                    key={answer.id}
                                    className="overflow-hidden"
                                >
                                    <CardHeader className="flex flex-row items-center justify-between bg-muted/40 p-4">
                                        <CardTitle className="text-sm font-medium">
                                            {idx + 1}. {question.title}
                                        </CardTitle>

                                        <Badge
                                            variant={
                                                isCorrect
                                                    ? 'default'
                                                    : isCorrect === false
                                                      ? 'destructive'
                                                      : 'secondary'
                                            }
                                        >
                                            {answer.earned_points ?? 0} /{' '}
                                            {answer.question_points} pts
                                        </Badge>
                                    </CardHeader>

                                    <CardContent className="space-y-3 p-4">
                                        {question.options.length > 0 ? (
                                            <div className="space-y-2">
                                                {question.options.map(
                                                    (option) => {
                                                        const isSelected =
                                                            answer.option_id ===
                                                            option.id;

                                                        const isOptionCorrect =
                                                            option.is_correct;

                                                        let itemStyle =
                                                            'border-muted bg-background';

                                                        if (
                                                            isSelected &&
                                                            isOptionCorrect
                                                        ) {
                                                            itemStyle =
                                                                'border-emerald-500 bg-emerald-500/10';
                                                        } else if (
                                                            isSelected &&
                                                            !isOptionCorrect
                                                        ) {
                                                            itemStyle =
                                                                'border-destructive bg-destructive/10';
                                                        } else if (
                                                            isOptionCorrect
                                                        ) {
                                                            itemStyle =
                                                                'border-emerald-500/50 bg-emerald-500/5';
                                                        }

                                                        return (
                                                            <div
                                                                key={option.id}
                                                                className={`flex items-center justify-between rounded-md border p-3 text-sm ${itemStyle}`}
                                                            >
                                                                <span>
                                                                    {
                                                                        option.title
                                                                    }
                                                                </span>

                                                                {isSelected && (
                                                                    <Badge variant="outline">
                                                                        Your
                                                                        Answer
                                                                    </Badge>
                                                                )}
                                                            </div>
                                                        );
                                                    },
                                                )}
                                            </div>
                                        ) : (
                                            <div className="space-y-2 text-sm">
                                                <p className="text-xs text-muted-foreground">
                                                    Your Written Answer:
                                                </p>

                                                <div className="rounded-md border bg-muted/20 p-3">
                                                    {answer.answer_text || (
                                                        <span className="text-muted-foreground italic">
                                                            No answer provided
                                                        </span>
                                                    )}
                                                </div>
                                            </div>
                                        )}
                                    </CardContent>
                                </Card>
                            );
                        })}
                    </div>
                )}

                <Button
                    asChild
                    variant="outline"
                    size="lg"
                    className="w-full cursor-pointer"
                >
                    <Link href="/my/submissions">
                        <ArrowLeft className="mr-2 h-4 w-4" /> Return to
                        Submissions
                    </Link>
                </Button>
            </div>
        </>
    );
}
