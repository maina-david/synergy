import { Button } from "@/Components/ui/button"
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu"
import { IoMdClose } from "react-icons/io"
import { useCart } from "@/Hooks/useCart"
import { CartItem } from "@/types/index"
import { IoCart } from "react-icons/io5";
import { MdOutlineShoppingCartCheckout } from "react-icons/md";

export default function CartDropdown() {
    const { cartItems, removeItem } = useCart()
    const itemCount = cartItems.length

    return (
        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <Button variant="outline" size="icon" className="relative flex items-center justify-center p-2">
                    <IoCart className="h-5 w-5 text-primary" />
                    {itemCount > 0 && (
                        <span className="absolute top-0 right-0 flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-600 rounded-full -translate-x-1/2 -translate-y-1/2">
                            {itemCount}
                        </span>
                    )}
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent className="w-72 p-4 border rounded-lg shadow-lg mr-4">
                <DropdownMenuLabel>Your Cart</DropdownMenuLabel>
                <DropdownMenuSeparator className="my-2" />
                {itemCount === 0 ? (
                    <DropdownMenuItem className="text-center text-sm text-gray-500">Your cart is empty</DropdownMenuItem>
                ) : (
                    <div className="flex flex-col space-y-2">
                        {cartItems.map((item: CartItem) => (
                            <DropdownMenuItem key={item.id} className="flex items-start justify-between p-3 border-b border-gray-200">
                                <div className="flex-1">
                                    <div className="font-medium text-lg">{item.name}</div>
                                    <div className="text-sm text-gray-500">Qty: {item.quantity} - ${item.price.toFixed(2)}</div>
                                </div>
                                <Button
                                    asChild
                                    variant="outline"
                                    size="icon"
                                    className="ml-2 text-red-500 hover:bg-red-100 cursor-pointer"
                                    onClick={() => removeItem(item.id)}
                                >
                                    <IoMdClose className="h-4 w-4" />
                                </Button>
                            </DropdownMenuItem>
                        ))}
                    </div>
                )}
                <DropdownMenuSeparator className="my-2" />
                <DropdownMenuItem className="text-center">
                    <Button variant="secondary" className="w-full bg-blue-500 text-white hover:bg-blue-600">
                        <MdOutlineShoppingCartCheckout className="h-4 w-4" />
                        Checkout
                    </Button>
                </DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenu>
    )
}
