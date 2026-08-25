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
import { ArrowDownRight, ArrowUpRight, Eye, Globe2, MousePointerClick, Users } from 'lucide-react';
import { Bar, Doughnut, Line } from 'react-chartjs-2';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { useChartPalette } from '@/hooks/use-chart-palette';
import type { ChartPalette } from '@/hooks/use-chart-palette';
import { dashboard } from '@/routes';

ChartJS.register(ArcElement, BarElement, CategoryScale, Filler, Legend, LineElement, LinearScale, PointElement, Tooltip);

type AnalyticsMetric = { label: string; value: number; change: number | null; format: 'number' | 'percent' };
type AnalyticsRow = { dimensions: string[]; metrics: number[] };
type AnalyticsData = {
    status: 'ok' | 'unavailable';
    period: { start: string; end: string };
    metrics: Record<string, AnalyticsMetric>;
    traffic: AnalyticsRow[];
    devices: AnalyticsRow[];
    sources: AnalyticsRow[];
    pages: AnalyticsRow[];
    events: AnalyticsRow[];
    error: string | null;
};

const numberFormatter = new Intl.NumberFormat('en-US', { maximumFractionDigits: 0 });
const percentFormatter = new Intl.NumberFormat('en-US', { maximumFractionDigits: 1 });

function formatMetric(metric: AnalyticsMetric) {
    return metric.format === 'percent' ? `${percentFormatter.format(metric.value)}%` : numberFormatter.format(metric.value);
}

function formatChange(change: number | null) {
    if (change === null) {
        return '—';
    }

    return `${change >= 0 ? '+' : ''}${percentFormatter.format(change)}%`;
}

function chartOptions(palette: ChartPalette) {
    return {
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
    };
}

function metricCards(analytics: AnalyticsData) {
    return [
        { key: 'users', icon: Users },
        { key: 'sessions', icon: MousePointerClick },
        { key: 'views', icon: Eye },
        { key: 'engagement_rate', icon: Globe2 },
    ].map(({ key, icon }) => ({ ...analytics.metrics[key], icon }));
}

