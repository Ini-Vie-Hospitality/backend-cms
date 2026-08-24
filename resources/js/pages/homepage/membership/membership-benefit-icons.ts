import {
    CalendarHeart,
    Coffee,
    Crown,
    Diamond,
    Flower2,
    Gem,
    Gift,
    Heart,
    ShieldCheck,
    ShoppingBag,
    Sparkles,
    Star,
    Tags,
    TicketPercent,
    Utensils,
    Waves,
} from 'lucide-react';
import type { LucideIcon } from 'lucide-react';

export const membershipBenefitIcons = [
    { value: 'diamond', label: 'Diamond', icon: Diamond },
    { value: 'gift', label: 'Gift', icon: Gift },
    { value: 'shopping-bag', label: 'Shopping bag', icon: ShoppingBag },
    { value: 'tags', label: 'Tags', icon: Tags },
    { value: 'crown', label: 'Crown', icon: Crown },
    { value: 'sparkles', label: 'Sparkles', icon: Sparkles },
    { value: 'heart', label: 'Heart', icon: Heart },
    { value: 'star', label: 'Star', icon: Star },
    { value: 'calendar-heart', label: 'Calendar heart', icon: CalendarHeart },
    { value: 'utensils', label: 'Dining', icon: Utensils },
    { value: 'flower-2', label: 'Flower', icon: Flower2 },
    { value: 'ticket-percent', label: 'Offer ticket', icon: TicketPercent },
    { value: 'shield-check', label: 'Shield check', icon: ShieldCheck },
    { value: 'gem', label: 'Gem', icon: Gem },
    { value: 'coffee', label: 'Coffee', icon: Coffee },
    { value: 'waves', label: 'Wellness', icon: Waves },
] as const satisfies readonly {
    value: string;
    label: string;
    icon: LucideIcon;
}[];

export type MembershipBenefitIconKey =
    (typeof membershipBenefitIcons)[number]['value'];
