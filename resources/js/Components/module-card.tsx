import { Img } from 'react-image'
import { cn } from '@/lib/utils'
import {
    ContextMenu,
    ContextMenuContent,
    ContextMenuItem,
    ContextMenuSeparator,
    ContextMenuTrigger,
} from '@/Components/ui/context-menu'
import { ModuleType, PageProps } from '@/types/index'
import { Button } from '@/Components/ui/button'
import { Badge } from '@/Components/ui/badge'
import { IoMdCart, IoIosClose } from 'react-icons/io'
import { useState } from 'react'
import { Link, router, usePage } from '@inertiajs/react'
import { FaSpinner } from 'react-icons/fa'
import { useQueryClient } from '@tanstack/react-query'
import { useCart } from '@/Hooks/useCart'

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
    const queryClient = useQueryClient()
    const { cartItems } = useCart()
    const { orgCurrency, exchangeRates } = usePage<PageProps>().props

    const isInCart = cartItems.some(item => item.id === module.id)


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

    const getFormattedAmount = (amount: number) => {
        const formattedAmount = amount * exchangeRates[orgCurrency];
        return Intl.NumberFormat(orgCurrency, { style: "currency", currency: orgCurrency }).format(formattedAmount);
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
                    <p className="text-sm font-semibold">Price: {getFormattedAmount(module.price)}</p>
                    {module.is_subscribed ? (
                        <Button
                            onClick={() => handleUnsubscribe(module.id)}
                            disabled={loadingItemId === module.id}
                            className="flex items-center px-3 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition-colors"
                        >
                            {loadingItemId === module.id ? (
                                <FaSpinner className='animate-spin mr-2' />
                            ) : (
                                <>
                                    <IoIosClose className="h-4 w-4 mr-2" />
                                    Unsubscribe
                                </>
                            )}
                        </Button>
                    ) : !isInCart ? (
                        <Button
                            className="flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors"
                            onClick={() => handleAddToCart(module.id)}
                            disabled={loadingItemId === module.id}
                        >
                            {loadingItemId === module.id ? (
                                <><FaSpinner className='animate-spin mr-2' />Adding to Cart</>
                            ) : (
                                <>
                                    <IoMdCart className="h-4 w-4 mr-2" />
                                    Add to Cart
                                </>
                            )}
                        </Button>
                    ) : (
                        <Button
                            className="flex items-center px-3 py-2 text-sm font-medium rounded-md bg-gray-300 text-gray-700 cursor-not-allowed"
                            disabled
                        >
                            <IoMdCart className="h-4 w-4 mr-2" />
                            In Cart
                        </Button>
                    )}
                </div>
            </div>
        </div>
    )
}
