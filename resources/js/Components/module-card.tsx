import { Img } from 'react-image'
import { PlusCircledIcon } from '@radix-ui/react-icons'

import { cn } from '@/lib/utils'
import {
    ContextMenu,
    ContextMenuContent,
    ContextMenuItem,
    ContextMenuSeparator,
    ContextMenuSub,
    ContextMenuSubContent,
    ContextMenuSubTrigger,
    ContextMenuTrigger,
} from '@/Components/ui/context-menu'

import { ModuleType } from '@/types/module'
import { Link } from '@inertiajs/react'

interface ModuleProps extends React.HTMLAttributes<HTMLDivElement> {
    module: ModuleType
    aspectRatio?: 'portrait' | 'square'
    width?: number
    height?: number
}

export function ModuleCard({
    module,
    aspectRatio = 'portrait',
    width,
    height,
    className,
    ...props
}: ModuleProps) {
    return (
        <div className={cn('space-y-3', className)} {...props}>
            <ContextMenu>
                <ContextMenuTrigger>
                    <div className="overflow-hidden rounded-md">
                        <Img
                            src={module.banner}
                            alt={module.name}
                            width={width}
                            height={height}
                            className={cn(
                                'h-auto w-auto object-cover transition-all hover:scale-105',
                                aspectRatio === 'portrait' ? 'aspect-[3/4]' : 'aspect-square'
                            )}
                        />
                    </div>
                </ContextMenuTrigger>
                <ContextMenuContent className="w-40">
                    <ContextMenuItem>
                        <Link href={module.url}>
                            View Details
                        </Link>
                    </ContextMenuItem>
                    <ContextMenuSeparator />
                    <ContextMenuItem>
                        <Link href="#">
                            Subscribe Now
                        </Link>
                    </ContextMenuItem>
                    <ContextMenuSeparator />
                    <ContextMenuItem>
                        <Link href="#">
                            Share
                        </Link>
                    </ContextMenuItem>
                </ContextMenuContent>
            </ContextMenu>
            <div className="space-y-1 text-sm">
                <h3 className="font-medium leading-none">
                    <Link href={module.url} className='hover:underline hover:text-primary'>
                        {module.name}
                    </Link>
                </h3>
                <p className="text-xs text-muted-foreground">{module.description}</p>
                <div className="flex items-center space-x-2">
                    <Img
                        src={module.icon}
                        alt={`${module.name} icon`}
                        width={24}
                        height={24}
                        className="object-contain"
                    />
                    <span className="text-sm capitalize">{module.subscription_type}</span>
                </div>
                <p className="text-sm font-semibold">Price: ${module.price.toFixed(2)}</p>
            </div>
        </div>
    )
}
