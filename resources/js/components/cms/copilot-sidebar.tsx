import { usePage } from '@inertiajs/react';
import { Bot, Check, ClipboardCopy, Send, Wand2 } from 'lucide-react';
import { useCallback, useEffect, useMemo, useState } from 'react';
import { Button } from '@/components/ui/button';
import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetHeader,
    SheetTitle,
} from '@/components/ui/sheet';

type FieldDefinition = {
    label: string;
    type: 'text' | 'textarea' | 'url';
    max_length: number;
};

type TargetDefinition = {
    key: string;
    label: string;
    action: string | null;
    action_prefix: string | null;
    fields: Record<string, FieldDefinition>;
};

type CopilotContext = {
    context: string;
    title: string;
    targets: TargetDefinition[];
};

type Suggestion = {
    field: string;
    label: string;
    value: string;
};

type Message = {
    role: 'user' | 'assistant';
    content: string;
    suggestions?: Suggestion[];
    sources?: string[];
};

type AvailableTarget = TargetDefinition & { form_action: string };

function findForm(action: string): HTMLFormElement | null {
    const targetAction = actionPath(action);

    return (
        Array.from(document.forms).find(
            (form) => formActionPath(form) === targetAction,
        ) ?? null
    );
}

function formActionPath(form: HTMLFormElement): string {
    return actionPath(form.getAttribute('action') ?? '');
}

function actionPath(action: string): string {
    try {
        return new URL(action, window.location.origin).pathname;
    } catch {
        return action;
    }
}

