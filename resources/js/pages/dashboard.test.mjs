import assert from 'node:assert/strict';
import test from 'node:test';
import { readFile } from 'node:fs/promises';

const source = await readFile(new URL('./dashboard.tsx', import.meta.url), 'utf8');

test('dashboard renders GA4 props with native charts and tables', () => {
    assert.match(source, /analytics: AnalyticsData/);
    assert.match(source, /sessions over time/i);
    assert.match(source, /Visitors by device/);
    assert.match(source, /Traffic sources/);
    assert.match(source, /Top pages/);
    assert.match(source, /Events/);
    assert.doesNotMatch(source, /datastudio\.google\.com|iframe/);
});
