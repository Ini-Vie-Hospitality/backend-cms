import { Head, router, usePage } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

type Item = { id?: number; title?: string; category?: string | null; content?: string; status?: string };

export function KnowledgeForm({ title, action, item }: { title: string; action: string; item?: Item }) {
    const errors = usePage().props.errors as Record<string, string>;
    function submit(event: React.FormEvent<HTMLFormElement>) {
        event.preventDefault();
        const data = new FormData(event.currentTarget);

        if (item?.id) {
data.set('_method', 'PUT');
}

        router.post(action, data, { forceFormData: true });
    }

    return (
        <>
            <Head title={title} />
            <form onSubmit={submit} className="w-full max-w-[900px] space-y-6 rounded-xl border bg-card p-6 shadow-sm">
                <div><h1 className="font-serif text-3xl font-medium tracking-tight">{title}</h1><p className="mt-1 text-sm text-muted-foreground">Concierge answers use published knowledge only.</p></div>
                <div className="grid gap-5 md:grid-cols-2">
                    <div className="space-y-2"><Label htmlFor="title">Title</Label><Input id="title" name="title" defaultValue={item?.title ?? ''} required />{errors.title && <p className="text-sm text-destructive">{errors.title}</p>}</div>
                    <div className="space-y-2"><Label htmlFor="category">Category</Label><Input id="category" name="category" defaultValue={item?.category ?? ''} placeholder="Stays, wellness, dining..." />{errors.category && <p className="text-sm text-destructive">{errors.category}</p>}</div>
                    <div className="space-y-2 md:col-span-2"><Label htmlFor="content">Answer source</Label><textarea id="content" name="content" defaultValue={item?.content ?? ''} className="min-h-56 w-full rounded-md border border-input bg-card p-3 text-sm outline-none focus-visible:border-ring focus-visible:ring-2 focus-visible:ring-ring/12" required />{errors.content && <p className="text-sm text-destructive">{errors.content}</p>}</div>
                    <div className="space-y-2"><Label htmlFor="status">Status</Label><select id="status" name="status" defaultValue={item?.status ?? 'draft'} className="h-10 w-full rounded-md border border-input bg-card px-3 text-foreground"><option value="draft">Draft</option><option value="published">Published</option></select>{errors.status && <p className="text-sm text-destructive">{errors.status}</p>}</div>
                </div>
                <Button type="submit">Save and index</Button>
            </form>
        </>
    );
}
