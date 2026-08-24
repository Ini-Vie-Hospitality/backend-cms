import { ItemForm } from '@/components/homepage/item-form';
import { fields } from './fields';
export default function Create() {
    return (
        <ItemForm
            title="Add Journal Story"
            action="/cms/homepage/whats-new/items"
            fields={fields}
        />
    );
}
