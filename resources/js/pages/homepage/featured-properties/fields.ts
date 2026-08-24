import type { Field } from '@/components/homepage/structured-form';

export const fields: Field[] = [
    { name: 'name', label: 'Property name' },
    { name: 'category', label: 'Category' },
    { name: 'description', label: 'Description', type: 'textarea' },
    { name: 'image', label: 'Image', type: 'file' },
    { name: 'image_alt', label: 'Image alt text' },
    { name: 'href', label: 'Redirect URL', type: 'url' },
    { name: 'cta_label', label: 'CTA label' },
];
