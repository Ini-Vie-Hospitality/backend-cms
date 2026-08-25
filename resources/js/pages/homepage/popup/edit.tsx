import { StructuredForm } from '@/components/homepage/structured-form';

export default function PopupEdit({
    record,
}: {
    record: Record<string, string>;
}) {
    return (
        <StructuredForm
            title="Website Popup"
            action="/cms/homepage/popup"
            record={record}
            fields={[
                { name: 'image', label: 'Popup image', type: 'image' },
                { name: 'redirect_url', label: 'Redirect URL when clicked' },
            ]}
        />
    );
}
