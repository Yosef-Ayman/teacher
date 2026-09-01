export enum ChapterContentType {
    VIDEO = 'video',
    MARKDOWN = 'markdown',
    ASSESSMENT = 'assessment',
    EMPTY = 'empty',
}

export const ChapterContentTypeNames: Record<ChapterContentType, string> = {
    [ChapterContentType.VIDEO]: 'Video',
    [ChapterContentType.MARKDOWN]: 'Reading',
    [ChapterContentType.ASSESSMENT]: 'Assessment',
    [ChapterContentType.EMPTY]: 'Empty',
};
