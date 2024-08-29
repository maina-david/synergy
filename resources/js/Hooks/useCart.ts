import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import axios from 'axios'
import { CartItem } from '@/types/index'

export function useCart() {
    const queryClient = useQueryClient()

    const fetchCartItems = async (): Promise<CartItem[]> => {
        const response = await axios.get('/get-cart-items')
        return response.data
    }

    const { data: cartItems = [], isLoading: cartItemsLoading, isError: isCartItemsError, error: cartItemsError } = useQuery<CartItem[]>({
        queryKey: ['cartItems'],
        queryFn: fetchCartItems,
        initialData: [],
    })

    const addCartItemMutation = useMutation({
        mutationFn: async ({ id, itemType, itemQuantity, frequency }: { id: string, itemType: string, itemQuantity: number, frequency: string }) => {
            await axios.post('/add-item-to-cart', {
                item_type: itemType,
                item_id: id,
                quantity: itemQuantity,
                frequency: frequency
            })
        },
        onMutate: async ({ id, itemType, itemQuantity, frequency }) => {
            await queryClient.cancelQueries({ queryKey: ['cartItems'] })

            const previousCartItems = queryClient.getQueryData<CartItem[]>(['cartItems'])

            queryClient.setQueryData<CartItem[]>(['cartItems'], [
                ...previousCartItems || [],
                {
                    id,
                    name: 'Loading...',
                    quantity: itemQuantity,
                    price: 0,
                    type: itemType,
                    frequency: frequency
                }
            ])

            return { previousCartItems }
        },
        onError: (error, newItem, context) => {
            if (context?.previousCartItems) {
                queryClient.setQueryData(['cartItems'], context.previousCartItems)
            }
            console.error('Failed to add item to cart:', error)
        },
        onSuccess: async () => {
            await queryClient.invalidateQueries({ queryKey: ['cartItems'] })
        },
    })

    const removeCartItemMutation = useMutation({
        mutationFn: async ({ id, itemType }: { id: string, itemType: string }) => {
            await axios.post('/remove-item-from-cart', {
                item_type: itemType,
                item_id: id,
            })
        },
        onMutate: async ({ id, itemType }) => {
            await queryClient.invalidateQueries({ queryKey: ['cartItems'] })

            const previousCartItems = queryClient.getQueryData<CartItem[]>(['cartItems'])

            queryClient.setQueryData<CartItem[]>(['cartItems'], previousCartItems?.filter(item => item.id !== id || item.type !== itemType))

            return { previousCartItems }
        },
        onSuccess: async () => {
            await queryClient.invalidateQueries({ queryKey: ['cartItems'] })
        },
        onError: (error, { id, itemType }, context) => {
            if (context?.previousCartItems) {
                queryClient.setQueryData(['cartItems'], context.previousCartItems)
            }
            console.error('Failed to remove item from cart:', error)
        },
    })

    const addCartItem = (id: string, itemType: string, itemQuantity: number, frequency: string) => {
        addCartItemMutation.mutate({ id, itemType, itemQuantity, frequency })
    }

    const removeCartItem = (id: string, itemType: string) => {
        removeCartItemMutation.mutate({ id, itemType })
    }

    return {
        cartItems,
        cartItemsLoading,
        cartItemsError: isCartItemsError ? cartItemsError : null,
        addCartItem,
        addingCartItem: addCartItemMutation.isPending,
        addCartItemError: addCartItemMutation.isError,
        addCartItemSuccess: addCartItemMutation.isSuccess,
        removeCartItem,
        removingCartItem: removeCartItemMutation.isPending,
        removeCartItemError: removeCartItemMutation.isError,
        removeCartItemSuccess: removeCartItemMutation.isSuccess,
    }
}
