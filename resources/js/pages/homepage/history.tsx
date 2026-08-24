import { Head, router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';

type Version = {
    id: number;
    version: number;
    action: string;
    created_at: string;
};

export default function HomepageHistory({ versions }: { versions: Version[] }) {
    return (
        <>
            <Head title="Publish History" />
            <div className="space-y-6">
                <div>
                    <h1 className="font-serif text-3xl font-medium">
                        Publish History
                    </h1>
                    <p className="text-sm text-muted-foreground">
                        Rollback points created before draft imports.
                    </p>
                </div>
                <div className="overflow-hidden rounded-xl border bg-card">
                    <table className="w-full text-sm">
                        <thead className="bg-muted/70">
                            <tr>
                                <th className="p-3 text-left">Version</th>
                                <th className="p-3 text-left">Action</th>
                                <th className="p-3 text-left">Created</th>
                                <th className="p-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            {versions.map((version) => (
                                <tr key={version.id} className="border-t">
                                    <td className="p-3">#{version.version}</td>
                                    <td className="p-3">{version.action}</td>
                                    <td className="p-3">
                                        {version.created_at}
                                    </td>
                                    <td className="p-3 text-right">
                                        <Button
                                            variant="outline"
                                            size="sm"
                                            onClick={() =>
                                                confirm(
                                                    `Restore version #${version.version}?`,
                                                ) &&
                                                router.post(
                                                    `/cms/homepage/history/${version.id}/rollback`,
                                                )
                                            }
                                        >
                                            Rollback
                                        </Button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            </div>
        </>
    );
}
