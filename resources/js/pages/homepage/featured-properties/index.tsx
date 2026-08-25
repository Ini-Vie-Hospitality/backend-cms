import {
    CollectionTable,
    StructuredForm,
} from '@/components/homepage/structured-form';
export default function FeaturedPropertiesIndex({
    record,
    items,
}: {
    record: Record<string, string>;
    items: Record<string, string | number | null>[];
}) {
    return (
        <>
            <StructuredForm
                title="Featured Properties Settings"
                action="/cms/homepage/featured-properties"
                record={record}
                fields={[
                    { name: 'eyebrow', label: 'Eyebrow' },
                    { name: 'title', label: 'Title' },
                    {
                        name: 'description',
                        label: 'Description',
                        type: 'textarea',
                    },
                    { name: 'default_cta_label', label: 'Default CTA' },
                    { name: 'scroll_label', label: 'Scroll label' },
                ]}
            />
            <CollectionTable
                title="Properties"
                base="/cms/homepage/featured-properties/items"
                items={items}
                columns={[
                    { key: 'image_url', label: 'Image', type: 'image' },
                    'name',
                    'category',
                    'status',
                    'sort_order',
                ]}
            />
        </>
    );
}
