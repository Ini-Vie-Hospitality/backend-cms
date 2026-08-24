import { ItemForm } from '@/components/homepage/item-form';
import { fields } from './fields';
export default function Create() {
    return (
        <ItemForm
            title="Add Property"
            action="/cms/homepage/featured-properties/items"
            fields={fields}
        />
    );
}
