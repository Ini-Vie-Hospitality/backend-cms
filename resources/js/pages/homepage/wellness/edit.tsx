import { ItemForm } from '@/components/homepage/item-form';
import { fields } from './fields';
export default function Edit({
    item,
}: {
    item: Record<string, string | number | null>;
}) {
    return (
        <ItemForm
            title="Edit Wellness Escape"
            action={`/cms/homepage/wellness/items/${item.id}`}
            item={item}
            fields={fields}
        />
    );
}
