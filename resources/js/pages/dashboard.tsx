import { Head } from '@inertiajs/react';
import {
    ArcElement,
    BarElement,
    CategoryScale,
    Chart as ChartJS,
    Filler,
    Legend,
    LineElement,
    LinearScale,
    PointElement,
    Tooltip,
} from 'chart.js';
import {
    ArrowDownRight,
    ArrowUpRight,
    Eye,
    Globe2,
    MousePointerClick,
    Users,
} from 'lucide-react';
import { Bar, Doughnut, Line } from 'react-chartjs-2';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { useChartPalette } from '@/hooks/use-chart-palette';
import type { ChartPalette } from '@/hooks/use-chart-palette';
import { cn } from '@/lib/utils';
import { dashboard } from '@/routes';

ChartJS.register(
    ArcElement,
    BarElement,
    CategoryScale,
    Filler,
    Legend,
    LineElement,
    LinearScale,
    PointElement,
    Tooltip,
);

const createTrafficData = (palette: ChartPalette) => ({
    labels: [
        '01 Aug',
        '04 Aug',
        '07 Aug',
        '10 Aug',
        '13 Aug',
        '16 Aug',
        '19 Aug',
        '22 Aug',
        '25 Aug',
        '28 Aug',
    ],
    datasets: [
        {
            label: 'Sessions',
            data: [184, 236, 208, 312, 285, 384, 351, 426, 402, 488],
            borderColor: palette.primary,
            backgroundColor: palette.area,
            fill: true,
            tension: 0.38,
            pointRadius: 0,
            pointHoverRadius: 5,
            borderWidth: 2,
        },
    ],
});

const createDeviceData = (palette: ChartPalette) => ({
    labels: ['Mobile', 'Desktop', 'Tablet'],
    datasets: [
        {
            data: [62, 29, 9],
            backgroundColor: [
                palette.secondary,
                palette.primary,
                palette.quaternary,
            ],
            borderRadius: 4,
            borderSkipped: false,
        },
    ],
});

const createSourceData = (palette: ChartPalette) => ({
    labels: ['Organic search', 'Direct', 'Social', 'Referral'],
    datasets: [
        {
            data: [44, 28, 18, 10],
            backgroundColor: [
                palette.primary,
                palette.secondary,
                palette.tertiary,
                palette.quaternary,
            ],
            borderWidth: 0,
            hoverOffset: 4,
        },
    ],
});

const createChartOptions = (palette: ChartPalette) => ({
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
        tooltip: {
            backgroundColor: palette.tooltip,
            padding: 12,
            titleColor: palette.tooltipText,
            bodyColor: palette.tooltipText,
            displayColors: false,
        },
    },
});

const metrics = [
    {
        label: 'Total users',
        value: '8,492',
        change: '+12.4%',
        trend: 'up',
        icon: Users,
    },
    {
        label: 'Sessions',
        value: '12,847',
        change: '+8.2%',
        trend: 'up',
        icon: MousePointerClick,
    },
    {
        label: 'Page views',
        value: '31,284',
        change: '+15.8%',
        trend: 'up',
        icon: Eye,
    },
    {
        label: 'Engagement rate',
        value: '64.7%',
        change: '-2.1%',
        trend: 'down',
        icon: Globe2,
    },
] as const;

const topPages = [
    { page: '/', views: '8,924', engagement: '68.4%' },
    { page: '/stays', views: '6,512', engagement: '61.2%' },
    { page: '/experiences', views: '4,186', engagement: '57.9%' },
    { page: '/wellness', views: '2,731', engagement: '59.6%' },
];

const createEventData = (palette: ChartPalette) => ({
    labels: [
        'page_view',
        'click',
        'click_booking',
        'view_property',
        'form_start',
        'generate_lead',
    ],
    datasets: [
        {
            data: [31284, 8421, 2178, 1936, 864, 328],
            backgroundColor: [
                palette.secondary,
                palette.tertiary,
                palette.primary,
                palette.quaternary,
                palette.tertiary,
                palette.light,
            ],
            borderRadius: 4,
            borderSkipped: false,
        },
    ],
});

const events = [
    { name: 'page_view', count: '31,284', users: '8,492', conversion: '—' },
    { name: 'click', count: '8,421', users: '4,738', conversion: '55.8%' },
    {
        name: 'click_booking',
        count: '2,178',
        users: '1,804',
        conversion: '21.2%',
    },
    {
        name: 'view_property',
        count: '1,936',
        users: '1,421',
        conversion: '16.7%',
    },
    { name: 'form_start', count: '864', users: '742', conversion: '8.7%' },
    { name: 'generate_lead', count: '328', users: '296', conversion: '3.5%' },
];

