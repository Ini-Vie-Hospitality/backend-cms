import { StructuredForm } from '@/components/homepage/structured-form';
import { NavbarLinkEditor } from './link-editor';
import type { LinkRow } from './link-editor';

export default function NavbarEdit({
    record,
    links = [],
}: {
    record: Record<string, string>;
    links: LinkRow[];
}) {
    return (
        <>
            <StructuredForm
                title="Navbar Settings"
                action="/cms/homepage/navbar"
                record={record}
                fields={[
                    { name: 'logo', label: 'Logo', type: 'file' },
                    { name: 'logo_alt', label: 'Logo alt text' },
                    { name: 'logo_href', label: 'Logo redirect' },
                    { name: 'book_label', label: 'Book button label' },
                    { name: 'book_href', label: 'Book button redirect' },
                    { name: 'mobile_eyebrow', label: 'Sidebar eyebrow' },
                    { name: 'mobile_open_label', label: 'Open menu label' },
                    { name: 'mobile_close_label', label: 'Close menu label' },
                ]}
            />
            <div className="mx-auto max-w-4xl space-y-4 p-6">
                <h2 className="text-xl font-semibold">
                    Desktop and Mobile Links
                </h2>
                {[...links, undefined].map((link, index) => (
                    <NavbarLinkEditor
                        key={link?.id ?? 'new'}
                        link={link}
                        index={index}
                    />
                ))}
            </div>
        </>
    );
}
