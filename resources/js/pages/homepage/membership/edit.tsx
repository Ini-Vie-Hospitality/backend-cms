import { StructuredForm } from '@/components/homepage/structured-form';
import { MembershipBenefitEditor } from './benefit-editor';
import type { Benefit } from './benefit-editor';

export default function MembershipEdit({
    record,
    benefits = [],
}: {
    record: Record<string, string>;
    benefits: Benefit[];
}) {
    return (
        <>
            <StructuredForm
                title="Membership"
                action="/cms/homepage/membership"
                record={record}
                fields={[
                    { name: 'title', label: 'Title' },
                    { name: 'subtitle', label: 'Subtitle' },
                    {
                        name: 'description',
                        label: 'Description',
                        type: 'textarea',
                    },
                    { name: 'video', label: 'Background video', type: 'file' },
                    { name: 'primary_label', label: 'Primary CTA label' },
                    { name: 'primary_href', label: 'Primary CTA redirect' },
                    { name: 'secondary_label', label: 'Secondary CTA label' },
                    { name: 'secondary_href', label: 'Secondary CTA redirect' },
                ]}
            />
            <div className="mx-auto max-w-4xl space-y-3 p-6">
                <h2 className="text-xl font-semibold">Membership Benefits</h2>
                {[...benefits, undefined].map((benefit, index) => (
                    <MembershipBenefitEditor
                        key={benefit?.id ?? 'new'}
                        benefit={benefit}
                        index={index}
                    />
                ))}
            </div>
        </>
    );
}
