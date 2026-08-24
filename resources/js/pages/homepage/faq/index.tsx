import {
    CollectionTable,
    StructuredForm,
} from '@/components/homepage/structured-form';
export default function FaqIndex({
    record,
    items,
}: {
    record: Record<string, string>;
    items: Record<string, string | number | null>[];
}) {
    return (
        <>
            <StructuredForm
                title="FAQ Settings"
                action="/cms/homepage/faq"
                record={record}
                fields={[
                    { name: 'eyebrow', label: 'Eyebrow' },
                    { name: 'title', label: 'Title' },
                    {
                        name: 'description',
                        label: 'Description',
                        type: 'textarea',
                    },
                ]}
            />
            <CollectionTable
                title="Questions"
                base="/cms/homepage/faq/items"
                items={items}
                columns={['question', 'status', 'sort_order']}
            />
        </>
    );
}