export default function Dashboard() {
    const palette = useChartPalette();
    const trafficData = createTrafficData(palette);
    const deviceData = createDeviceData(palette);
    const sourceData = createSourceData(palette);
    const eventData = createEventData(palette);
    const chartOptions = createChartOptions(palette);

    return (
        <>
            <Head title="Analytics Dashboard" />
            <div className="mx-auto flex w-full max-w-7xl flex-1 flex-col gap-6 p-4 md:p-6">
                <header className="flex flex-col gap-3 border-b border-border pb-6 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p className="text-xs font-medium tracking-[0.16em] text-brand-accent uppercase">
                            Website analytics
                        </p>
                        <h1 className="mt-2 font-serif text-3xl font-medium tracking-tight text-foreground md:text-4xl">
                            Traffic overview
                        </h1>
                    </div>
                    <p className="text-sm text-muted-foreground">
                        1–30 August 2026{' '}
                        <span className="mx-2 text-border">|</span> Demo data
                    </p>
                </header>

                <section
                    aria-label="Traffic summary"
                    className="grid gap-4 sm:grid-cols-2 xl:grid-cols-4"
                >
                    {metrics.map(
                        ({ label, value, change, trend, icon: Icon }) => (
                            <Card
                                key={label}
                                className="gap-4 py-5 shadow-none"
                            >
                                <CardContent className="flex items-start justify-between px-5">
                                    <div>
                                        <p className="text-sm text-muted-foreground">
                                            {label}
                                        </p>
                                        <p className="mt-2 text-2xl font-semibold tracking-tight">
                                            {value}
                                        </p>
                                        <p
                                            className={cn(
                                                'mt-2 flex items-center gap-1 text-xs font-medium',
                                                trend === 'up'
                                                    ? 'text-success'
                                                    : 'text-destructive',
                                            )}
                                        >
                                            {trend === 'up' ? (
                                                <ArrowUpRight className="size-3.5" />
                                            ) : (
                                                <ArrowDownRight className="size-3.5" />
                                            )}
                                            {change}{' '}
                                            <span className="font-normal text-muted-foreground">
                                                vs previous period
                                            </span>
                                        </p>
                                    </div>
                                    <Icon
                                        className="size-5 text-brand-accent"
                                        strokeWidth={1.5}
                                        aria-hidden="true"
                                    />
                                </CardContent>
                            </Card>
                        ),
                    )}
                </section>

                <section className="grid gap-6 xl:grid-cols-[minmax(0,1fr)_320px]">
                    <Card className="gap-0 py-0 shadow-none">
                        <CardHeader className="flex-row items-start justify-between px-6 py-5">
                            <div>
                                <CardTitle>Sessions over time</CardTitle>
                                <p className="mt-1 text-sm text-muted-foreground">
                                    Daily website visits in the selected period
                                </p>
                            </div>
                            <span className="rounded-md bg-muted px-2 py-1 text-xs font-medium">
                                Last 30 days
                            </span>
                        </CardHeader>
                        <CardContent className="h-80 px-4 pb-5 sm:px-6">
                            <Line
                                data={trafficData}
                                options={{
                                    ...chartOptions,
                                    scales: {
                                        x: {
                                            grid: { display: false },
                                            ticks: {
                                                color: palette.text,
                                                maxRotation: 0,
                                            },
                                            border: { display: false },
                                        },
                                        y: {
                                            beginAtZero: true,
                                            grid: {
                                                color: palette.grid,
                                            },
                                            ticks: {
                                                color: palette.text,
                                                precision: 0,
                                            },
                                            border: { display: false },
                                        },
                                    },
                                }}
                            />
                        </CardContent>
                    </Card>

                    <Card className="gap-0 py-0 shadow-none">
                        <CardHeader className="px-6 py-5">
                            <CardTitle>Traffic sources</CardTitle>
                            <p className="mt-1 text-sm text-muted-foreground">
                                How visitors find Ini Vie
                            </p>
                        </CardHeader>
                        <CardContent className="flex flex-1 flex-col px-6 pb-5">
                            <div className="mx-auto h-44 w-44">
                                <Doughnut
                                    data={sourceData}
                                    options={{ ...chartOptions, cutout: '68%' }}
                                />
                            </div>
                            <ul className="mt-6 space-y-3">
                                {sourceData.labels.map((label, index) => (
                                    <li
                                        key={label}
                                        className="flex items-center justify-between text-sm"
                                    >
                                        <span className="flex items-center gap-2 text-muted-foreground">
                                            <span
                                                className="size-2 rounded-full"
                                                style={{
                                                    backgroundColor:
                                                        sourceData.datasets[0]
                                                            .backgroundColor[
                                                            index
                                                        ],
                                                }}
                                            />
                                            {label}
                                        </span>
                                        <span className="font-medium">
                                            {sourceData.datasets[0].data[index]}
                                            %
                                        </span>
                                    </li>
                                ))}
                            </ul>
                        </CardContent>
                    </Card>
                </section>

                <section
                    aria-labelledby="event-performance-title"
                    className="grid gap-6 xl:grid-cols-[minmax(0,1fr)_440px]"
                >
                    <Card className="gap-0 py-0 shadow-none">
                        <CardHeader className="px-6 py-5">
                            <CardTitle id="event-performance-title">
                                Event performance
                            </CardTitle>
                            <p className="mt-1 text-sm text-muted-foreground">
                                Event volume captured across the website
                            </p>
                        </CardHeader>
                        <CardContent className="h-80 px-5 pb-5">
                            <Bar
                                data={eventData}
                                options={{
                                    ...chartOptions,
                                    indexAxis: 'y',
                                    scales: {
                                        x: {
                                            beginAtZero: true,
                                            grid: {
                                                color: palette.grid,
                                            },
                                            border: { display: false },
                                            ticks: {
                                                color: palette.text,
                                                callback: (value) =>
                                                    Number(
                                                        value,
                                                    ).toLocaleString('en-US'),
                                            },
                                        },
                                        y: {
                                            grid: { display: false },
                                            border: { display: false },
                                            ticks: {
                                                color: palette.text,
                                                font: { family: 'monospace' },
                                            },
                                        },
                                    },
                                }}
                            />
                        </CardContent>
                    </Card>

                    <Card className="gap-0 py-0 shadow-none">
                        <CardHeader className="px-6 py-5">
                            <CardTitle>Event details</CardTitle>
                            <p className="mt-1 text-sm text-muted-foreground">
                                Users and conversion by tracked action
                            </p>
                        </CardHeader>
                        <CardContent className="px-6 pb-3">
                            <div className="overflow-x-auto">
                                <table className="w-full min-w-[390px] text-left text-sm">
                                    <thead className="border-b bg-muted/60 text-xs tracking-wider text-muted-foreground uppercase">
                                        <tr>
                                            <th className="pb-3 font-medium">
                                                Event
                                            </th>
                                            <th className="pb-3 text-right font-medium">
                                                Count
                                            </th>
                                            <th className="pb-3 text-right font-medium">
                                                Users
                                            </th>
                                            <th className="pb-3 text-right font-medium">
                                                Rate
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {events.map(
                                            ({
                                                name,
                                                count,
                                                users,
                                                conversion,
                                            }) => (
                                                <tr
                                                    key={name}
                                                    className="border-b transition-colors last:border-0 hover:bg-accent/60"
                                                >
                                                    <td className="py-3 font-mono text-xs">
                                                        {name}
                                                    </td>
                                                    <td className="py-3 text-right font-medium">
                                                        {count}
                                                    </td>
                                                    <td className="py-3 text-right text-muted-foreground">
                                                        {users}
                                                    </td>
                                                    <td className="py-3 text-right text-muted-foreground">
                                                        {conversion}
                                                    </td>
                                                </tr>
                                            ),
                                        )}
                                    </tbody>
                                </table>
                            </div>
                        </CardContent>
                    </Card>
                </section>

                <section className="grid gap-6 lg:grid-cols-2">
                    <Card className="gap-0 py-0 shadow-none">
                        <CardHeader className="px-6 py-5">
                            <CardTitle>Visitors by device</CardTitle>
                            <p className="mt-1 text-sm text-muted-foreground">
                                Share of sessions by device type
                            </p>
                        </CardHeader>
                        <CardContent className="h-72 px-5 pb-5">
                            <Bar
                                data={deviceData}
                                options={{
                                    ...chartOptions,
                                    scales: {
                                        x: {
                                            grid: { display: false },
                                            border: { display: false },
                                            ticks: { color: palette.text },
                                        },
                                        y: {
                                            beginAtZero: true,
                                            max: 70,
                                            grid: {
                                                color: palette.grid,
                                            },
                                            border: { display: false },
                                            ticks: {
                                                color: palette.text,
                                                callback: (value) =>
                                                    `${value}%`,
                                            },
                                        },
                                    },
                                }}
                            />
                        </CardContent>
                    </Card>
                    <Card className="gap-0 py-0 shadow-none">
                        <CardHeader className="px-6 py-5">
                            <CardTitle>Top pages</CardTitle>
                            <p className="mt-1 text-sm text-muted-foreground">
                                Most visited pages this month
                            </p>
                        </CardHeader>
                        <CardContent className="px-6 pb-3">
                            <div className="overflow-x-auto">
                                <table className="w-full min-w-[420px] text-left text-sm">
                                    <thead className="border-b bg-muted/60 text-xs tracking-wider text-muted-foreground uppercase">
                                        <tr>
                                            <th className="pb-3 font-medium">
                                                Page
                                            </th>
                                            <th className="pb-3 text-right font-medium">
                                                Views
                                            </th>
                                            <th className="pb-3 text-right font-medium">
                                                Engagement
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {topPages.map(
                                            ({ page, views, engagement }) => (
                                                <tr
                                                    key={page}
                                                    className="border-b transition-colors last:border-0 hover:bg-accent/60"
                                                >
                                                    <td className="py-3 font-mono text-xs">
                                                        {page}
                                                    </td>
                                                    <td className="py-3 text-right font-medium">
                                                        {views}
                                                    </td>
                                                    <td className="py-3 text-right text-muted-foreground">
                                                        {engagement}
                                                    </td>
                                                </tr>
                                            ),
                                        )}
                                    </tbody>
                                </table>
                            </div>
                        </CardContent>
                    </Card>
                </section>
            </div>
        </>
    );
}

Dashboard.layout = {
    breadcrumbs: [{ title: 'Dashboard', href: dashboard() }],
};
