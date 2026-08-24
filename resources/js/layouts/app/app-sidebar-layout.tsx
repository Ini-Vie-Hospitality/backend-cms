import { AppContent } from '@/components/app-content';
import { AppShell } from '@/components/app-shell';
import { AppSidebar } from '@/components/app-sidebar';
import { AppSidebarHeader } from '@/components/app-sidebar-header';
import type { AppLayoutProps } from '@/types';

export default function AppSidebarLayout({
    children,
    breadcrumbs = [],
}: AppLayoutProps) {
    return (
        <AppShell variant="sidebar">
            <AppSidebar />
            <AppContent variant="sidebar" className="min-w-0 overflow-x-clip">
                <AppSidebarHeader breadcrumbs={breadcrumbs} />
                <div className="w-full px-4 py-8 sm:px-6 lg:px-10 xl:px-12">
                    <div className="w-full max-w-[1180px] space-y-10">
                        {children}
                    </div>
                </div>
            </AppContent>
        </AppShell>
    );
}
