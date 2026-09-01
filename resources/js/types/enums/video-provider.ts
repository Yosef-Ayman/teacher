export enum VideoProvider {
    Youtube = 'youtube',
    VIMEO = 'vimeo',
    STREAMABLE = 'streamable',
}

export const VideoProviderNames: Record<VideoProvider, string> = {
    [VideoProvider.Youtube]: 'Youtube',
    [VideoProvider.VIMEO]: 'Vimeo',
    [VideoProvider.STREAMABLE]: 'Streamable',
};
