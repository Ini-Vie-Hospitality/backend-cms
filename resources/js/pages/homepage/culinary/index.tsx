import {
    CollectionTable,
    StructuredForm,
} from '@/components/homepage/structured-form';
export default function CulinaryIndex({
    record,
    items,
}: {
    record: Record<string, string>;
    items: Record<string, string | number | null>[];
}) {
    return (
        <>
            <StructuredForm
                title="Culinary Settings"
                action="/cms/homepage/culinary"
                record={record}
                fields={[
                    { name: 'eyebrow', label: 'Eyebrow' },
                    { name: 'title', label: 'Title' },
                    {
                        name: 'description',
                        label: 'Description',
                        type: 'textarea',
                    },
                    { name: 'scroll_label', label: 'Scroll label' },
                ]}
            />
            <CollectionTable
                title="Dining Destinations"
                base="/cms/homepage/culinary/items"
                items={items}
                columns={['name', 'location', 'status', 'sort_order']}
            />
        </>
    );
}
