import { ItemForm } from '@/components/homepage/item-form';
import { StructuredForm } from '@/components/homepage/structured-form';
type Block = Record<string, string | number | null>;
const fields = [
    { name: 'title', label: 'Title' },
    { name: 'description', label: 'Description', type: 'textarea' as const },
    { name: 'image', label: 'Image', type: 'file' as const },
    { name: 'image_alt', label: 'Image alt text' },
    { name: 'cta_label', label: 'CTA label' },
    { name: 'href', label: 'Redirect URL' },
];
export default function OurStoryEdit({
    record,
    blocks = [],
}: {
    record: Record<string, string>;
    blocks: Block[];
}) {
    return (
        <>
            <StructuredForm
                title="Our Story"
                action="/cms/homepage/our-story"
                record={record}
                fields={[
                    { name: 'title', label: 'Title' },
                    {
                        name: 'description',
                        label: 'Description',
                        type: 'textarea',
                    },
                ]}
            />
            {blocks.map((block) => (
                <ItemForm
                    key={block.id}
                    title={`Story Slot ${block.slot}`}
                    action={`/cms/homepage/our-story/blocks/${block.id}`}
                    item={block}
                    fields={fields}
                />
            ))}
        </>
    );
}
