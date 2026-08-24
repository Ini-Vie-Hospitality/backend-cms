import { Label } from '@/components/ui/label';

export function PublicationStatus({ value }: { value?: string | null }) {
    return (
        <div className="space-y-2">
            <Label htmlFor="status">Publication status</Label>
            <select
                id="status"
                name="status"
                defaultValue={value ?? 'draft'}
                className="h-10 w-full rounded-md border bg-background px-3"
            >
                <option value="draft">Draft</option>
                <option value="published">Published</option>
            </select>
        </div>
    );
}
