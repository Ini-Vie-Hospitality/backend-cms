import { StructuredForm } from '@/components/homepage/structured-form';
import { SocialEditor } from './social-editor';
import type { Social } from './social-editor';
export default function FooterEdit({
    record,
    socials = [],
}: {
    record: Record<string, string>;
    socials: Social[];
}) {
    return (
        <>
            <StructuredForm
                title="Footer Settings"
                action="/cms/homepage/footer"
                record={record}
                fields={[
                {
                    name: 'background_image',
                    label: 'Background image',
                    type: 'file',
                },
                { name: 'logo', label: 'Logo', type: 'file' },
                { name: 'logo_alt', label: 'Logo alt text' },
                { name: 'summary', label: 'Summary', type: 'textarea' },
                { name: 'office_title', label: 'Office title' },
                {
                    name: 'office_address',
                    label: 'Office address',
                    type: 'textarea',
                },
                {
                    name: 'office_phone_label',
                    label: 'Phone label',
                    type: 'tel',
                },
                { name: 'office_phone_href', label: 'Phone redirect' },
                {
                    name: 'office_email_label',
                    label: 'Email label',
                    type: 'email',
                },
                { name: 'office_email_href', label: 'Email redirect' },
                { name: 'office_map_label', label: 'Map label' },
                { name: 'office_map_href', label: 'Map redirect', type: 'url' },
                { name: 'subscribe_title', label: 'Subscribe title' },
                {
                    name: 'subscribe_description',
                    label: 'Subscribe description',
                    type: 'textarea',
                },
                {
                    name: 'subscribe_action_label',
                    label: 'Subscribe button label',
                },
                { name: 'subscribe_action_href', label: 'Subscribe redirect' },
                { name: 'socials_title', label: 'Social heading' },
                { name: 'policy_label', label: 'Policy label' },
                { name: 'policy_href', label: 'Policy redirect' },
                { name: 'copyright', label: 'Copyright' },
                ]}
            />
            <section className="w-full max-w-[1180px] space-y-4">
                <div>
                    <h2 className="font-serif text-2xl font-medium">Social Media</h2>
                    <p className="mt-1 text-sm text-muted-foreground">Manage the social links shown in the public footer.</p>
                </div>
                {[...socials, undefined].map((social, index) => <SocialEditor key={social?.id ?? 'new'} social={social} index={index} />)}
            </section>
        </>
    );
}
