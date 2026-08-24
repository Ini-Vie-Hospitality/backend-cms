import { ItemForm } from '@/components/homepage/item-form';
import { fields } from './fields';
export default function Create() {
    return (
        <ItemForm
            title="Add Wellness Escape"
            action="/cms/homepage/wellness/items"
            fields={fields}
        />
    );
}
