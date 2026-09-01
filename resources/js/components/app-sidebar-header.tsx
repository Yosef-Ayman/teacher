import { Clock } from 'lucide-react';
import { useEffect, useState } from 'react';
import { Breadcrumbs } from '@/components/breadcrumbs';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItem as BreadcrumbItemType } from '@/types';

export function useUTCClock() {
    const [time, setTime] = useState<string>('');

    useEffect(() => {
        setTime(new Date().toUTCString());

        const i = setInterval(() => {
            setTime(new Date().toUTCString());
        }, 1000);

        return () => clearInterval(i);
    }, []);

    return time;
}

export function AppSidebarHeader({
    breadcrumbs = [],
}: {
    breadcrumbs?: BreadcrumbItemType[];
}) {
    return (
        <header className="flex h-16 shrink-0 items-center gap-2 border-b border-sidebar-border/50 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4">
            <div className="flex items-center gap-2">
                <SidebarTrigger className="-ml-1" />
                <Breadcrumbs breadcrumbs={breadcrumbs} />
                <div
                    className={`inline-flex w-fit items-center gap-2 rounded-full border px-3 py-1.5 text-sm font-medium`}
                >
                    <Clock />

                    <div>{useUTCClock()}</div>
                </div>
            </div>
        </header>
    );
}
