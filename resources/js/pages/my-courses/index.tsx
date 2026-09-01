import { Link, usePage } from '@inertiajs/react';
import Pagination from '@/components/pagination';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { parseUtcDate } from '@/lib/date';
import type { Enrollment } from '@/types/models';
import type { Paginated } from '@/types/pagination';

type PageProps = {
    courses: Paginated<Enrollment>;
};

export default function MyCoursesIndex() {
    const { courses } = usePage<PageProps>().props;

    return (
        <>
            <div className="flex flex-col gap-6 p-4 md:p-6">
                <div>
                    <h1 className="text-2xl font-bold tracking-tight">
                        My Courses
                    </h1>
                    <p className="text-sm text-muted-foreground">
                        Courses you have access to
                    </p>
                </div>

                {courses.data.length === 0 ? (
                    <Card className="flex min-h-[400px] flex-col items-center justify-center p-8 text-center">
                        <div className="flex h-16 w-16 items-center justify-center rounded-full bg-muted">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                className="h-8 w-8 text-muted-foreground"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth={1.5}
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"
                                />
                            </svg>
                        </div>
                        <h3 className="mt-4 text-lg font-semibold">
                            No courses yet
                        </h3>
                        <p className="mt-2 max-w-sm text-sm text-muted-foreground">
                            You haven't enrolled in any courses yet.
                        </p>
                    </Card>
                ) : (
                    <>
                        <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            {courses.data.map((enrollment) => {
                                const course = enrollment.course;

                                if (!course) {
                                    return null;
                                }

                                return (
                                    <Card
                                        key={course.id}
                                        className="flex flex-col justify-between overflow-hidden transition-all hover:shadow-md"
                                    >
                                        <CardHeader className="p-0">
                                            <div className="relative aspect-video w-full overflow-hidden bg-muted">
                                                {course.thumbnail_url ? (
                                                    <img
                                                        src={
                                                            course.thumbnail_url
                                                        }
                                                        alt={course.title}
                                                        className="h-full w-full object-cover"
                                                    />
                                                ) : (
                                                    <div
                                                        className="flex h-full w-full items-center justify-center text-muted-foreground"
                                                        style={{
                                                            background:
                                                                'repeating-linear-gradient(45deg, rgba(244, 244, 241, 0.06) 0 1px, transparent 1px 14px), linear-gradient(135deg, #14140f, #0C0C09)',
                                                        }}
                                                    >
                                                        <span>No Cover</span>
                                                    </div>
                                                )}

                                                <div className="absolute top-3 right-3">
                                                    <Badge
                                                        variant={
                                                            course.price === 0
                                                                ? 'secondary'
                                                                : 'default'
                                                        }
                                                    >
                                                        {course.price === 0
                                                            ? 'Free'
                                                            : `$ ${course.price}`}
                                                    </Badge>
                                                </div>
                                            </div>
                                        </CardHeader>

                                        <CardContent className="flex-1 p-4">
                                            <CardTitle className="line-clamp-1 text-lg">
                                                {course.title}
                                            </CardTitle>
                                            <CardDescription className="mt-2 line-clamp-2 text-sm">
                                                {course.description}
                                            </CardDescription>
                                        </CardContent>

                                        <CardFooter className="flex items-center justify-between border-t p-4 text-xs text-muted-foreground">
                                            <span>
                                                {enrollment.expires_at
                                                    ? `Expires at ${parseUtcDate(enrollment.expires_at).toLocaleDateString()}`
                                                    : 'Lifetime'}
                                            </span>

                                            <Button asChild size="sm">
                                                <Link
                                                    href={`/course/${course.slug}`}
                                                >
                                                    View Course
                                                </Link>
                                            </Button>
                                        </CardFooter>
                                    </Card>
                                );
                            })}
                        </div>

                        <div className="flex justify-center">
                            <Pagination links={courses.links} />
                        </div>
                    </>
                )}
            </div>
        </>
    );
}
