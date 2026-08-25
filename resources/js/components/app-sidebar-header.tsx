import { router, usePage } from '@inertiajs/react';
import { Sparkles } from 'lucide-react';
import { Breadcrumbs } from '@/components/breadcrumbs';
import { CmsCopilotSidebar } from '@/components/cms/copilot-sidebar';
import { Button } from '@/components/ui/button';
import { SidebarTrigger } from '@/components/ui/sidebar';
import {
    Tooltip,
    TooltipContent,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import type { BreadcrumbItem as BreadcrumbItemType } from '@/types';

export function AppSidebarHeader({
    breadcrumbs = [],
}: {
    breadcrumbs?: BreadcrumbItemType[];
}) {
    const page = usePage();
    const workspace = page.props.homepageWorkspace as {
        mode: 'draft' | 'published';
    } | null;
    const copilotAvailable = Boolean(page.props.cmsCopilot);

    function switchMode(mode: 'draft' | 'published') {
        router.put(
            '/cms/homepage/workspace',
            { mode },
            { preserveScroll: true },
        );
    }

    return (
        <>
            <header className="flex min-h-14 shrink-0 flex-wrap items-center justify-between gap-x-2 gap-y-1 border-b border-border bg-background/95 px-3 py-2 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:sm:min-h-12 sm:min-h-16 sm:flex-nowrap sm:px-4 sm:py-0 md:px-6">
                <div className="flex min-w-0 flex-1 items-center gap-2 overflow-hidden">
                    <SidebarTrigger className="-ml-1" />
                    <div className="min-w-0 truncate">
                        <Breadcrumbs breadcrumbs={breadcrumbs} />
                    </div>
                </div>
                <div className="flex shrink-0 items-center gap-2 max-sm:w-full max-sm:justify-end">
                    {workspace && (
                        <div className="flex rounded-lg border bg-card p-1">
                            <Button
                                size="sm"
                                variant={
                                    workspace.mode === 'draft'
                                        ? 'default'
                                        : 'ghost'
                                }
                                onClick={() => switchMode('draft')}
                            >
                                Draft
                            </Button>
                            <Button
                                size="sm"
                                variant={
                                    workspace.mode === 'published'
                                        ? 'default'
                                        : 'ghost'
                                }
                                onClick={() => switchMode('published')}
                            >
                                Publish
                            </Button>
                        </div>
                    )}
                    {copilotAvailable && (
                        <Tooltip>
                            <TooltipTrigger asChild>
                                <Button
                                    aria-label="Open CMS Copilot"
                                    size="icon"
                                    type="button"
                                    variant="ghost"
                                    onClick={() =>
                                        window.dispatchEvent(
                                            new CustomEvent('cms-copilot:open'),
                                        )
                                    }
                                >
                                    <Sparkles className="size-5" />
                                </Button>
                            </TooltipTrigger>
                            <TooltipContent>CMS Copilot</TooltipContent>
                        </Tooltip>
                    )}
                </div>
            </header>
            <CmsCopilotSidebar />
        </>
    );
}
