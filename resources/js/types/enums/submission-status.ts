export enum SubmissionStatus {
    PROGRESS = 'in_progress',
    SUBMITTED = 'submitted',
    GRADED = 'graded',
}

export const SubmissionStatusNames: Record<SubmissionStatus, string> = {
    [SubmissionStatus.PROGRESS]: 'In Progress',
    [SubmissionStatus.SUBMITTED]: 'Submitted',
    [SubmissionStatus.GRADED]: 'Graded',
};
