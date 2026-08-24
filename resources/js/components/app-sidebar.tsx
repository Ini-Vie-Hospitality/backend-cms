import { Link } from '@inertiajs/react';
import {
    Award,
    BadgePercent,
    BookHeart,
    CircleHelp,
    Crown,
    HeartPulse,
    History,
    Hotel,
    LayoutGrid,
    Navigation,
    Newspaper,
    PanelBottom,
    Sparkles,
    ScanEye,
    Utensils,
} from 'lucide-react';
import AppLogo from '@/components/app-logo';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';

const mainNavItems: NavItem[] = [
    { title: 'Dashboard', href: dashboard(), icon: LayoutGrid },
    { title: 'Navbar', href: '/cms/homepage/navbar', icon: Navigation },
    {
        title: 'Brand Introduction',
        href: '/cms/homepage/brand-introduction',
        icon: Sparkles,
    },
    {
        title: 'Featured Properties',
        href: '/cms/homepage/featured-properties',
        icon: Hotel,
    },
    {
        title: 'Culinary Journey',
        href: '/cms/homepage/culinary',
        icon: Utensils,
    },
    {
        title: 'Wellness Harmony',
        href: '/cms/homepage/wellness',
        icon: HeartPulse,
    },
    { title: 'Membership', href: '/cms/homepage/membership', icon: Crown },
    { title: 'Our Story', href: '/cms/homepage/our-story', icon: BookHeart },
    {
        title: 'Special Offers',
        href: '/cms/homepage/special-offers',
        icon: BadgePercent,
    },
    { title: "What's New", href: '/cms/homepage/whats-new', icon: Newspaper },
    { title: 'Featured In', href: '/cms/homepage/featured-in', icon: Award },
    { title: 'FAQ', href: '/cms/homepage/faq', icon: CircleHelp },
    { title: 'Footer', href: '/cms/homepage/footer', icon: PanelBottom },
    { title: 'Preview', href: '/cms/homepage/preview', icon: ScanEye },
    { title: 'Publish History', href: '/cms/homepage/history', icon: History },
];

export function AppSidebar() {
    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={dashboard()} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>
            <SidebarContent>
                <NavMain items={mainNavItems} />
            </SidebarContent>
            <SidebarFooter>
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
