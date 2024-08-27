import { Img } from 'react-image'
import { cn } from '@/lib/utils'
import {
    ContextMenu,
    ContextMenuContent,
    ContextMenuItem,
    ContextMenuSeparator,
    ContextMenuTrigger,
} from '@/Components/ui/context-menu'
import { ModuleType } from '@/types/index'
import { Button } from '@/Components/ui/button'
import { Badge } from '@/Components/ui/badge'
import { IoMdCart, IoIosClose } from 'react-icons/io'
import { useState } from 'react'
import { Link, router } from '@inertiajs/react'
import { FaSpinner } from 'react-icons/fa'
import { useQueryClient } from '@tanstack/react-query'

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
    const [loadingItemId, setLoadingItemId] = useState<string | null>(null)
    const [processing, setProcessing] = useState<boolean>(false)
    const queryClient = useQueryClient()
    
    const handleUnsubscribe = async (moduleId: string) => {
        setLoadingItemId(moduleId)
        router.visit(route('module.unsubscribe', { module: moduleId }), {
            method: 'post',
            onError: () => {
                setLoadingItemId(null)
            },
            onFinish: () => {
                setLoadingItemId(null)
            },
        })
    }

    const handleAddToCart = async (moduleId: string) => {
        setLoadingItemId(moduleId)
        router.visit('/add-item-to-cart', {
            method: 'post',
            data: {
                item_type: 'module',
                item_id: moduleId,
                quantity: 1
            },
            replace: false,
            preserveState: true,
            preserveScroll: true,
            onError: () => {
                setLoadingItemId(null)
            },
            onFinish: () => {
                queryClient.invalidateQueries({ queryKey: ['cartItems'] })
                setLoadingItemId(null)
            },
        })
    }

    return (
        <div className={cn('space-y-3', className)} {...props}>
            <ContextMenu>
                <ContextMenuTrigger>
                    <div className="relative overflow-hidden rounded-md">
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
                        <Badge
                            variant={module.is_subscribed ? 'secondary' : 'destructive'}
                            className="absolute top-2 right-2 text-xs px-2 py-1"
                        >
                            {module.is_subscribed ? 'Subscribed' : 'Not Subscribed'}
                        </Badge>
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
                        <Link href="/share-module">
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
                <div className="flex items-center justify-between">
                    <p className="text-sm font-semibold">Price: ${module.price.toFixed(2)}</p>
                    {module.is_subscribed ? (
                        <Button
                            onClick={() => handleUnsubscribe(module.id)}
                            disabled={loadingItemId === module.id || processing}
                            className="flex items-center px-3 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition-colors"
                        >
                            {loadingItemId === module.id || processing ? (
                                <FaSpinner className='animate-spin mr-2' />
                            ) : (
                                <>
                                    <IoIosClose className="h-4 w-4 mr-2" />
                                    Unsubscribe
                                </>
                            )}
                        </Button>
                    ) : (
                        <Button
                            className="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors"
                            onClick={() => handleAddToCart(module.id)}
                            disabled={loadingItemId === module.id}
                        >
                            {loadingItemId === module.id ? (
                                <FaSpinner className='animate-spin mr-2' />
                            ) : (
                                <>
                                    <IoMdCart className="h-4 w-4 mr-2" />
                                    Add to Cart
                                </>
                            )}
                        </Button>
                    )}
                </div>
            </div>
        </div>
    )
}
