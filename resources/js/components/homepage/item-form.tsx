import { Head, router, usePage } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { FieldInput } from './field-input';
import type { Field, RecordData } from './field-input';
import { FormErrors } from './form-errors';
import { PublicationStatus } from './publication-status';

export function ItemForm({
    title,
    action,
    item,
    fields,
}: {
    title: string;
    action: string;
    item?: RecordData;
    fields: Field[];
}) {
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
            <form onSubmit={submit} className="mx-auto max-w-3xl space-y-6 p-6">
                <h1 className="text-2xl font-semibold">{title}</h1>
                <FormErrors errors={errors} />
                <div className="grid gap-5 md:grid-cols-2">
                    {fields.map((field) => (
                        <FieldInput
                            key={field.name}
                            field={field}
                            record={item}
                            error={errors[field.name]}
                        />
                    ))}
                    <div className="space-y-2">
                        <Label htmlFor="sort_order">Sort order</Label>
                        <Input
                            id="sort_order"
                            name="sort_order"
                            type="number"
                            min="0"
                            defaultValue={String(item?.sort_order ?? 0)}
                            required
                        />
                    </div>
                    <PublicationStatus
                        value={item?.status ? String(item.status) : undefined}
                    />
                </div>
                <Button>Save item</Button>
            </form>
        </>
    );
}
