import { useState, useEffect } from "react"
import { CartItem } from "@/types/index"

const sampleCartItems: CartItem[] = [
    {
        id: "1",
        name: "Module A",
        quantity: 2,
        price: 29.99,
        type: "Module",
    },
    {
        id: "2",
        name: "User Seat",
        quantity: 1,
        price: 49.99,
        type: "Seat",
    },
    {
        id: "3",
        name: "Extra Storage",
        quantity: 3,
        price: 19.99,
        type: "Storage",
    },
]

export function useCart() {
    const [cartItems, setCartItems] = useState<CartItem[]>([])

    useEffect(() => {
        setCartItems(sampleCartItems)
    }, [])

    const removeItem = (id: string) => {
        setCartItems(prevItems => prevItems.filter(item => item.id !== id))
    }

    return { cartItems, removeItem }
}
