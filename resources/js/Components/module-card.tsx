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
import { useCart } from '@/Hooks/useCart'

interface ModuleProps extends React.HTMLAttributes<HTMLDivElement> {
    frequency: string
    module: ModuleType
    aspectRatio?: 'portrait' | 'square'
    width?: number
    height?: number
}

export function ModuleCard({
    frequency,
    module,
    aspectRatio = 'portrait',
    width,
    height,
    className,
    ...props
}: ModuleProps) {
    const [loadingItemId, setLoadingItemId] = useState<string | null>(null)
    const { cartItems, addCartItem } = useCart()
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
        addCartItem(moduleId, 'module', 1, frequency);
        setLoadingItemId(null)
    }

    const capitalizeFirstLetter = (word: string) => {
        if (!word) return '';
        return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
    };

    const getPricingLabel = (tab: string) => {
        switch (tab) {
            case 'monthly':
                return 'Month';
            case 'annual':
                return 'Year';
            default:
                return capitalizeFirstLetter(tab);
        }
    }

    const getFormattedAmount = (basePrice: number) => {
        let multiplier = 1;
        let discount = 0;

        switch (frequency) {
            case 'annual':
                multiplier = 12;
                discount = 2;
                break;
            default:
                multiplier = 1;
                break;
        }

        const priceInUSD = basePrice * multiplier;
        const discountedPriceInUSD = priceInUSD - basePrice * discount;
        const price = discountedPriceInUSD * exchangeRates[orgCurrency];
        const formattedPrice = Intl.NumberFormat(orgCurrency, { style: "currency", currency: orgCurrency }).format(price);

        return {
            price: formattedPrice,
            discountAmount: Intl.NumberFormat(orgCurrency, { style: "currency", currency: orgCurrency }).format(basePrice * discount * exchangeRates[orgCurrency])
        };
    };

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
                <div className="flex items-center justify-between">
                    <p className="text-sm font-semibold">
                        Price: {getFormattedAmount(module.price).price} / {getPricingLabel(frequency)}
                    </p>
                    {module.is_subscribed ? (
                        <Button
                            size={'sm'}
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
                            size={'sm'}
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
                            size={'sm'}
                            className="flex items-center px-3 py-2 text-sm font-medium rounded-md bg-gray-300 text-gray-700 cursor-not-allowed"
                            disabled
                        >
                            <IoMdCart className="h-4 w-4 mr-2" />
                            In Cart
                        </Button>
                    )}
                </div>
                {frequency === 'annual' && (
                    <span className="text-sm text-green-600">
                        Save {getFormattedAmount(module.price).discountAmount}!
                    </span>
                )}
            </div>
        </div>
    )
}
