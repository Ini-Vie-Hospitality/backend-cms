import type { Field } from '@/components/homepage/structured-form';

export const fields: Field[] = [
    { name: 'name', label: 'Restaurant name' },
    { name: 'location', label: 'Location' },
    { name: 'eyebrow', label: 'Category' },
    { name: 'description', label: 'Description', type: 'textarea' },
    { name: 'schedule', label: 'Schedule' },
    { name: 'image', label: 'Image', type: 'file' },
    { name: 'image_alt', label: 'Image alt text' },
    { name: 'href', label: 'Redirect URL', type: 'url' },
    { name: 'cta_label', label: 'CTA label' },
];
