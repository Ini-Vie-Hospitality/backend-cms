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
    const videoUrl = record.video_url;

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
            {videoUrl && (
                <div className="w-full max-w-[1180px] space-y-2 rounded-xl border bg-card p-6">
                    <h2 className="text-lg font-semibold">
                        Current background video
                    </h2>
                    <video
                        src={videoUrl}
                        controls
                        preload="metadata"
                        className="max-h-[420px] w-full rounded-md bg-black object-contain"
                    />
                </div>
            )}
            <div className="space-y-3">
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
