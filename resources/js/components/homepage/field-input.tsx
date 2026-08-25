import { useEffect, useState } from 'react';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

export type RecordData = Record<string, string | number | boolean | null> & {
    id?: number;
    status?: string;
};
export type Field = {
    name: string;
    label: string;
    type?:
        | 'text'
        | 'url'
        | 'email'
        | 'tel'
        | 'number'
        | 'textarea'
        | 'file'
        | 'image';
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
    const existingPreviewUrl =
        field.type === 'image'
            ? String(
                  record?.[`${field.name}_url`] ??
                      (field.name === 'image' ? record?.image_url : '') ??
                      '',
              )
            : '';
    const previewAlt = String(
        record?.[field.name.replace('image', 'image_alt')] ??
            record?.image_alt ??
            '',
    );
    const [uploadPreviewUrl, setUploadPreviewUrl] = useState('');

    useEffect(() => {
        return () => {
            if (uploadPreviewUrl) {
                URL.revokeObjectURL(uploadPreviewUrl);
            }
        };
    }, [uploadPreviewUrl]);

    function previewUpload(event: React.ChangeEvent<HTMLInputElement>) {
        const file = event.currentTarget.files?.[0];

        setUploadPreviewUrl(file ? URL.createObjectURL(file) : '');
    }

    return (
        <div
            className={
                field.type === 'textarea'
                    ? 'space-y-2 md:col-span-2'
                    : 'space-y-2'
            }
        >
            <Label htmlFor={field.name}>{field.label}</Label>
            {(existingPreviewUrl || uploadPreviewUrl) && (
                <div className="grid gap-3 sm:grid-cols-2">
                    {existingPreviewUrl && (
                        <div className="space-y-1.5">
                            <p className="text-xs font-medium text-muted-foreground">
                                Current image
                            </p>
                            <img
                                src={existingPreviewUrl}
                                alt={previewAlt}
                                className="h-32 w-full rounded-md border bg-muted object-contain"
                            />
                        </div>
                    )}
                    {uploadPreviewUrl && (
                        <div className="space-y-1.5">
                            <p className="text-xs font-medium text-muted-foreground">
                                New image preview
                            </p>
                            <img
                                src={uploadPreviewUrl}
                                alt="Selected upload preview"
                                className="h-32 w-full rounded-md border bg-muted object-contain"
                            />
                        </div>
                    )}
                </div>
            )}
            {field.type === 'textarea' ? (
                <textarea
                    id={field.name}
                    name={field.name}
                    defaultValue={value}
                    className="min-h-28 w-full rounded-md border border-input bg-card p-3 text-sm text-foreground outline-none placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-2 focus-visible:ring-ring/12"
                    required
                />
            ) : (
                <Input
                    id={field.name}
                    name={field.name}
                    type={
                        field.type === 'file' || field.type === 'image'
                            ? 'file'
                            : (field.type ?? 'text')
                    }
                    defaultValue={
                        field.type === 'file' || field.type === 'image'
                            ? undefined
                            : value
                    }
                    accept={
                        field.type === 'image'
                            ? 'image/jpeg,image/png,image/webp,image/avif'
                            : field.type === 'file'
                              ? 'image/jpeg,image/png,image/webp,image/avif,video/mp4,video/webm'
                              : undefined
                    }
                    required={field.type !== 'file' && field.type !== 'image'}
                    onChange={
                        field.type === 'image' ? previewUpload : undefined
                    }
                />
            )}
            {error && <p className="text-sm text-destructive">{error}</p>}
        </div>
    );
}
