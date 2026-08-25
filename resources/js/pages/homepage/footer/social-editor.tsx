import { router } from '@inertiajs/react';
import { useState } from 'react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';

const socialIcons = [
    ['facebook', 'Facebook'],
    ['instagram', 'Instagram'],
    ['linkedin', 'LinkedIn'],
    ['youtube', 'YouTube'],
    ['tiktok', 'TikTok'],
] as const;

export type Social = {
    id: number;
    label: string;
    href: string;
    icon: (typeof socialIcons)[number][0];
    sort_order: number;
    is_active: boolean;
};

export function SocialEditor({ social, index }: { social?: Social; index: number }) {
    const [selectedIcon, setSelectedIcon] = useState<Social['icon']>(social?.icon ?? 'instagram');

    function save(event: React.FormEvent<HTMLFormElement>) {
        event.preventDefault();
        const data = new FormData(event.currentTarget);
        data.set('is_active', data.get('is_active') ? '1' : '0');

        if (social) {
data.set('_method', 'PUT');
}

        router.post('/cms/homepage/footer/socials' + (social ? `/${social.id}` : ''), data);
    }

    return (
        <form onSubmit={save} className="grid gap-3 rounded-xl border bg-card p-4 shadow-[0_2px_8px_rgba(44,36,28,0.025)] md:grid-cols-5">
            <Input name="label" placeholder="Platform label" defaultValue={social?.label} required />
            <Input name="href" type="url" placeholder="https://..." defaultValue={social?.href} required />
            <input type="hidden" name="icon" value={selectedIcon} />
            <Select value={selectedIcon} onValueChange={(value) => setSelectedIcon(value as Social['icon'])}>
                <SelectTrigger className="w-full"><SelectValue placeholder="Platform" /></SelectTrigger>
                <SelectContent>
                    {socialIcons.map(([value, label]) => <SelectItem key={value} value={value}>{label}</SelectItem>)}
                </SelectContent>
            </Select>
            <Input name="sort_order" type="number" min="0" defaultValue={social?.sort_order ?? index} required />
            <div className="flex items-center gap-3">
                <label className="flex items-center gap-2 text-sm">
                    <input name="is_active" type="checkbox" defaultChecked={social?.is_active ?? true} /> Active
                </label>
                <Button>{social ? 'Save' : 'Add'}</Button>
                {social && <Button type="button" variant="destructive" onClick={() => router.delete(`/cms/homepage/footer/socials/${social.id}`)}>Delete</Button>}
            </div>
        </form>
    );
}
