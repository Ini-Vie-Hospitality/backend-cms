import { StructuredForm } from '@/components/homepage/structured-form';
export default function FooterEdit({
    record,
}: {
    record: Record<string, string>;
}) {
    return (
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
    );
}
