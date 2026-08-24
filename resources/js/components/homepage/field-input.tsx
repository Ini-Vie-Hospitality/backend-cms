import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

export type RecordData = Record<string, string | number | boolean | null> & {
    id?: number;
    status?: string;
};
export type Field = {
    name: string;
    label: string;
    type?: 'text' | 'url' | 'email' | 'tel' | 'number' | 'textarea' | 'file';
};

export function FieldInput({
    field,
    record,
    error,
}: {
    field: Field;
    record?: RecordData | null;
    error?: string;
}) {
    const value = String(record?.[field.name] ?? '');

    return (
        <div
            className={
                field.type === 'textarea'
                    ? 'space-y-2 md:col-span-2'
                    : 'space-y-2'
            }
        >
            <Label htmlFor={field.name}>{field.label}</Label>
            {field.type === 'textarea' ? (
                <textarea
                    id={field.name}
                    name={field.name}
                    defaultValue={value}
                    className="min-h-28 w-full rounded-md border bg-background p-3 text-sm"
                    required
                />
            ) : (
                <Input
                    id={field.name}
                    name={field.name}
                    type={
                        field.type === 'file' ? 'file' : (field.type ?? 'text')
                    }
                    defaultValue={field.type === 'file' ? undefined : value}
                    accept={
                        field.type === 'file'
                            ? 'image/jpeg,image/png,image/webp,image/avif,video/mp4,video/webm'
                            : undefined
                    }
                    required={field.type !== 'file'}
                />
            )}
            {error && <p className="text-sm text-destructive">{error}</p>}
        </div>
    );
}
