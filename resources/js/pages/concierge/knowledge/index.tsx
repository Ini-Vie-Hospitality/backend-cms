import { Head, Link, router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';

type Item = { id: number; title: string; category: string | null; status: string; embedding_ready: boolean; updated_at: string };

export default function KnowledgeIndex({ items }: { items: Item[] }) {
    return (
        <><Head title="Knowledge Base" /><div className="w-full max-w-[1180px] space-y-6">
            <div className="flex flex-wrap items-end justify-between gap-4"><div><h1 className="font-serif text-3xl font-medium">Knowledge Base</h1><p className="mt-1 text-sm text-muted-foreground">Manage the facts used by the multilingual concierge.</p></div><div className="flex gap-2"><Button variant="outline" onClick={() => router.post('/cms/concierge/knowledge/reindex-all')}>Reindex published</Button><Button asChild><Link href="/cms/concierge/knowledge/create">Add knowledge</Link></Button></div></div>
            <div className="overflow-hidden rounded-xl border bg-card"><table className="w-full text-sm"><thead className="bg-muted/50"><tr><th className="p-3 text-left">Title</th><th className="p-3 text-left">Category</th><th className="p-3 text-left">Status</th><th className="p-3 text-left">Index</th><th className="p-3 text-right">Actions</th></tr></thead><tbody>{items.map((item) => <tr key={item.id} className="border-t"><td className="p-3 font-medium">{item.title}</td><td className="p-3 text-muted-foreground">{item.category || 'General'}</td><td className="p-3 capitalize">{item.status}</td><td className="p-3">{item.embedding_ready ? 'Ready' : 'Needs index'}</td><td className="p-3 text-right"><Link className="underline" href={`/cms/concierge/knowledge/${item.id}/edit`}>Edit</Link><button className="ml-4 text-destructive" onClick={() => confirm('Delete this knowledge item?') && router.delete(`/cms/concierge/knowledge/${item.id}`)}>Delete</button></td></tr>)}{items.length === 0 && <tr><td className="p-8 text-center text-muted-foreground" colSpan={5}>No knowledge items yet.</td></tr>}</tbody></table></div>
        </div></>
    );
}
