import { ItemForm } from '@/components/homepage/item-form';
import { StructuredForm } from '@/components/homepage/structured-form';
type Offer = Record<string, string | number | null>;
const fields = [
    { name: 'display_number', label: 'Display number' },
    { name: 'category', label: 'Category' },
    { name: 'title', label: 'Title' },
    { name: 'description', label: 'Description', type: 'textarea' as const },
    { name: 'image', label: 'Image', type: 'file' as const },
    { name: 'image_alt', label: 'Image alt text' },
    { name: 'href', label: 'Redirect URL' },
];
export default function SpecialOffersEdit({
    record,
    items = [],
}: {
    record: Record<string, string>;
    items: Offer[];
}) {
    return (
        <>
            <StructuredForm
                title="Special Offers"
                action="/cms/homepage/special-offers"
                record={record}
                fields={[
                    { name: 'eyebrow', label: 'Eyebrow' },
                    { name: 'title', label: 'Title' },
                    {
                        name: 'description',
                        label: 'Description',
                        type: 'textarea',
                    },
                    { name: 'all_offers_label', label: 'All offers label' },
                    { name: 'all_offers_href', label: 'All offers redirect' },
                ]}
            />
            {items.map((item) => (
                <ItemForm
                    key={item.id}
                    title={`Offer Slot ${item.slot}`}
                    action={`/cms/homepage/special-offers/items/${item.id}`}
                    item={item}
                    fields={fields}
                />
            ))}
        </>
    );
}
