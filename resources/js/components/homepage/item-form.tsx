import { Head, router, usePage } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import type { Field } from './structured-form';

export function ItemForm({ title, action, item, fields }: { title: string; action: string; item?: Record<string, string | number | null>; fields: Field[] }) {
 const errors=usePage().props.errors as Record<string,string>;
 function submit(e:React.FormEvent<HTMLFormElement>){e.preventDefault();const data=new FormData(e.currentTarget);if(item?.id)data.set('_method','PUT');router.post(action,data,{forceFormData:true});}
 return <><Head title={title}/><form onSubmit={submit} className="mx-auto max-w-3xl space-y-6 p-6"><h1 className="text-2xl font-semibold">{title}</h1>{Object.keys(errors).length>0&&<div className="rounded-md border border-destructive p-3 text-sm text-destructive">{Object.values(errors).map(String).join(' ')}</div>}<div className="grid gap-5 md:grid-cols-2">{fields.map(field=><div key={field.name} className={field.type==='textarea'?'space-y-2 md:col-span-2':'space-y-2'}><Label htmlFor={field.name}>{field.label}</Label>{field.type==='textarea'?<textarea id={field.name} name={field.name} defaultValue={String(item?.[field.name]??'')} className="min-h-28 w-full rounded-md border bg-background p-3" required/>:<Input id={field.name} name={field.name} type={field.type==='file'?'file':field.type??'text'} defaultValue={field.type==='file'?undefined:String(item?.[field.name]??'')} required={field.type!=='file'}/>}</div>)}<div className="space-y-2"><Label>Sort order</Label><Input name="sort_order" type="number" min="0" defaultValue={String(item?.sort_order??0)} required/></div><div className="space-y-2"><Label>Status</Label><select name="status" defaultValue={String(item?.status??'draft')} className="h-10 w-full rounded-md border bg-background px-3"><option value="draft">Draft</option><option value="published">Published</option></select></div></div><Button>Save item</Button></form></>;
}
