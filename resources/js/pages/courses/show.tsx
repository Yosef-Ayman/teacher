import { Head, Link, router, usePage } from '@inertiajs/react';
import { AppBreadcrumbs } from '@/components/app-breadcrumbs';
import AssessmentsList from '@/components/assessments-list';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { parseUtcDate } from '@/lib/date';
import { ChapterContentTypeNames } from '@/types/enums';
import type { Course, Enrollment } from '@/types/models';

type PageProps = {
    course: Course;
    enrollment: Enrollment | null;
};

export default function CourseShow() {
    const { course, enrollment } = usePage<PageProps>().props;

    const lessons = course.lessons
        ? [...course.lessons].sort((a, b) => a.position - b.position)
        : [];
    const visibleLessons = lessons.filter((l) => !l.hidden);
    const totalChapters = lessons.reduce(
        (sum, l) => sum + (l.chapters?.length ?? 0),
        0,
    );
    const firstLesson = visibleLessons[0];
    const hasLessons = lessons.length > 0;

    const isEnrolled = !!enrollment;
    const isExpired =
        !!enrollment?.expires_at &&
        parseUtcDate(enrollment.expires_at) < new Date();

    const handleEnroll = () => {
        router.post(`/course/buy/${course.slug}`);
    };

    return (
        <>
            <Head title={course.title} />

            <div className="flex flex-col gap-6 p-4 md:p-6">
                <div className="grid gap-6 lg:grid-cols-3">
                    <div className="flex flex-col gap-6 lg:col-span-2">
                        <div className="lg:col-span-3">
                            <AppBreadcrumbs
                                items={[
                                    { title: 'All Courses', href: '/courses' },
                                    {
                                        title: course.title,
                                        href: `/course/${course.slug}`,
                                    },
                                ]}
                            />
                        </div>

                        <div>
                            <div className="mb-2 flex items-center gap-2">
                                <Badge
                                    variant={
                                        isEnrolled ? 'secondary' : 'default'
                                    }
                                >
                                    {isEnrolled
                                        ? isExpired
                                            ? 'Expired'
                                            : 'Enrolled'
                                        : course.price === 0
                                          ? 'Free'
                                          : `$ ${course.price}`}
                                </Badge>
                            </div>
                            <h1 className="text-2xl font-bold tracking-tight">
                                {course.title}
                            </h1>
                            {course.description && (
                                <p className="mt-2 text-sm text-muted-foreground">
                                    {course.description}
                                </p>
                            )}
                        </div>

                        <Card className="overflow-hidden">
                            <div className="relative aspect-video w-full overflow-hidden bg-muted">
                                {course.thumbnail_url ? (
                                    <img
                                        src={course.thumbnail_url}
                                        alt={course.title}
                                        className="h-full w-full object-cover"
                                    />
                                ) : (
                                    <div
                                        className="flex h-full items-center justify-center text-muted-foreground"
                                        style={{
                                            background:
                                                'repeating-linear-gradient(45deg, rgba(244, 244, 241, 0.06) 0 1px, transparent 1px 14px), linear-gradient(135deg, #14140f, #0C0C09)',
                                        }}
                                    >
                                        <span>No Cover</span>
                                    </div>
                                )}
                            </div>
                        </Card>

                        <div>
                            <div className="mb-3 flex items-center justify-between">
                                <h2 className="text-lg font-semibold">
                                    Syllabus
                                </h2>
                                {hasLessons && (
                                    <span className="text-xs text-muted-foreground">
                                        {lessons.length} lessons ·{' '}
                                        {totalChapters} chapters
                                    </span>
                                )}
                            </div>

                            {hasLessons ? (
                                <Card>
                                    <CardContent className="divide-y p-0">
                                        {lessons.map((lesson, index) => (
                                            <div
                                                key={lesson.id}
                                                className="flex items-start gap-4 p-4"
                                            >
                                                <span className="mt-0.5 w-6 flex-shrink-0 font-mono text-xs font-semibold text-muted-foreground">
                                                    {String(index + 1).padStart(
                                                        2,
                                                        '0',
                                                    )}
                                                </span>

                                                <div className="flex-1">
                                                    <div className="flex flex-wrap items-center gap-2">
                                                        <h3 className="text-sm font-semibold">
                                                            {lesson.title}
                                                        </h3>
                                                        {lesson.hidden && (
                                                            <Badge variant="outline">
                                                                Hidden
                                                            </Badge>
                                                        )}
                                                    </div>

                                                    <div className="mt-2 flex flex-col gap-1.5">
                                                        {(lesson.chapters ?? [])
                                                            .slice()
                                                            .sort(
                                                                (a, b) =>
                                                                    a.position -
                                                                    b.position,
                                                            )
                                                            .map(
                                                                (
                                                                    chapter,
                                                                    ci,
                                                                ) => (
                                                                    <div
                                                                        key={
                                                                            chapter.id
                                                                        }
                                                                        className="flex items-center gap-2 text-xs text-muted-foreground"
                                                                    >
                                                                        <span className="font-mono">
                                                                            {String(
                                                                                ci +
                                                                                    1,
                                                                            ).padStart(
                                                                                2,
                                                                                '0',
                                                                            )}
                                                                        </span>
                                                                        <span>
                                                                            {ChapterContentTypeNames[chapter.type]}
                                                                        </span>
                                                                    </div>
                                                                ),
                                                            )}
                                                    </div>
                                                </div>

                                                {isEnrolled &&
                                                    !isExpired &&
                                                    !lesson.hidden && (
                                                        <Button
                                                            asChild
                                                            size="sm"
                                                            variant="ghost"
                                                        >
                                                            <Link
                                                                href={`/course/${course.slug}/lesson/${lesson.slug}`}
                                                            >
                                                                Open
                                                            </Link>
                                                        </Button>
                                                    )}
                                            </div>
                                        ))}
                                    </CardContent>
                                </Card>
                            ) : (
                                <Card className="flex min-h-[200px] flex-col items-center justify-center gap-2 p-8 text-center">
                                    <h3 className="text-sm font-semibold">
                                        No lessons yet
                                    </h3>
                                    <p className="max-w-xs text-xs text-muted-foreground">
                                        This course doesn't have any lessons
                                        published yet.
                                    </p>
                                </Card>
                            )}
                        </div>

                        {course.assessments &&
                            course.assessments.length > 0 && (
                                <AssessmentsList
                                    course={course}
                                    assessments={course.assessments}
                                />
                        )}
                    </div>

                    <div className="lg:col-span-1">
                        <Card className="sticky top-6">
                            <CardHeader>
                                <CardTitle className="text-2xl">
                                    {course.price === 0
                                        ? 'Free'
                                        : `$ ${course.price}`}
                                </CardTitle>
                                <CardDescription>
                                    {course.access_duration_days
                                        ? `Access for ${course.access_duration_days} days`
                                        : 'Lifetime access'}
                                </CardDescription>
                            </CardHeader>
                            <CardContent className="flex flex-col gap-3 text-sm text-muted-foreground">
                                <div className="flex items-center justify-between">
                                    <span>Lessons</span>
                                    <span className="font-medium text-foreground">
                                        {lessons.length}
                                    </span>
                                </div>
                                <div className="flex items-center justify-between">
                                    <span>Chapters</span>
                                    <span className="font-medium text-foreground">
                                        {totalChapters}
                                    </span>
                                </div>
                                {enrollment?.expires_at && (
                                    <div className="flex items-center justify-between">
                                        <span>
                                            {isExpired ? 'Expired' : 'Expires'}
                                        </span>
                                        <span
                                            className={`font-medium ${isExpired ? 'text-destructive' : 'text-foreground'}`}
                                        >
                                            {parseUtcDate(
                                                enrollment.expires_at,
                                            ).toLocaleDateString()}
                                        </span>
                                    </div>
                                )}
                            </CardContent>
                            <CardFooter>
                                {isEnrolled && !isExpired ? (
                                    firstLesson ? (
                                        <Button asChild className="w-full">
                                            <Link
                                                href={`/course/${course.slug}/lessons`}
                                            >
                                                Continue Learning
                                            </Link>
                                        </Button>
                                    ) : (
                                        <Button className="w-full" disabled>
                                            No lessons yet
                                        </Button>
                                    )
                                ) : (
                                    <Button
                                        className="w-full cursor-pointer"
                                        onClick={handleEnroll}
                                        disabled={!hasLessons}
                                    >
                                        {isExpired
                                            ? 'Renew Access'
                                            : course.price === 0
                                              ? 'Enroll for Free'
                                              : 'Buy Course'}
                                    </Button>
                                )}
                            </CardFooter>
                        </Card>
                    </div>
                </div>
            </div>
        </>
    );
}
