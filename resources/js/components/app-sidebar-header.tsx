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
            <header className="flex h-16 shrink-0 items-center justify-between gap-2 border-b border-border bg-background/95 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4">
                <div className="flex items-center gap-2">
                    <SidebarTrigger className="-ml-1" />
                    <Breadcrumbs breadcrumbs={breadcrumbs} />
                </div>
                <div className="flex items-center gap-2">
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
