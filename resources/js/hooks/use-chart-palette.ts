import { useMemo } from 'react';
import { useAppearance } from '@/hooks/use-appearance';

export type ChartPalette = {
    primary: string;
    secondary: string;
    tertiary: string;
    quaternary: string;
    light: string;
    grid: string;
    text: string;
    tooltip: string;
    tooltipText: string;
    area: string;
};

const fallback: ChartPalette = {
    primary: '#C79A52',
    secondary: '#423528',
    tertiary: '#7B7A64',
    quaternary: '#A8A096',
    light: '#DCC8A4',
    grid: 'rgba(89, 73, 57, 0.12)',
    text: '#796D61',
    tooltip: '#29251F',
    tooltipText: '#FBF9F5',
    area: 'rgba(196, 147, 71, 0.16)',
};

export function useChartPalette(): ChartPalette {
    const { resolvedAppearance } = useAppearance();

    return useMemo(() => {
        const themeFallback =
            resolvedAppearance === 'dark'
                ? { ...fallback, tooltipText: '#EADfCE' }
                : fallback;

        if (typeof window === 'undefined') {
            return themeFallback;
        }

        const styles = getComputedStyle(document.documentElement);
        const value = (name: string, defaultValue: string) =>
            styles.getPropertyValue(name).trim() || defaultValue;

        return {
            primary: value('--chart-1', themeFallback.primary),
            secondary: value('--chart-2', themeFallback.secondary),
            tertiary: value('--chart-3', themeFallback.tertiary),
            quaternary: value('--chart-4', themeFallback.quaternary),
            light: value('--chart-5', themeFallback.light),
            grid: value('--chart-grid', themeFallback.grid),
            text: value('--chart-text', themeFallback.text),
            tooltip: value('--chart-tooltip', themeFallback.tooltip),
            tooltipText: value(
                '--primary-foreground',
                themeFallback.tooltipText,
            ),
            area: value('--chart-area', themeFallback.area),
        };
    }, [resolvedAppearance]);
}
