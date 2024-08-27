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
import { IoCartOutline, IoCart } from "react-icons/io5"
import { MdOutlineShoppingCartCheckout } from "react-icons/md"
import { motion } from "framer-motion"

export default function CartDropdown() {
    const { cartItems, removeItem } = useCart()
    const itemCount = cartItems.length

    return (
        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <Button variant="outline" size="icon" className="relative flex items-center justify-center p-2">
                    <motion.div
                        initial={{ scale: 1 }}
                        whileHover={{ scale: 1.1 }}
                        transition={{ type: 'spring', stiffness: 300 }}
                    >
                        <IoCart className="h-5 w-5 text-primary" />
                    </motion.div>
                    {itemCount > 0 && (
                        <motion.span
                            initial={{ scale: 0.8 }}
                            animate={{ scale: 1 }}
                            transition={{ type: 'spring', stiffness: 300 }}
                            className="absolute top-0 right-0 flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-600 rounded-full -translate-x-1/2 -translate-y-1/2"
                        >
                            {itemCount}
                        </motion.span>
                    )}
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent className="w-72 p-4 border rounded-lg shadow-lg mr-4">
                <DropdownMenuLabel className="text-lg font-semibold">Your Cart</DropdownMenuLabel>
                <DropdownMenuSeparator className="my-2" />
                {itemCount === 0 ? (
                    <div className="flex flex-col items-center justify-center h-48 text-gray-500">
                        <IoCartOutline className="h-12 w-12 mb-4" />
                        <DropdownMenuItem className="text-center text-sm">Your cart is empty</DropdownMenuItem>
                    </div>
                ) : (
                    <motion.div
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        transition={{ duration: 0.3 }}
                        className="flex flex-col space-y-2"
                    >
                        {cartItems.map((item: CartItem, index) => (
                            <motion.div
                                key={item.id}
                                initial={{ x: 20, opacity: 0 }}
                                animate={{ x: 0, opacity: 1 }}
                                transition={{ duration: 0.5, delay: index * 0.1 }}
                            >
                                <DropdownMenuItem className="flex items-start justify-between p-3 border-b border-gray-200">
                                    <div className="flex-1">
                                        <div className="font-medium text-lg">{item.name}</div>
                                        <div className="text-sm text-gray-500">Qty: {item.quantity} - ${item.price.toFixed(2)}</div>
                                    </div>
                                    <Button
                                        asChild
                                        variant="outline"
                                        size="icon"
                                        className="ml-2 text-red-500 hover:bg-red-100 cursor-pointer"
                                        onClick={() => removeItem(item.id, item.type)}
                                    >
                                        <IoMdClose className="h-4 w-4" />
                                    </Button>
                                </DropdownMenuItem>
                            </motion.div>
                        ))}
                    </motion.div>
                )}
                {itemCount > 0 && (
                    <>
                        <DropdownMenuSeparator className="my-2" />
                        <DropdownMenuItem className="text-center">
                            <Button variant="secondary" className="w-full bg-blue-500 text-white hover:bg-blue-600">
                                <motion.div
                                    initial={{ scale: 1 }}
                                    whileHover={{ scale: 1.1 }}
                                    transition={{ type: 'spring', stiffness: 300 }}
                                    className="flex items-center justify-center"
                                >
                                    <MdOutlineShoppingCartCheckout className="h-4 w-4" />
                                </motion.div>
                                Checkout
                            </Button>
                        </DropdownMenuItem>
                    </>
                )}
            </DropdownMenuContent>
        </DropdownMenu>
    )
}
