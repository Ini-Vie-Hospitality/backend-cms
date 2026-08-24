import { router, usePage } from '@inertiajs/react';
import { Breadcrumbs } from '@/components/breadcrumbs';
import { Button } from '@/components/ui/button';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItem as BreadcrumbItemType } from '@/types';

export function AppSidebarHeader({
    breadcrumbs = [],
}: {
    breadcrumbs?: BreadcrumbItemType[];
}) {
    const workspace = usePage().props.homepageWorkspace as {
        mode: 'draft' | 'published';
    } | null;

    function switchMode(mode: 'draft' | 'published') {
        router.put(
            '/cms/homepage/workspace',
            { mode },
            { preserveScroll: true },
        );
    }

    return (
        <header className="flex h-16 shrink-0 items-center justify-between gap-2 border-b border-border bg-background/95 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4">
            <div className="flex items-center gap-2">
                <SidebarTrigger className="-ml-1" />
                <Breadcrumbs breadcrumbs={breadcrumbs} />
            </div>
            {workspace && (
                <div className="flex rounded-lg border bg-card p-1">
                    <Button
                        size="sm"
                        variant={
                            workspace.mode === 'draft' ? 'default' : 'ghost'
                        }
                        onClick={() => switchMode('draft')}
                    >
                        Draft
                    </Button>
                    <Button
                        size="sm"
                        variant={
                            workspace.mode === 'published' ? 'default' : 'ghost'
                        }
                        onClick={() => switchMode('published')}
                    >
                        Publish
                    </Button>
                </div>
            )}
        </header>
    );
}
