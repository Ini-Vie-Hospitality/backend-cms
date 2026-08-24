import { StructuredForm } from '@/components/homepage/structured-form';
export default function BrandIntroductionEdit({
    record,
}: {
    record: Record<string, string>;
}) {
    return (
        <StructuredForm
            title="Brand Introduction"
            action="/cms/homepage/brand-introduction"
            record={record}
            fields={[
                { name: 'title', label: 'Headline' },
                { name: 'quote', label: 'Quote', type: 'textarea' },
                { name: 'word_1', label: 'Background word 1' },
                { name: 'word_2', label: 'Background word 2' },
                {
                    name: 'paragraph_1',
                    label: 'Story paragraph 1',
                    type: 'textarea',
                },
                {
                    name: 'paragraph_2',
                    label: 'Story paragraph 2',
                    type: 'textarea',
                },
                { name: 'image_1', label: 'Image slot 1', type: 'file' },
                { name: 'image_alt_1', label: 'Image 1 alt text' },
                { name: 'image_2', label: 'Image slot 2', type: 'file' },
                { name: 'image_alt_2', label: 'Image 2 alt text' },
                { name: 'image_3', label: 'Image slot 3', type: 'file' },
                { name: 'image_alt_3', label: 'Image 3 alt text' },
            ]}
        />
    );
}
