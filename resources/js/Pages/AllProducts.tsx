import { Head, usePage } from '@inertiajs/react'
import { Separator } from '@/Components/ui/separator'
import { ModuleCard } from '@/Components/module-card'
import { PageProps } from '@/types'

export default function AllProducts() {
    const { modules } = usePage<PageProps>().props;

    console.log('====================================');
    console.log(modules);
    console.log('====================================');

    return (
        <>
            <Head title="Explore All Products" />
            <div className="col-span-3 lg:col-span-4 lg:border-l">
                <div className="h-full px-4 py-6 lg:px-8">
                    <div className="flex items-center justify-between">
                        <div className="space-y-1">
                            <h2 className="text-2xl font-semibold tracking-tight">
                                All Available Apps
                            </h2>
                            <p className="text-sm text-muted-foreground">
                                Explore our range of Apps. Updated regularly.
                            </p>
                        </div>
                    </div>
                    <Separator className="my-4" />
                    <div className="relative">
                        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            {modules.map((module) => (
                                <ModuleCard
                                    key={module.id}
                                    module={module}
                                    className="w-full"
                                    aspectRatio="square"
                                    width={250}
                                    height={330}
                                />
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </>
    )
}
