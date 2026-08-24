import { Head, router } from '@inertiajs/react';
import { Monitor, Smartphone } from 'lucide-react';
import { useState } from 'react';
import { Button } from '@/components/ui/button';

export default function HomepagePreview({
    publishedUrl,
    draftUrl,
}: {
    publishedUrl: string;
    draftUrl: string;
}) {
    const [mode, setMode] = useState<'desktop' | 'mobile'>('desktop');
    const importDraft = () => {
        const confirmation = window.prompt(
            'Type PUBLISH to replace published content.',
        );

        if (confirmation === 'PUBLISH') {
            router.post('/cms/homepage/import-draft', { confirmation });
        }
    };

    return (
        <>
            <Head title="Homepage Preview" />
            <div className="space-y-6">
                <div className="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <h1 className="font-serif text-3xl font-medium">
                            Homepage Preview
                        </h1>
                        <p className="text-sm text-muted-foreground">
                            Compare published and draft content.
                        </p>
                    </div>
                    <div className="flex flex-wrap items-center gap-3">
                        <div
                            className="inline-flex rounded-lg border border-border bg-muted p-1"
                            role="group"
                            aria-label="Preview device mode"
                        >
                            <Button
                                type="button"
                                size="sm"
                                variant={
                                    mode === 'desktop' ? 'default' : 'ghost'
                                }
                                aria-pressed={mode === 'desktop'}
                                onClick={() => setMode('desktop')}
                            >
                                <Monitor />
                                Desktop
                            </Button>
                            <Button
                                type="button"
                                size="sm"
                                variant={
                                    mode === 'mobile' ? 'default' : 'ghost'
                                }
                                aria-pressed={mode === 'mobile'}
                                onClick={() => setMode('mobile')}
                            >
                                <Smartphone />
                                Mobile
                            </Button>
                        </div>
                        <Button onClick={importDraft}>
                            Import Draft to Publish
                        </Button>
                    </div>
                </div>
                <div
                    className={
                        mode === 'desktop'
                            ? 'space-y-6'
                            : 'flex gap-6 overflow-x-auto pb-4'
                    }
                >
                    <PreviewFrame
                        title="Published"
                        url={publishedUrl}
                        mode={mode}
                    />
                    <PreviewFrame title="Draft" url={draftUrl} mode={mode} />
                </div>
            </div>
        </>
    );
}

function PreviewFrame({
    title,
    url,
    mode,
}: {
    title: string;
    url: string;
    mode: 'desktop' | 'mobile';
}) {
    if (mode === 'mobile') {
        return <MobilePreviewFrame title={title} url={url} />;
    }

    return (
        <section className="overflow-hidden rounded-xl border bg-card">
            <PreviewFrameHeader title={title} url={url} />
            <iframe
                title={title + ' homepage'}
                src={url}
                className="h-[75vh] w-full bg-background"
            />
            <PreviewHelp />
        </section>
    );
}

function MobilePreviewFrame({ title, url }: { title: string; url: string }) {
    return (
        <section className="w-[390px] shrink-0 overflow-hidden rounded-xl border bg-card">
            <PreviewFrameHeader title={title} url={url} />
            <iframe
                title={title + ' mobile homepage'}
                src={url}
                className="h-[75vh] w-full bg-background"
            />
            <PreviewHelp />
        </section>
    );
}

function PreviewFrameHeader({ title, url }: { title: string; url: string }) {
    return (
        <div className="flex items-center justify-between gap-3 border-b px-4 py-3">
            <span className="font-medium">{title}</span>
            <a
                href={url}
                target="_blank"
                rel="noreferrer"
                className="text-xs text-muted-foreground underline-offset-4 hover:text-foreground hover:underline"
            >
                Open preview
            </a>
        </div>
    );
}

function PreviewHelp() {
    return (
        <p className="border-t px-4 py-3 text-xs text-muted-foreground">
            If this frame cannot connect, run <code>npm run dev</code> in{' '}
            <code>frontend-web</code>.
        </p>
    );
}
