import type { Field } from '@/components/homepage/structured-form';

export const fields: Field[] = [
    { name: 'question', label: 'Question' },
    { name: 'answer', label: 'Answer', type: 'textarea' },
];
