import { router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import type { RecordData } from './field-input';

type Props = {
    title: string;
    base: string;
    items: RecordData[];
    columns: string[];
};

export function CollectionTable({ title, base, items, columns }: Props) {
    return (
        <div className="mx-auto max-w-6xl space-y-6 p-6">
            <div className="flex items-center justify-between">
                <h2 className="text-xl font-semibold">{title}</h2>
                <Button asChild>
                    <a href={base + '/create'}>Add item</a>
                </Button>
            </div>
            <div className="overflow-hidden rounded-md border">
                <table className="w-full text-sm">
                    <thead className="bg-muted">
                        <tr>
                            {columns.map((column) => (
                                <th key={column} className="p-3 text-left">
                                    {column.replaceAll('_', ' ')}
                                </th>
                            ))}
                            <th className="p-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {items.map((item) => (
                            <tr key={item.id} className="border-t">
                                {columns.map((column) => (
                                    <td key={column} className="p-3">
                                        {String(item[column] ?? '')}
                                    </td>
                                ))}
                                <td className="p-3 text-right">
                                    <a
                                        className="underline"
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
