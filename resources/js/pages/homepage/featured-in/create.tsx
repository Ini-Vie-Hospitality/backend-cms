import { ItemForm } from '@/components/homepage/item-form';
import { fields } from './fields';
export default function Create() {
    return (
        <ItemForm
            title="Add Publication Logo"
            action="/cms/homepage/featured-in/items"
            fields={fields}
        />
    );
}
