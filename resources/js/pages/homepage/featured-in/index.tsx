import {
    CollectionTable,
    StructuredForm,
} from '@/components/homepage/structured-form';
export default function FeaturedInIndex({
    record,
    items,
}: {
    record: Record<string, string>;
    items: Record<string, string | number | null>[];
}) {
    return (
        <>
            <StructuredForm
                title="Featured In Settings"
                action="/cms/homepage/featured-in"
                record={record}
                fields={[{ name: 'title', label: 'Title' }]}
            />
            <CollectionTable
                title="Publication Logos"
                base="/cms/homepage/featured-in/items"
                items={items}
                columns={[
                    {
                        key: 'image_url',
                        label: 'Image',
                        type: 'image',
                        fit: 'contain',
                    },
                    'image_alt',
                    'status',
                    'sort_order',
                ]}
            />
        </>
    );
}
