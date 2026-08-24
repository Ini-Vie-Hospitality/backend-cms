import { Head, router, usePage } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { FieldInput } from './field-input';
import type { Field, RecordData } from './field-input';
import { FormErrors } from './form-errors';
import { PublicationStatus } from './publication-status';

export type { Field } from './field-input';
export { CollectionTable } from './collection-table';

type Props = {
    title: string;
    action: string;
    record: RecordData | null;
    fields: Field[];
    submitLabel?: string;
};

export function StructuredForm({
    title,
    action,
    record,
    fields,
    submitLabel = 'Save changes',
}: Props) {
    const errors = usePage().props.errors as Record<string, string>;

    function submit(event: React.FormEvent<HTMLFormElement>) {
        event.preventDefault();
        const data = new FormData(event.currentTarget);
        data.set('_method', 'PUT');
        router.post(action, data, { forceFormData: true });
    }

    return (
        <>
            <Head title={title} />
            <form
                onSubmit={submit}
                className="mx-auto my-6 max-w-4xl space-y-6 rounded-xl border bg-card p-6 shadow-[0_2px_8px_rgba(44,36,28,0.025)]"
            >
                <div>
                    <h1 className="font-serif text-3xl font-medium tracking-tight">
                        {title}
                    </h1>
                    <p className="mt-1 text-sm text-muted-foreground">
                        Manage the published content shown on the public
                        homepage.
                    </p>
                </div>
                <FormErrors errors={errors} />
                <div className="grid gap-5 md:grid-cols-2">
                    {fields.map((field) => (
                        <FieldInput
                            key={field.name}
                            field={field}
                            record={record}
                            error={errors[field.name]}
                        />
                    ))}
                    <PublicationStatus value={record?.status} />
                </div>
                <Button type="submit">{submitLabel}</Button>
            </form>
        </>
    );
}
