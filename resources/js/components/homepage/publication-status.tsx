import { Label } from '@/components/ui/label';

export function PublicationStatus({ value }: { value?: string | null }) {
    return (
        <div className="space-y-2">
            <Label htmlFor="status">Publication status</Label>
            <select
                id="status"
                name="status"
                defaultValue={value ?? 'draft'}
                className="h-10 w-full rounded-md border border-input bg-card px-3 text-foreground outline-none focus-visible:border-ring focus-visible:ring-2 focus-visible:ring-ring/12"
            >
                <option value="draft">Draft</option>
                <option value="published">Published</option>
            </select>
        </div>
    );
}
