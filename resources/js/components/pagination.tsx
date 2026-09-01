// resources/js/components/pagination.tsx
import { Link } from '@inertiajs/react';
import { buttonVariants } from '@/components/ui/button';
import {
    Pagination as PaginationRoot,
    PaginationContent,
    PaginationEllipsis,
    PaginationItem,
} from '@/components/ui/pagination';
import { cn } from '@/lib/utils';
import type { PaginationLink as PaginationLinkType } from '@/types/pagination';

export default function Pagination({ links }: { links: PaginationLinkType[] }) {
    if (links.length <= 3) {
        return null;
    }

    const prev = links[0];
    const next = links[links.length - 1];
    const middle = links.slice(1, -1);

    return (
        <PaginationRoot>
            <PaginationContent>
                <PaginationItem>
                    {prev.url ? (
                        <Link
                            href={prev.url}
                            preserveScroll
                            className={cn(
                                buttonVariants({
                                    variant: 'ghost',
                                    size: 'default',
                                }),
                                'gap-1 pl-2.5',
                            )}
                        >
                            Previous
                        </Link>
                    ) : (
                        <span
                            className={cn(
                                buttonVariants({
                                    variant: 'ghost',
                                    size: 'default',
                                }),
                                'pointer-events-none gap-1 pl-2.5 opacity-40',
                            )}
                        >
                            Previous
                        </span>
                    )}
                </PaginationItem>

                {middle.map((link, i) =>
                    link.label === '...' ? (
                        <PaginationItem key={i}>
                            <PaginationEllipsis />
                        </PaginationItem>
                    ) : (
                        <PaginationItem key={i}>
                            {link.url ? (
                                <Link
                                    href={link.url}
                                    preserveScroll
                                    aria-current={
                                        link.active ? 'page' : undefined
                                    }
                                    className={cn(
                                        buttonVariants({
                                            variant: link.active
                                                ? 'outline'
                                                : 'ghost',
                                            size: 'icon',
                                        }),
                                    )}
                                >
                                    {link.label}
                                </Link>
                            ) : (
                                <span
                                    className={cn(
                                        buttonVariants({
                                            variant: 'ghost',
                                            size: 'icon',
                                        }),
                                        'pointer-events-none opacity-40',
                                    )}
                                >
                                    {link.label}
                                </span>
                            )}
                        </PaginationItem>
                    ),
                )}

                <PaginationItem>
                    {next.url ? (
                        <Link
                            href={next.url}
                            preserveScroll
                            className={cn(
                                buttonVariants({
                                    variant: 'ghost',
                                    size: 'default',
                                }),
                                'gap-1 pr-2.5',
                            )}
                        >
                            Next
                        </Link>
                    ) : (
                        <span
                            className={cn(
                                buttonVariants({
                                    variant: 'ghost',
                                    size: 'default',
                                }),
                                'pointer-events-none gap-1 pr-2.5 opacity-40',
                            )}
                        >
                            Next
                        </span>
                    )}
                </PaginationItem>
            </PaginationContent>
        </PaginationRoot>
    );
}
