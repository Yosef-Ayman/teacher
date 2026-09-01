export enum AssessmentType {
    QUIZ = 'quiz',
    HOMEWORK = 'homework',
    SHORT_QUIZ = 'short_quiz',
    EXAM = 'exam',
}

export const AssessmentTypeNames: Record<AssessmentType, string> = {
    [AssessmentType.QUIZ]: 'Quiz',
    [AssessmentType.HOMEWORK]: 'Homework',
    [AssessmentType.SHORT_QUIZ]: 'Short Quiz',
    [AssessmentType.EXAM]: 'Exam',
};
