import { Head, Link, router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';

type Item = { id: number; title: string; category: string | null; status: string; embedding_ready: boolean; updated_at: string };
type PageSize = '10' | '50' | '100' | 'all';

type Paginator = {
    data: Item[];
    current_page: number;
    last_page: number;
    per_page: PageSize;
    total: number;
    from: number | null;
    to: number | null;
};

const pageSizes: PageSize[] = ['10', '50', '100', 'all'];

export default function KnowledgeIndex({ items }: { items: Paginator }) {
    const navigate = (page: number) => {
        if (items.per_page === 'all' || page < 1 || page > items.last_page || page === items.current_page) {
            return;
        }

        router.get('/cms/concierge/knowledge', { page, per_page: items.per_page }, { preserveState: true, preserveScroll: true });
    };

    const changePageSize = (size: string) => {
        router.get('/cms/concierge/knowledge', { page: 1, per_page: size as PageSize }, { preserveState: true, preserveScroll: true });
    };

    const firstVisiblePage = Math.max(1, Math.min(items.current_page - 2, items.last_page - 4));
    const lastVisiblePage = Math.min(firstVisiblePage + 4, items.last_page);
    const visiblePages = Array.from(
        { length: Math.min(items.last_page, lastVisiblePage) - firstVisiblePage + 1 },
        (_, index) => firstVisiblePage + index,
    );

    return (
        <>
            <Head title="Knowledge Base" />
            <div className="w-full max-w-[1180px] space-y-6">
                <div className="flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <h1 className="font-serif text-3xl font-medium">Knowledge Base</h1>
                        <p className="mt-1 text-sm text-muted-foreground">Manage the facts used by the multilingual concierge.</p>
                    </div>
                    <div className="flex gap-2">
                        <Button variant="outline" onClick={() => router.post('/cms/concierge/knowledge/reindex-all')}>
                            Reindex published
                        </Button>
                        <Button asChild>
                            <Link href="/cms/concierge/knowledge/create">Add knowledge</Link>
                        </Button>
                    </div>
                </div>

                <div className="overflow-x-auto rounded-xl border bg-card">
                    <table className="w-full min-w-[680px] text-sm">
                        <thead className="bg-muted/50">
                            <tr>
                                <th className="p-3 text-left">Title</th>
                                <th className="p-3 text-left">Category</th>
                                <th className="p-3 text-left">Status</th>
                                <th className="p-3 text-left">Index</th>
                                <th className="p-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {items.data.map((item) => (
                                <tr key={item.id} className="border-t">
                                    <td className="p-3 font-medium">{item.title}</td>
                                    <td className="p-3 text-muted-foreground">{item.category || 'General'}</td>
                                    <td className="p-3 capitalize">{item.status}</td>
                                    <td className="p-3">{item.embedding_ready ? 'Ready' : 'Needs index'}</td>
                                    <td className="p-3 text-right">
                                        <Link className="underline" href={`/cms/concierge/knowledge/${item.id}/edit`}>
                                            Edit
                                        </Link>
                                        <button
                                            className="ml-4 text-destructive"
                                            onClick={() => confirm('Delete this knowledge item?') && router.delete(`/cms/concierge/knowledge/${item.id}`)}
                                        >
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            ))}
                            {items.data.length === 0 && (
                                <tr>
                                    <td className="p-8 text-center text-muted-foreground" colSpan={5}>
                                        No knowledge items yet.
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>

                <div className="flex flex-wrap items-center justify-between gap-4">
                    <p className="text-sm text-muted-foreground">
                        {items.from === null ? 'No items' : `${items.from}-${items.to} of ${items.total} items`}
                    </p>

                    <div className="flex flex-wrap items-center gap-4">
                        <div className="flex items-center gap-2">
                            <span className="text-sm text-muted-foreground">Rows</span>
                            <Select value={String(items.per_page)} onValueChange={changePageSize}>
                                <SelectTrigger aria-label="Items per page" className="w-20">
                                    <SelectValue />
                                </SelectTrigger>
                                <SelectContent>
                                    {pageSizes.map((size) => (
                                        <SelectItem key={size} value={size}>
                                            {size === 'all' ? 'All' : size}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                        </div>

                        {items.per_page !== 'all' && (
                            <div className="flex items-center gap-1">
                                <Button variant="outline" size="sm" disabled={items.current_page === 1} onClick={() => navigate(items.current_page - 1)}>
                                    Previous
                                </Button>
                                {visiblePages.map((page) => (
                                    <Button
                                        key={page}
                                        variant={page === items.current_page ? 'default' : 'outline'}
                                        size="sm"
                                        aria-current={page === items.current_page ? 'page' : undefined}
                                        onClick={() => navigate(page)}
                                    >
                                        {page}
                                    </Button>
                                ))}
                                <Button
                                    variant="outline"
                                    size="sm"
                                    disabled={items.current_page === items.last_page}
                                    onClick={() => navigate(items.current_page + 1)}
                                >
                                    Next
                                </Button>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </>
    );
}
