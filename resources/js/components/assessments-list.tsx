import { Link } from '@inertiajs/react';
import { Clock3, FileQuestion, Target, Trophy } from 'lucide-react';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import type { Assessment, Course } from '@/types/models';
import { AssessmentType, AssessmentTypeNames } from '@/types/enums';

export default function AssessmentsList({
    course,
    assessments,
}: {
    course: Course;
    assessments: Assessment[];
}) {
    if (assessments.length === 0) {
        return null;
    }

    return (
        <div className="space-y-3">
            <div className="flex items-center justify-between">
                <h2 className="text-lg font-semibold">Assessments</h2>
                <span className="text-xs text-muted-foreground">
                    {assessments.length} assessment
                    {assessments.length !== 1 && 's'}
                </span>
            </div>

            <div className="grid gap-4 sm:grid-cols-2">
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
                                        {AssessmentTypeNames[assessment.type]}
                                    </Badge>

                                    {assessment.duration_minutes > 0 && (
                                        <span className="flex items-center gap-1 text-xs text-muted-foreground">
                                            <Clock3 className="h-3.5 w-3.5" />
                                            {assessment.duration_minutes} min
                                        </span>
                                    )}
                                </div>

                                <h3 className="mb-1 font-semibold">
                                    {assessment.title}
                                </h3>

                                {assessment.description && (
                                    <p className="line-clamp-2 text-sm text-muted-foreground">
                                        {assessment.description}
                                    </p>
                                )}
                            </div>

                            <div className="mt-auto space-y-4 border-t pt-4">
                                <div className="grid grid-cols-2 gap-3 text-xs text-muted-foreground">
                                    <span className="flex items-center gap-1.5">
                                        <Trophy className="h-3.5 w-3.5" />
                                        {assessment.total_points} pts
                                    </span>
                                    <span className="flex items-center gap-1.5">
                                        <Target className="h-3.5 w-3.5" />
                                        {assessment.attempts} attempt
                                        {assessment.attempts !== 1 && 's'}
                                    </span>
                                </div>

                                <Button asChild size="sm" className="w-full">
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
        </div>
    );
}
