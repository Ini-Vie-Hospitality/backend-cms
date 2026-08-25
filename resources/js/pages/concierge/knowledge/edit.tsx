import { KnowledgeForm } from '@/components/concierge/knowledge-form';
export default function Edit({ item }: { item: { id: number; title: string; category: string | null; content: string; status: string } }) {
 return <KnowledgeForm title="Edit Knowledge" action={`/cms/concierge/knowledge/${item.id}`} item={item} />; 
}
