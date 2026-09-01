export enum QuestionType {
    MCQ = 'mcq',
    TRUE_FALSE = 'true_false',
    SHORT_ANSWER = 'short_answer',
}

export const QuestionTypeNames: Record<QuestionType, string> = {
    [QuestionType.MCQ]: 'Multiple Choice',
    [QuestionType.TRUE_FALSE]: 'True / False',
    [QuestionType.SHORT_ANSWER]: 'Short Answer',
};
