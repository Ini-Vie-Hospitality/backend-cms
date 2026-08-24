import type { Field } from '@/components/homepage/structured-form';

export const fields: Field[] = [
    { name: 'external_key', label: 'Slug' },
    { name: 'category', label: 'Category' },
    { name: 'description', label: 'Description', type: 'textarea' },
    { name: 'reading_time', label: 'Reading time' },
    { name: 'image', label: 'Image', type: 'file' },
    { name: 'image_alt', label: 'Image alt text' },
    { name: 'href', label: 'Redirect URL', type: 'url' },
];
