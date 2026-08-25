import { router } from '@inertiajs/react';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import type { RecordData } from './field-input';

type Props = {
    title: string;
    base: string;
    items: RecordData[];
    columns: Column[];
};

type Column =
    | string
    | {
          key: string;
          label: string;
          type: 'image';
          fit?: 'cover' | 'contain';
      };

function TableValue({
    column,
    value,
    imageAlt,
    imageFit,
}: {
    column: string;
    value: RecordData[string];
    imageAlt?: RecordData[string];
    imageFit?: 'cover' | 'contain';
}) {
    if (column === 'image_url') {
        return value ? (
            <img
                src={String(value)}
                alt={String(imageAlt ?? '')}
                className={`h-14 w-20 rounded-md border bg-muted ${imageFit === 'contain' ? 'object-contain' : 'object-cover'}`}
            />
        ) : (
            <span className="text-muted-foreground">—</span>
        );
    }

    if (column !== 'status') {
        return String(value ?? '');
    }

    const status = String(value ?? 'hidden');
    const variant =
        status === 'published'
            ? 'success'
            : status === 'draft'
              ? 'warning'
              : 'hidden';

    return <Badge variant={variant}>{status}</Badge>;
}

export function CollectionTable({ title, base, items, columns }: Props) {
    return (
        <div className="w-full max-w-[1180px] space-y-6">
            <div className="flex items-center justify-between">
                <h2 className="font-serif text-2xl font-medium">{title}</h2>
                <Button asChild>
                    <a href={base + '/create'}>Add item</a>
                </Button>
            </div>
            <div className="overflow-hidden rounded-xl border bg-card shadow-[0_2px_8px_rgba(44,36,28,0.025)]">
                <table className="w-full text-sm">
                    <thead className="bg-muted/70 text-xs tracking-[0.08em] text-muted-foreground uppercase">
                        <tr>
                            {columns.map((column) => (
                                <th
                                    key={
                                        typeof column === 'string'
                                            ? column
                                            : column.key
                                    }
                                    className="p-3 text-left"
                                >
                                    {typeof column === 'string'
                                        ? column.replaceAll('_', ' ')
                                        : column.label}
                                </th>
                            ))}
                            <th className="p-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {items.map((item) => (
                            <tr
                                key={item.id}
                                className="border-t transition-colors hover:bg-accent/70"
                            >
                                {columns.map((column) => {
                                    const key =
                                        typeof column === 'string'
                                            ? column
                                            : column.key;

                                    return (
                                        <td key={key} className="p-3">
                                            <TableValue
                                                column={key}
                                                value={item[key]}
                                                imageAlt={item.image_alt}
                                                imageFit={
                                                    typeof column === 'string'
                                                        ? undefined
                                                        : column.fit
                                                }
                                            />
                                        </td>
                                    );
                                })}
                                <td className="p-3 text-right">
                                    <a
                                        className="text-secondary-foreground underline decoration-brand-accent/45 underline-offset-4 hover:decoration-brand-accent"
                                        href={base + '/' + item.id + '/edit'}
                                    >
                                        Edit
                                    </a>
                                    <button
                                        className="ml-4 text-destructive"
                                        onClick={() =>
                                            confirm('Delete this item?') &&
                                            router.delete(base + '/' + item.id)
                                        }
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
        </div>
    );
}
