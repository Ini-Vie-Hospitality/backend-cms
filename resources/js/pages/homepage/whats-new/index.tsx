import {
    CollectionTable,
    StructuredForm,
} from '@/components/homepage/structured-form';
export default function WhatsNewIndex({
    record,
    items,
}: {
    record: Record<string, string>;
    items: Record<string, string | number | null>[];
}) {
    return (
        <>
            <StructuredForm
                title="What's New Settings"
                action="/cms/homepage/whats-new"
                record={record}
                fields={[
                    { name: 'eyebrow', label: 'Eyebrow' },
                    { name: 'title', label: 'Title' },
                    {
                        name: 'description',
                        label: 'Description',
                        type: 'textarea',
                    },
                    { name: 'explore_label', label: 'Explore label' },
                    {
                        name: 'explore_href',
                        label: 'Explore redirect',
                        type: 'url',
                    },
                    { name: 'read_label', label: 'Read label' },
                ]}
            />
            <CollectionTable
                title="Journal Stories"
                base="/cms/homepage/whats-new/items"
                items={items}
                columns={['external_key', 'category', 'status', 'sort_order']}
            />
        </>
    );
}
