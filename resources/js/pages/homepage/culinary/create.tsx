import { ItemForm } from '@/components/homepage/item-form';
import { fields } from './fields';
export default function Create() {
    return (
        <ItemForm
            title="Add Dining Destination"
            action="/cms/homepage/culinary/items"
            fields={fields}
        />
    );
}
