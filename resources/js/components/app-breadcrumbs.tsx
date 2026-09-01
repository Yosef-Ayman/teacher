import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from '@/components/ui/breadcrumb';

export interface AppBreadcrumbItem {
    title: string;
    href?: string;
}

interface Props {
    items: AppBreadcrumbItem[];
}

export function AppBreadcrumbs({ items }: Props) {
    return (
        <Breadcrumb>
            <BreadcrumbList>
                {items.map((item, index) => (
                    <div key={item.title} className="flex items-center">
                        <BreadcrumbItem>
                            {item.href ? (
                                <BreadcrumbLink href={item.href}>
                                    {item.title}
                                </BreadcrumbLink>
                            ) : (
                                <BreadcrumbPage>{item.title}</BreadcrumbPage>
                            )}
                        </BreadcrumbItem>

                        {index < items.length - 1 && <BreadcrumbSeparator />}
                    </div>
                ))}
            </BreadcrumbList>
        </Breadcrumb>
    );
}
