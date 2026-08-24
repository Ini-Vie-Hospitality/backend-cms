import { ItemForm } from '@/components/homepage/item-form';
import { fields } from './fields';
export default function Create() {
    return (
        <ItemForm
            title="Add FAQ"
            action="/cms/homepage/faq/items"
            fields={fields}
        />
    );
}