export function CmsCopilotSidebar() {
    const page = usePage();
    const context = page.props.cmsCopilot as CopilotContext | undefined;
    const [availableTargets, setAvailableTargets] = useState<AvailableTarget[]>(
        [],
    );
    const [selectedFormAction, setSelectedFormAction] = useState('');
    const [prompt, setPrompt] = useState('');
    const [messages, setMessages] = useState<Message[]>([]);
    const [isGenerating, setIsGenerating] = useState(false);
    const [error, setError] = useState('');
    const [open, setOpen] = useState(false);

    const discoverTargets = useCallback(() => {
        if (!context) {
            setAvailableTargets([]);
            setSelectedFormAction('');

            return;
        }

        const targets = context.targets.flatMap((target) => {
            return Array.from(document.forms)
                .map(formActionPath)
                .filter(Boolean)
                .filter((action) =>
                    target.action
                        ? action === actionPath(target.action)
                        : target.action_prefix
                          ? action === actionPath(target.action_prefix) ||
                            action.startsWith(
                                `${actionPath(target.action_prefix)}/`,
                            )
                          : false,
                )
                .map((action) => ({ ...target, form_action: action }));
        });

        setAvailableTargets(targets);
        setSelectedFormAction(
            (current) =>
                targets.some((target) => target.form_action === current)
                    ? current
                    : (targets[0]?.form_action ?? ''),
        );
    }, [context]);

    const openCopilot = useCallback(() => {
        discoverTargets();
        setOpen(true);
    }, [discoverTargets]);

    useEffect(() => {
        window.addEventListener('cms-copilot:open', openCopilot);

        return () =>
            window.removeEventListener('cms-copilot:open', openCopilot);
    }, [openCopilot]);

    useEffect(() => {
        const timer = window.setTimeout(discoverTargets, 0);

        return () => window.clearTimeout(timer);
    }, [discoverTargets]);

    useEffect(() => {
        const handleNavigate = () => {
            setMessages([]);
            setPrompt('');
            setError('');
            discoverTargets();
        };

        window.addEventListener('inertia:navigate', handleNavigate);

        return () =>
            window.removeEventListener('inertia:navigate', handleNavigate);
    }, [discoverTargets]);

    const selectedTarget = useMemo(() => {
        return (
            availableTargets.find(
                (target) =>
                    target.form_action === selectedFormAction &&
                    !!findForm(target.form_action),
            ) ?? availableTargets[0]
        );
    }, [availableTargets, selectedFormAction]);

    function currentValues(): Record<string, string> {
        if (!selectedTarget) {
            return {};
        }

        const form = findForm(selectedTarget.form_action);

        if (!form) {
            return {};
        }

        return Object.fromEntries(
            Object.keys(selectedTarget.fields)
                .map((name) => {
                    const input = form.elements.namedItem(name);

                    return input instanceof HTMLInputElement ||
                        input instanceof HTMLTextAreaElement
                        ? [name, input.value]
                        : null;
                })
                .filter((value): value is [string, string] => value !== null),
        );
    }

    const applySuggestion = useCallback(
        function (suggestion: Suggestion) {
            if (!selectedTarget) {
                return;
            }

            const form = findForm(selectedTarget.form_action);
            const input = form?.elements.namedItem(suggestion.field);

            if (
                !(
                    input instanceof HTMLInputElement ||
                    input instanceof HTMLTextAreaElement
                ) ||
                input.type === 'file'
            ) {
                return;
            }

            input.value = suggestion.value;
            input.dispatchEvent(new Event('input', { bubbles: true }));
            input.dispatchEvent(new Event('change', { bubbles: true }));
        },
        [selectedTarget],
    );

    async function copy(value: string) {
        await navigator.clipboard.writeText(value);
    }

    async function generate() {
        const content = prompt.trim();

        if (!content || !selectedTarget || isGenerating) {
            return;
        }

        const userMessage: Message = { role: 'user', content };
        setMessages((current) => [...current, userMessage]);
        setPrompt('');
        setIsGenerating(true);
        setError('');

        try {
            const token = document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute('content');
            const response = await fetch('/cms/copilot/generate', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': token ?? '',
                },
                body: JSON.stringify({
                    context: context?.context,
                    target: selectedTarget.key,
                    target_action: selectedTarget.form_action,
                    prompt: content,
                    history: messages.slice(-8),
                    current_values: currentValues(),
                }),
            });
            const data = (await response.json()) as {
                reply?: string;
                sources?: string[];
                suggestions?: Suggestion[];
                message?: string;
                errors?: Record<string, string[]>;
            };

            if (!response.ok) {
                throw new Error(
                    Object.values(data.errors ?? {}).flat()[0] ??
                        data.message ??
                        'Copilot request failed.',
                );
            }

            setMessages((current) => [
                ...current,
                {
                    role: 'assistant',
                    content: data.reply ?? '',
                    suggestions: data.suggestions ?? [],
                    sources: data.sources ?? [],
                },
            ]);
        } catch (requestError) {
            setError(
                requestError instanceof Error
                    ? requestError.message
                    : 'Copilot request failed.',
            );
        } finally {
            setIsGenerating(false);
        }
    }

    if (!context) {
        return null;
    }

    const latestSuggestions = [...messages]
        .reverse()
        .find((message) => message.suggestions?.length)?.suggestions;

    return (
        <Sheet onOpenChange={setOpen} open={open}>
            <SheetContent
                side="right"
                className="flex w-[min(430px,100vw)] flex-col gap-0 p-0 sm:max-w-[430px]"
            >
                <SheetHeader className="border-b p-4 text-left">
                    <SheetTitle className="flex items-center gap-2">
                        <Bot aria-hidden className="size-5" />
                        CMS Copilot
                    </SheetTitle>
                    <SheetDescription>{context.title}</SheetDescription>
                </SheetHeader>

                <div className="min-h-0 flex-1 space-y-4 overflow-y-auto p-4">
                    {!selectedTarget && (
                        <p className="rounded-lg border border-destructive/40 bg-destructive/10 p-3 text-sm text-destructive">
                            No compatible CMS form was found on this page.
                        </p>
                    )}

                    {availableTargets.length > 1 && (
                        <div className="space-y-1.5">
                            <label
                                className="text-xs font-medium"
                                htmlFor="copilot-target"
                            >
                                Form target
                            </label>
                            <select
                                id="copilot-target"
                                className="w-full rounded-md border bg-background px-3 py-2 text-sm"
                                value={selectedTarget?.form_action ?? ''}
                                onChange={(event) => {
                                    setSelectedFormAction(event.target.value);
                                    setMessages([]);
                                    setPrompt('');
                                    setError('');
                                }}
                            >
                                {availableTargets.map((target) => (
                                    <option
                                        key={`${target.key}-${target.form_action}`}
                                        value={target.form_action}
                                    >
                                        {target.label}
                                    </option>
                                ))}
                            </select>
                        </div>
                    )}

                    {messages.map((message, index) => (
                        <div
                            key={`${message.role}-${index}`}
                            className={
                                message.role === 'user'
                                    ? 'ml-6 rounded-lg border bg-accent/60 p-3 text-sm'
                                    : 'mr-4 rounded-lg border bg-card p-3 text-sm'
                            }
                        >
                            {message.content}
                            {message.sources?.length ? (
                                <p className="mt-2 truncate text-xs text-muted-foreground">
                                    Source: {message.sources.join(', ')}
                                </p>
                            ) : null}
                        </div>
                    ))}

                    {isGenerating && (
                        <p className="mr-4 rounded-lg border bg-card p-3 text-sm">
                            Generating suggestions…
                        </p>
                    )}

                    {error && (
                        <p className="rounded-lg border border-destructive/40 bg-destructive/10 p-3 text-sm text-destructive">
                            {error}
                        </p>
                    )}

                    {latestSuggestions?.length ? (
                        <div className="space-y-3 rounded-xl border p-3">
                            <div className="grid grid-cols-2 gap-2">
                                <Button
                                    type="button"
                                    variant="outline"
                                    size="sm"
                                    onClick={() =>
                                        latestSuggestions.forEach(
                                            applySuggestion,
                                        )
                                    }
                                >
                                    <Check aria-hidden className="size-4" />
                                    Apply all
                                </Button>
                                <Button
                                    type="button"
                                    variant="outline"
                                    size="sm"
                                    onClick={() =>
                                        void copy(
                                            latestSuggestions
                                                .map(
                                                    (item) =>
                                                        `${item.label}: ${item.value}`,
                                                )
                                                .join('\n'),
                                        )
                                    }
                                >
                                    <ClipboardCopy
                                        aria-hidden
                                        className="size-4"
                                    />
                                    Copy all
                                </Button>
                            </div>
                            {latestSuggestions.map((suggestion) => (
                                <div
                                    key={suggestion.field}
                                    className="space-y-2 rounded-lg border p-3"
                                >
                                    <p className="text-xs font-medium text-muted-foreground">
                                        {suggestion.label}
                                    </p>
                                    <p className="text-sm whitespace-pre-wrap">
                                        {suggestion.value}
                                    </p>
                                    <div className="flex gap-2">
                                        <Button
                                            type="button"
                                            size="sm"
                                            onClick={() =>
                                                applySuggestion(suggestion)
                                            }
                                        >
                                            Apply
                                        </Button>
                                        <Button
                                            type="button"
                                            variant="outline"
                                            size="sm"
                                            onClick={() =>
                                                void copy(suggestion.value)
                                            }
                                        >
                                            Copy
                                        </Button>
                                    </div>
                                </div>
                            ))}
                        </div>
                    ) : null}
                </div>

                <div className="border-t p-3">
                    <textarea
                        className="min-h-24 w-full resize-none rounded-md border bg-background p-3 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"
                        placeholder="Ask Copilot to write or improve this form…"
                        value={prompt}
                        onChange={(event) => setPrompt(event.target.value)}
                        onKeyDown={(event) => {
                            if (
                                (event.ctrlKey || event.metaKey) &&
                                event.key === 'Enter'
                            ) {
                                event.preventDefault();
                                void generate();
                            }
                        }}
                    />
                    <Button
                        className="mt-2 w-full"
                        disabled={
                            !prompt.trim() || isGenerating || !selectedTarget
                        }
                        type="button"
                        onClick={() => void generate()}
                    >
                        <Wand2 aria-hidden className="size-4" />
                        Generate
                        <Send aria-hidden className="size-4 opacity-60" />
                    </Button>
                </div>
            </SheetContent>
        </Sheet>
    );
}
