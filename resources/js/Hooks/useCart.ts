import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query'
import axios from 'axios'
import { CartItem } from '@/types/index'

export function useCart() {
    const queryClient = useQueryClient()

    const fetchCartItems = (): Promise<CartItem[]> =>
        axios.get('/get-cart-items').then((response) => response.data)

    const { data: cartItems = [], isLoading: loading, isError, error } = useQuery<CartItem[]>({
        queryKey: ['cartItems'],
        queryFn: fetchCartItems,
        initialData: [],
    })

    const removeItemMutation = useMutation({
        mutationFn: async ({ id, itemType }: { id: string, itemType: string }) => {
            await axios.post('/remove-item-from-cart', {
                item_type: itemType,
                item_id: id,
            })
        },
        onSuccess: (_, { id, itemType }) => {
            queryClient.setQueryData<CartItem[]>(['cartItems'], (oldData) =>
                oldData?.filter(item => item.id !== id || item.type !== itemType)
            )
        },
        onError: () => {
            console.error('Failed to remove item from cart.')
        },
    })

    const removeItem = (id: string, itemType: string) => {
        removeItemMutation.mutate({ id, itemType })
    }

    return { cartItems, loading, error: isError ? error : null, removeItem }
}
