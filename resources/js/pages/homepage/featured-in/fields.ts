import type { Field } from '@/components/homepage/structured-form';

export const fields: Field[] = [
    { name: 'image', label: 'Publication logo', type: 'file' },
    { name: 'image_alt', label: 'Publication name' },
];
