import { Head, Link, usePage } from '@inertiajs/react';
import { Clock3, FileQuestion, Target, Trophy } from 'lucide-react';
import ReactMarkdown from 'react-markdown';
import remarkGfm from 'remark-gfm';
import { AppBreadcrumbs } from '@/components/app-breadcrumbs';
import AssessmentsList from '@/components/assessments-list';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { AssessmentTypeNames } from '@/types/enums';
import type { Course, Lesson, Chapter, Video } from '@/types/models';

type PageProps = {
    course: Course;
    lessons: Lesson[];
    lesson: Lesson;
    chapters: Chapter[];
};

function VideoEmbed({ video }: { video: Video }) {
    if (video.embed_url) {
        return (
            <iframe
                src={video.embed_url}
                className="h-full w-full"
                allow="autoplay; fullscreen; picture-in-picture"
                allowFullScreen
                title={video.title}
            />
        );
    }

    return (
        <div className="flex h-full items-center justify-center text-sm text-muted-foreground">
            Unsupported video provider: {video.provider}
        </div>
    );
}

export default function LessonShow() {
    const { course, lessons, lesson, chapters } = usePage<PageProps>().props;

    const sortedLessons = [...lessons].sort((a, b) => a.position - b.position);
    const sortedChapters = [...chapters].sort(
        (a, b) => a.position - b.position,
    );
    const hasChapters = sortedChapters.length > 0;

    const currentIndex = sortedLessons.findIndex((l) => l.id === lesson.id);
    const prevLesson =
        currentIndex > 0 ? sortedLessons[currentIndex - 1] : null;
    const nextLesson =
        currentIndex >= 0 && currentIndex < sortedLessons.length - 1
            ? sortedLessons[currentIndex + 1]
            : null;

    return (
        <>
            <Head title={lesson.title} />

            <div className="grid min-w-0 gap-6 p-4 md:grid-cols-4 md:p-6">
                <div className="md:col-span-4">
                    <AppBreadcrumbs
                        items={[
                            { title: 'All Courses', href: '/courses' },
                            {
                                title: course.title,
                                href: `/course/${course.slug}`,
                            },
                            { title: lesson.title },
                        ]}
                    />
                </div>

                <div className="md:col-span-1">
                    <Card>
                        <CardContent className="flex flex-col gap-1 p-2">
                            {sortedLessons.map((item, index) => {
                                const isActive = item.id === lesson.id;

                                return (
                                    <Link
                                        key={item.id}
                                        href={`/course/${course.slug}/lesson/${item.slug}`}
                                        className={`flex items-center gap-3 rounded-md px-3 py-2 text-sm transition-colors ${
                                            isActive
                                                ? 'bg-secondary font-semibold text-secondary-foreground'
                                                : 'text-muted-foreground hover:bg-secondary/50 hover:text-foreground'
                                        }`}
                                    >
                                        <span className="font-mono text-xs text-muted-foreground">
                                            {String(index + 1).padStart(2, '0')}
                                        </span>
                                        <span className="line-clamp-1">
                                            {item.title}
                                        </span>
                                    </Link>
                                );
                            })}
                        </CardContent>
                    </Card>
                </div>

                <div className="flex min-w-0 flex-col gap-6 md:col-span-3">
                    <div className="flex min-w-0 flex-wrap items-center justify-between gap-2">
                        <h1 className="text-xl font-bold tracking-tight">
                            {lesson.title}
                        </h1>
                        {hasChapters && (
                            <Badge variant="outline">
                                {sortedChapters.length} chapter
                                {sortedChapters.length !== 1 && 's'}
                            </Badge>
                        )}
                    </div>

                    {hasChapters ? (
                        <div className="flex min-w-0 flex-col gap-10">
                            {sortedChapters.map((chapter, index) => (
                                <div
                                    key={chapter.id}
                                    className="flex min-w-0 flex-col gap-3"
                                >
                                    <span className="text-xs font-semibold tracking-wide text-muted-foreground uppercase">
                                        Chapter {index + 1} ·{' '}
                                        {chapter.video
                                            ? 'Video'
                                            : chapter.markdown
                                              ? 'Reading'
                                              : chapter.assessment
                                                ? 'Assessment'
                                                : 'Empty'}
                                    </span>

                                    {chapter.video && (
                                        <Card className="overflow-hidden">
                                            <div className="aspect-video w-full bg-black">
                                                <VideoEmbed
                                                    video={chapter.video}
                                                />
                                            </div>
                                        </Card>
                                    )}

                                    {chapter.markdown && (
                                        <Card>
                                            <CardContent className="max-w-full min-w-0 overflow-hidden p-6">
                                                <div className="prose max-w-none min-w-0 break-words prose-neutral dark:prose-invert prose-pre:max-w-full prose-pre:overflow-x-auto prose-table:block prose-table:overflow-x-auto prose-img:h-auto prose-img:max-w-full [&_*]:min-w-0">
                                                    <ReactMarkdown
                                                        remarkPlugins={[
                                                            remarkGfm,
                                                        ]}
                                                    >
                                                        {chapter.markdown
                                                            .body ?? ''}
                                                    </ReactMarkdown>
                                                </div>
                                            </CardContent>
                                        </Card>
                                    )}

                                    {chapter.assessment && (
                                        <Card className="border-primary/20 transition-colors hover:border-primary/50">
                                            <div className="flex flex-col items-center justify-between p-5 sm:flex-row">
                                                <div className="flex flex-col gap-4 sm:flex-row">
                                                    <div className="flex h-12 w-12 items-center justify-center rounded-xl bg-primary/10">
                                                        <FileQuestion className="h-6 w-6 text-primary" />
                                                    </div>

                                                    <div>
                                                        <div className="flex items-center gap-2">
                                                            <h3 className="font-semibold">
                                                                {
                                                                    chapter
                                                                        .assessment
                                                                        .title
                                                                }
                                                            </h3>
                                                            <Badge variant="secondary">
                                                                {
                                                                    AssessmentTypeNames[
                                                                        chapter.assessment.type
                                                                    ]
                                                                }
                                                            </Badge>
                                                        </div>

                                                        {chapter.assessment
                                                            .description && (
                                                            <p className="mt-1 text-sm text-muted-foreground">
                                                                {
                                                                    chapter
                                                                        .assessment
                                                                        .description
                                                                }
                                                            </p>
                                                        )}

                                                        <div className="mt-3 flex flex-wrap gap-4 text-xs text-muted-foreground">
                                                            <span className="flex items-center gap-1">
                                                                <Trophy className="h-3.5 w-3.5" />
                                                                {
                                                                    chapter
                                                                        .assessment
                                                                        .total_points
                                                                }{' '}
                                                                pts
                                                            </span>
                                                            <span className="flex items-center gap-1">
                                                                <Clock3 className="h-3.5 w-3.5" />
                                                                {
                                                                    chapter
                                                                        .assessment
                                                                        .duration_minutes
                                                                }{' '}
                                                                min
                                                            </span>
                                                            <span className="flex items-center gap-1">
                                                                <Target className="h-3.5 w-3.5" />
                                                                {
                                                                    chapter
                                                                        .assessment
                                                                        .attempts
                                                                }{' '}
                                                                attempt
                                                                {chapter
                                                                    .assessment
                                                                    .attempts !==
                                                                    1 && 's'}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <Button
                                                    asChild
                                                    className="mt-4 w-full sm:mt-0 sm:w-auto"
                                                >
                                                    <Link
                                                        href={`/course/${course.slug}/assessment/${chapter.assessment.slug}`}
                                                    >
                                                        Start Quiz
                                                    </Link>
                                                </Button>
                                            </div>
                                        </Card>
                                    )}

                                    {!chapter.video &&
                                        !chapter.markdown &&
                                        !chapter.assessment && (
                                            <Card className="p-6 text-sm text-muted-foreground">
                                                This chapter has no content yet.
                                            </Card>
                                        )}
                                </div>
                            ))}
                        </div>
                    ) : (
                        <Card className="flex min-h-[240px] flex-col items-center justify-center gap-2 p-8 text-center">
                            <h3 className="text-sm font-semibold">
                                No content yet
                            </h3>
                            <p className="max-w-xs text-xs text-muted-foreground">
                                This lesson doesn't have any chapters published
                                yet.
                            </p>
                        </Card>
                    )}

                    {course.assessments && course.assessments.length > 0 && (
                        <AssessmentsList
                            course={course}
                            assessments={course.assessments}
                        />
                    )}

                    <div className="flex items-center justify-between border-t pt-6">
                        {prevLesson ? (
                            <Button asChild variant="outline">
                                <Link
                                    href={`/course/${course.slug}/lesson/${prevLesson.slug}`}
                                >
                                    ← {prevLesson.title}
                                </Link>
                            </Button>
                        ) : (
                            <span />
                        )}

                        {nextLesson ? (
                            <Button asChild>
                                <Link
                                    href={`/course/${course.slug}/lesson/${nextLesson.slug}`}
                                >
                                    {nextLesson.title} →
                                </Link>
                            </Button>
                        ) : (
                            <Button asChild variant="outline">
                                <Link href={`/course/${course.slug}`}>
                                    Back to syllabus
                                </Link>
                            </Button>
                        )}
                    </div>
                </div>
            </div>
        </>
    );
}
