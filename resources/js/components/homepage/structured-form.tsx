import { Head, router, usePage } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

type RecordData = Record<string, string | number | boolean | null> & { id?: number; status?: string };
export type Field = { name: string; label: string; type?: 'text' | 'url' | 'email' | 'tel' | 'number' | 'textarea' | 'file' };

type Props = { title: string; action: string; record: RecordData | null; fields: Field[]; submitLabel?: string };

export function StructuredForm({ title, action, record, fields, submitLabel = 'Save changes' }: Props) {
    const errors = usePage().props.errors as Record<string, string>;
    function submit(event: React.FormEvent<HTMLFormElement>) {
        event.preventDefault();
        const data = new FormData(event.currentTarget);
        data.set('_method', 'PUT');
        router.post(action, data, { forceFormData: true });
    }
    return <>
        <Head title={title} />
        <form onSubmit={submit} className="mx-auto max-w-4xl space-y-6 p-6">
            <div><h1 className="text-2xl font-semibold">{title}</h1><p className="mt-1 text-sm text-muted-foreground">Manage the published content shown on the public homepage.</p></div>
            {Object.keys(errors).length > 0 && <div className="rounded-md border border-destructive p-3 text-sm text-destructive">{Object.entries(errors).map(([key,message]) => <p key={key}>{message}</p>)}</div>}
            <div className="grid gap-5 md:grid-cols-2">
                {fields.map(field => <div key={field.name} className={field.type === 'textarea' ? 'space-y-2 md:col-span-2' : 'space-y-2'}>
                    <Label htmlFor={field.name}>{field.label}</Label>
                    {field.type === 'textarea' ? <textarea id={field.name} name={field.name} defaultValue={String(record?.[field.name] ?? '')} className="min-h-28 w-full rounded-md border bg-background p-3 text-sm" required /> : <Input id={field.name} name={field.name} type={field.type === 'file' ? 'file' : field.type ?? 'text'} defaultValue={field.type === 'file' ? undefined : String(record?.[field.name] ?? '')} accept={field.type === 'file' ? 'image/jpeg,image/png,image/webp,image/avif,video/mp4,video/webm' : undefined} required={field.type !== 'file'} />}
                    {errors[field.name] && <p className="text-sm text-destructive">{errors[field.name]}</p>}
                </div>)}
                <div className="space-y-2"><Label htmlFor="status">Publication status</Label><select id="status" name="status" defaultValue={record?.status ?? 'draft'} className="h-10 w-full rounded-md border bg-background px-3"><option value="draft">Draft</option><option value="published">Published</option></select></div>
            </div>
            <Button type="submit">{submitLabel}</Button>
        </form>
    </>;
}

export function CollectionTable({ title, base, items, columns }: { title: string; base: string; items: RecordData[]; columns: string[] }) {
    return <div className="mx-auto max-w-6xl space-y-6 p-6"><div className="flex items-center justify-between"><h2 className="text-xl font-semibold">{title}</h2><Button asChild><a href={`${base}/create`}>Add item</a></Button></div><div className="overflow-hidden rounded-md border"><table className="w-full text-sm"><thead className="bg-muted"><tr>{columns.map(c=><th key={c} className="p-3 text-left">{c.replaceAll('_',' ')}</th>)}<th className="p-3">Actions</th></tr></thead><tbody>{items.map(item=><tr key={item.id} className="border-t">{columns.map(c=><td key={c} className="p-3">{String(item[c] ?? '')}</td>)}<td className="p-3 text-right"><a className="underline" href={`${base}/${item.id}/edit`}>Edit</a><button className="ml-4 text-destructive" onClick={()=>confirm('Delete this item?')&&router.delete(`${base}/${item.id}`)}>Delete</button></td></tr>)}</tbody></table></div></div>;
}
