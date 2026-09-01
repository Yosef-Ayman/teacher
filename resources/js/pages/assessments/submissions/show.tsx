import { Head, router, usePage } from '@inertiajs/react';
import { Check, ChevronLeft, ChevronRight } from 'lucide-react';
import { useEffect, useRef, useState } from 'react';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { parseUtcDate } from '@/lib/date';
import type { Question, Submission } from '@/types/models';

type PageProps = {
    submission: Submission;
    deadline: string;
};

interface AnswerPayload {
    question_id: number;
    option_id: number | null;
    answer_text: string | null;
}

interface SavedProgress {
    answers: Record<number, AnswerPayload>;
    currentIndex: number;
    questions: Question[];
}

const shuffleArray = <T,>(array: T[]): T[] => {
    const shuffled = [...array];

    for (let i = shuffled.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [shuffled[i], shuffled[j]] = [shuffled[j], shuffled[i]];
    }

    return shuffled;
};

export default function SubmissionShow() {
    const { submission, deadline } = usePage<PageProps>().props;
    const assessment = submission.assessment;

    const storageKey = `assessment-progress-${submission.id}`;

    const getSavedProgress = (): SavedProgress | null => {
        try {
            const saved = localStorage.getItem(storageKey);

            if (!saved) {
                return null;
            }

            return JSON.parse(saved);
        } catch {
            return null;
        }
    };

    const savedProgress = getSavedProgress();

    const [questions] = useState<Question[]>(() => {
        if (savedProgress?.questions?.length) {
            return savedProgress.questions;
        }

        let initial = [...(assessment.questions ?? [])];

        if (assessment.shuffle_questions) {
            initial = shuffleArray(initial);
        }

        if (assessment.shuffle_answers) {
            initial = initial.map((q) => ({
                ...q,
                options: shuffleArray(q.options),
            }));
        }

        return initial;
    });

    const [answers, setAnswers] = useState<Record<number, AnswerPayload>>(
        () => {
            if (savedProgress?.answers) {
                return savedProgress.answers;
            }

            const initial: Record<number, AnswerPayload> = {};
            submission.answers?.forEach((a) => {
                initial[a.question_id] = {
                    question_id: a.question_id,
                    option_id: a.option_id ?? null,
                    answer_text: a.answer_text ?? null,
                };
            });

            return initial;
        },
    );

    const [currentIndex, setCurrentIndex] = useState(() => {
        if (savedProgress) {
            return Math.min(
                Math.max(savedProgress.currentIndex, 0),
                Math.max(questions.length - 1, 0),
            );
        }

        return 0;
    });

    const [isSubmitting, setIsSubmitting] = useState(false);
    const currentQuestion = questions[currentIndex];

    useEffect(() => {
        const preventCopyPaste = (event: ClipboardEvent) =>
            event.preventDefault();
        document.addEventListener('copy', preventCopyPaste);
        document.addEventListener('cut', preventCopyPaste);
        document.addEventListener('paste', preventCopyPaste);

        return () => {
            document.removeEventListener('copy', preventCopyPaste);
            document.removeEventListener('cut', preventCopyPaste);
            document.removeEventListener('paste', preventCopyPaste);
        };
    }, []);

    useEffect(() => {
        try {
            const progress: SavedProgress = {
                answers,
                currentIndex,
                questions,
            };
            localStorage.setItem(storageKey, JSON.stringify(progress));
        } catch {
            // ignore
        }
    }, [answers, currentIndex, questions, storageKey]);

    const handleOptionSelect = (optionId: number) => {
        setAnswers((prev) => ({
            ...prev,
            [currentQuestion.id]: {
                question_id: currentQuestion.id,
                option_id: optionId,
                answer_text: null,
            },
        }));
    };

    const handleTextChange = (text: string) => {
        setAnswers((prev) => ({
            ...prev,
            [currentQuestion.id]: {
                question_id: currentQuestion.id,
                option_id: null,
                answer_text: text,
            },
        }));
    };

    const goToQuestion = (index: number) => {
        if (isSubmitting || index < 0 || index >= questions.length) {
            return;
        }

        setCurrentIndex(index);
    };

    const handleSubmit = () => {
        if (isSubmitting) {
            return;
        }

        setIsSubmitting(true);

        const payload = Object.values(answers);

        router.post(
            `/submission/${submission.id}/submit`,
            {
                answers: payload,
            },
            {
                onSuccess: () => {
                    try {
                        localStorage.removeItem(storageKey);
                    } catch {
                        // Ignore LocalStorage errors.
                    }
                },

                onFinish: () => {
                    setIsSubmitting(false);
                },
            },
        );
    };

    const hasAutoSubmittedRef = useRef(false);
    const handleSubmitRef = useRef(handleSubmit);
    const SUBMIT_BUFFER_MS = 2000;

    useEffect(() => {
        handleSubmitRef.current = handleSubmit;
    }, [handleSubmit]);

    useEffect(() => {
        if (!deadline || hasAutoSubmittedRef.current) {
            return;
        }

        const deadlineMs = parseUtcDate(deadline).getTime();
        const msRemaining = deadlineMs - Date.now() - SUBMIT_BUFFER_MS;

        if (msRemaining <= 0) {
            hasAutoSubmittedRef.current = true;
            handleSubmitRef.current();

            return;
        }

        const timeoutId = setTimeout(() => {
            hasAutoSubmittedRef.current = true;
            handleSubmitRef.current();
        }, msRemaining);

        return () => clearTimeout(timeoutId);
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [deadline]);

    const answeredCount = Object.keys(answers).length;
    const progressPercentage =
        questions.length > 0 ? (answeredCount / questions.length) * 100 : 0;

    if (!currentQuestion) {
        return (
            <>
                <Head title={assessment.title} />
                <div className="p-6 text-center text-sm text-muted-foreground">
                    No questions found for this assessment.
                </div>
            </>
        );
    }

    return (
        <>
            <Head title={`Assessment - ${assessment.title}`} />

            <div className="mx-auto max-w-4xl space-y-6 p-4 md:p-6">
                <div className="flex flex-col gap-4 border-b pb-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 className="text-2xl font-bold">
                            {assessment.title}
                        </h1>
                        <p className="text-sm text-muted-foreground">
                            Question {currentIndex + 1} of {questions.length}
                        </p>
                    </div>

                    <div className="flex flex-col items-center gap-2 rounded-lg border bg-card px-4 py-2 font-mono text-lg font-bold">
                        <div>
                            Started:{' '}
                            {parseUtcDate(
                                submission.started_at,
                            ).toLocaleString()}
                        </div>
                        <div>
                            Deadline: {parseUtcDate(deadline).toLocaleString()}
                        </div>
                    </div>
                </div>

                <div className="h-2 w-full overflow-hidden rounded-full bg-muted">
                    <div
                        className="h-full rounded-full bg-primary transition-all"
                        style={{ width: `${progressPercentage}%` }}
                    />
                </div>

                <div className="grid gap-6 md:grid-cols-4">
                    <div className="space-y-6 md:col-span-3">
                        <Card>
                            <CardHeader className="flex flex-row items-start justify-between space-y-0">
                                <CardTitle className="text-lg leading-relaxed font-medium">
                                    {currentQuestion.title}
                                </CardTitle>
                                <span className="rounded-full bg-muted px-2.5 py-1 text-xs whitespace-nowrap text-muted-foreground">
                                    {currentQuestion.points} pts
                                </span>
                            </CardHeader>

                            <CardContent className="space-y-4">
                                {currentQuestion.options.length > 0 ? (
                                    <div className="space-y-3">
                                        {currentQuestion.options.map(
                                            (option) => {
                                                const selected =
                                                    answers[currentQuestion.id]
                                                        ?.option_id ===
                                                    option.id;

                                                return (
                                                    <label
                                                        key={option.id}
                                                        className={`flex cursor-pointer items-center gap-3 rounded-lg border p-4 transition-colors hover:bg-accent ${
                                                            selected
                                                                ? 'border-primary bg-primary/5'
                                                                : ''
                                                        }`}
                                                    >
                                                        <input
                                                            type="radio"
                                                            name={`question-${currentQuestion.id}`}
                                                            value={option.id}
                                                            checked={selected}
                                                            onChange={() =>
                                                                handleOptionSelect(
                                                                    option.id,
                                                                )
                                                            }
                                                            className="h-4 w-4"
                                                        />
                                                        <span className="flex-1">
                                                            {option.title}
                                                        </span>
                                                    </label>
                                                );
                                            },
                                        )}
                                    </div>
                                ) : (
                                    <textarea
                                        placeholder="Type your answer here..."
                                        rows={5}
                                        value={
                                            answers[currentQuestion.id]
                                                ?.answer_text ?? ''
                                        }
                                        onChange={(e) =>
                                            handleTextChange(e.target.value)
                                        }
                                        className="w-full resize-y rounded-lg border bg-background p-3 transition outline-none focus:ring-2 focus:ring-primary"
                                    />
                                )}
                            </CardContent>
                        </Card>

                        <div className="flex items-center justify-between">
                            <Button
                                variant="outline"
                                onClick={() => goToQuestion(currentIndex - 1)}
                                disabled={currentIndex === 0 || isSubmitting}
                            >
                                <ChevronLeft className="mr-2 h-4 w-4" />
                                Previous
                            </Button>

                            {currentIndex < questions.length - 1 ? (
                                <Button
                                    onClick={() =>
                                        goToQuestion(currentIndex + 1)
                                    }
                                    disabled={isSubmitting}
                                >
                                    Next
                                    <ChevronRight className="ml-2 h-4 w-4" />
                                </Button>
                            ) : (
                                <Button
                                    onClick={handleSubmit}
                                    disabled={isSubmitting}
                                    className="cursor-pointer bg-emerald-600 hover:bg-emerald-700"
                                >
                                    <Check className="mr-2 h-4 w-4" />
                                    {isSubmitting
                                        ? 'Submitting...'
                                        : 'Finish & Submit'}
                                </Button>
                            )}
                        </div>
                    </div>

                    <div className="space-y-4">
                        <Card>
                            <CardHeader className="p-4">
                                <CardTitle className="text-sm">
                                    Question Navigation
                                </CardTitle>
                            </CardHeader>

                            <CardContent className="grid grid-cols-4 gap-2 p-4 pt-0">
                                {questions.map((question, index) => {
                                    const answer = answers[question.id];
                                    const isAnswered =
                                        answer?.option_id != null ||
                                        !!answer?.answer_text;
                                    const isCurrent = index === currentIndex;

                                    return (
                                        <button
                                            key={question.id}
                                            type="button"
                                            onClick={() => goToQuestion(index)}
                                            disabled={isSubmitting}
                                            className={`h-9 w-9 rounded-md border text-xs font-semibold transition-all ${
                                                isCurrent
                                                    ? 'border-primary ring-2 ring-primary'
                                                    : isAnswered
                                                      ? 'border-primary bg-primary text-primary-foreground'
                                                      : 'bg-background hover:bg-muted'
                                            }`}
                                        >
                                            {index + 1}
                                        </button>
                                    );
                                })}
                            </CardContent>
                        </Card>
                    </div>
                </div>
            </div>
        </>
    );
}