export default function Dashboard({ analytics }: { analytics: AnalyticsData }) {
    const palette = useChartPalette();
    const options = chartOptions(palette);
    const cards = analytics.status === 'ok' ? metricCards(analytics) : [];
    const trafficLabels = analytics.traffic.map(({ dimensions }) => dimensions[0] ?? '');
    const trafficData = analytics.traffic.map(({ metrics }) => metrics[0] ?? 0);
    const deviceLabels = analytics.devices.map(({ dimensions }) => dimensions[0] ?? 'Unknown');
    const deviceData = analytics.devices.map(({ metrics }) => metrics[0] ?? 0);
    const sourceLabels = analytics.sources.map(({ dimensions }) => dimensions[0] ?? 'Unknown');
    const sourceData = analytics.sources.map(({ metrics }) => metrics[0] ?? 0);

    return (
        <>
            <Head title="Analytics Dashboard" />
            <div className="w-full space-y-6">
                <div className="flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <h1 className="text-2xl font-semibold">Website analytics</h1>
                        <p className="mt-1 text-sm text-muted-foreground">Ini Vie Hospitality · last 30 days</p>
                    </div>
                    <p className="text-xs text-muted-foreground">{analytics.period.start} — {analytics.period.end}</p>
                </div>

                {analytics.status === 'unavailable' ? (
                    <Card><CardContent className="p-6 text-sm text-muted-foreground">{analytics.error}</CardContent></Card>
                ) : (
                    <>
                        <section className="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                            {cards.map((metric) => {
                                const Icon = metric.icon;
                                const isUp = metric.change === null || metric.change >= 0;

                                return (
                                    <Card className="gap-3 py-5" key={metric.label}>
                                        <CardContent className="px-5">
                                            <div className="flex items-center justify-between">
                                                <span className="text-sm text-muted-foreground">{metric.label}</span>
                                                <Icon className="size-4 text-muted-foreground" />
                                            </div>
                                            <div className="mt-3 text-2xl font-semibold">{formatMetric(metric)}</div>
                                            <div className={`mt-2 flex items-center gap-1 text-xs ${isUp ? 'text-emerald-600' : 'text-destructive'}`}>
                                                {isUp ? <ArrowUpRight className="size-3.5" /> : <ArrowDownRight className="size-3.5" />}
                                                {formatChange(metric.change)} vs previous period
                                            </div>
                                        </CardContent>
                                    </Card>
                                );
                            })}
                        </section>

                        <section className="grid gap-6 xl:grid-cols-[1.45fr_1fr]">
                            <Card className="gap-0 py-0">
                                <CardHeader className="px-6 py-5"><CardTitle>Sessions over time</CardTitle><p className="mt-1 text-sm text-muted-foreground">Daily website sessions</p></CardHeader>
                                <CardContent className="h-80 px-5 pb-5"><Line data={{ labels: trafficLabels, datasets: [{ label: 'Sessions', data: trafficData, borderColor: palette.primary, backgroundColor: palette.area, fill: true, tension: 0.38, pointRadius: 0, pointHoverRadius: 5, borderWidth: 2 }] }} options={{ ...options, scales: { x: { grid: { display: false }, border: { display: false }, ticks: { color: palette.text, maxTicksLimit: 8 } }, y: { beginAtZero: true, grid: { color: palette.grid }, border: { display: false }, ticks: { color: palette.text } } } }} /></CardContent>
                            </Card>
                            <Card className="gap-0 py-0">
                                <CardHeader className="px-6 py-5"><CardTitle>Visitors by device</CardTitle><p className="mt-1 text-sm text-muted-foreground">Active users by device category</p></CardHeader>
                                <CardContent className="flex h-80 items-center justify-center px-5 pb-5"><div className="h-64 w-full max-w-[280px]"><Doughnut data={{ labels: deviceLabels, datasets: [{ data: deviceData, backgroundColor: [palette.secondary, palette.primary, palette.quaternary, palette.tertiary], borderWidth: 0, hoverOffset: 4 }] }} options={options} /></div></CardContent>
                            </Card>
                        </section>

                        <section className="grid gap-6 xl:grid-cols-[1fr_1.45fr]">
                            <Card className="gap-0 py-0">
                                <CardHeader className="px-6 py-5"><CardTitle>Traffic sources</CardTitle><p className="mt-1 text-sm text-muted-foreground">Sessions by source</p></CardHeader>
                                <CardContent className="h-80 px-5 pb-5"><Bar data={{ labels: sourceLabels, datasets: [{ data: sourceData, backgroundColor: [palette.primary, palette.secondary, palette.tertiary, palette.quaternary], borderRadius: 4, borderSkipped: false }] }} options={{ ...options, indexAxis: 'y', scales: { x: { beginAtZero: true, grid: { color: palette.grid }, border: { display: false }, ticks: { color: palette.text } }, y: { grid: { display: false }, border: { display: false }, ticks: { color: palette.text } } } }} /></CardContent>
                            </Card>
                            <Card className="gap-0 py-0">
                                <CardHeader className="px-6 py-5"><CardTitle>Top pages</CardTitle><p className="mt-1 text-sm text-muted-foreground">Most viewed website pages</p></CardHeader>
                                <CardContent className="px-6 pb-3"><div className="overflow-x-auto"><table className="w-full min-w-[480px] text-left text-sm"><thead className="border-b bg-muted/60 text-xs tracking-wider text-muted-foreground uppercase"><tr><th className="pb-3 font-medium">Page</th><th className="pb-3 text-right font-medium">Views</th><th className="pb-3 text-right font-medium">Engagement</th></tr></thead><tbody>{analytics.pages.map(({ dimensions, metrics }) => <tr className="border-b last:border-0 hover:bg-accent/60" key={dimensions[0]}><td className="max-w-[280px] truncate py-3 font-mono text-xs">{dimensions[0] || '/'}</td><td className="py-3 text-right font-medium">{numberFormatter.format(metrics[0] ?? 0)}</td><td className="py-3 text-right text-muted-foreground">{percentFormatter.format(metrics[1] ?? 0)}%</td></tr>)}</tbody></table></div></CardContent>
                            </Card>
                        </section>

                        <Card className="gap-0 py-0">
                            <CardHeader className="px-6 py-5"><CardTitle>Events</CardTitle><p className="mt-1 text-sm text-muted-foreground">Interactions recorded by the Ini Vie website</p></CardHeader>
                            <CardContent className="px-6 pb-3"><div className="overflow-x-auto"><table className="w-full min-w-[520px] text-left text-sm"><thead className="border-b bg-muted/60 text-xs tracking-wider text-muted-foreground uppercase"><tr><th className="pb-3 font-medium">Event</th><th className="pb-3 text-right font-medium">Count</th><th className="pb-3 text-right font-medium">Users</th></tr></thead><tbody>{analytics.events.map(({ dimensions, metrics }) => <tr className="border-b last:border-0 hover:bg-accent/60" key={dimensions[0]}><td className="py-3 font-mono text-xs">{dimensions[0]}</td><td className="py-3 text-right font-medium">{numberFormatter.format(metrics[0] ?? 0)}</td><td className="py-3 text-right text-muted-foreground">{numberFormatter.format(metrics[1] ?? 0)}</td></tr>)}</tbody></table></div></CardContent>
                        </Card>
                    </>
                )}
            </div>
        </>
    );
}

Dashboard.layout = {
    breadcrumbs: [{ title: 'Dashboard', href: dashboard() }],
};
