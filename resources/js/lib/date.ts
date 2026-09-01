export function parseUtcDate(dateString: string): Date {
    const hasTimezone = /Z$|[+-]\d{2}:?\d{2}$/.test(dateString);

    if (hasTimezone) {
        return new Date(dateString);
    }

    const normalized = dateString.replace(' ', 'T');

    return new Date(`${normalized}Z`);
}
