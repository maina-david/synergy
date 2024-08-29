import { Button } from "@/Components/ui/button";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/ui/dropdown-menu";
import { MdOutlineShoppingCartCheckout, MdStorage } from "react-icons/md";
import { FaCogs, FaTrash, FaSpinner } from "react-icons/fa";
import { IoCartOutline, IoCart } from "react-icons/io5";
import { motion } from "framer-motion";
import { useCart } from "@/Hooks/useCart";
import { useState } from "react";
import { Link, usePage } from "@inertiajs/react";
import { PageProps } from "@/types";
import { cn } from "@/lib/utils";
import { Badge } from "./ui/badge";

const getItemIcon = (type: string) => {
    switch (type) {
        case "module":
            return <FaCogs className="text-primary h-6 w-6" />;
        case "storage":
            return <MdStorage className="text-primary h-6 w-6" />;
        default:
            return <IoCart className="text-primary h-6 w-6" />;
    }
};

export default function CartDropdown() {
    const { orgCurrency, exchangeRates } = usePage<PageProps>().props;
    const { cartItems, removeCartItem } = useCart();
    const itemCount = cartItems.length;
    const [isOpen, setIsOpen] = useState(false);
    const [removingItemId, setRemovingItemId] = useState<string | null>(null);

    const subtotal = cartItems.reduce((acc, item) => acc + item.price * item.quantity, 0);

    const handleRemoveItem = async (id: string, itemType: string) => {
        try {
            setRemovingItemId(id);
            removeCartItem(id, itemType);

        } catch (error) {
            console.log('====================================');
            console.log(error);
            console.log('====================================');
        } finally {
            setRemovingItemId(null);
        }
    };

    const getFormattedAmount = (amount: number) => {
        const formattedAmount = amount * exchangeRates[orgCurrency];
        return Intl.NumberFormat(orgCurrency, { style: "currency", currency: orgCurrency }).format(formattedAmount);
    };

    const capitalizeFirstLetter = (word: string) => {
        if (!word) return '';
        return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
    };

    return (
        <DropdownMenu open={isOpen} onOpenChange={setIsOpen}>
            <DropdownMenuTrigger asChild>
                <Button
                    variant="outline"
                    size="icon"
                    className="relative flex items-center justify-center p-2"
                >
                    <motion.div
                        initial={{ scale: 1 }}
                        whileHover={{ scale: 1.1 }}
                        transition={{ type: "spring", stiffness: 300 }}
                    >
                        <IoCart className="h-5 w-5 text-primary" />
                    </motion.div>
                    {itemCount > 0 && (
                        <motion.span
                            initial={{ scale: 0.8 }}
                            animate={{ scale: 1 }}
                            transition={{ type: "spring", stiffness: 300 }}
                            className="absolute top-0 right-0 flex items-center justify-center w-5 h-5 text-xs font-bold text-white bg-red-600 rounded-full -translate-x-1/2 -translate-y-1/2"
                        >
                            {itemCount}
                        </motion.span>
                    )}
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent className={cn('p-4 border rounded-lg shadow-lg bg-white', itemCount > 0 ? 'w-full' : 'w-80')}>
                <DropdownMenuLabel
                    className="flex items-center justify-between p-4 text-lg font-semibold text-white bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-md"
                >
                    <span>Cart</span>
                    {itemCount > 0 && (
                        <span className="bg-red-600 text-white px-2 py-1 rounded-full text-sm font-medium">
                            {itemCount}
                        </span>
                    )}
                </DropdownMenuLabel>

                <DropdownMenuSeparator className="my-2" />
                {itemCount === 0 ? (
                    <div className="flex flex-col items-center justify-center h-48 text-gray-500">
                        <IoCartOutline className="h-12 w-12 mb-4" />
                        <p className="text-sm font-semibold">Your cart is empty!</p>
                    </div>
                ) : (
                    <motion.div
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        transition={{ duration: 0.3 }}
                        className="flex flex-col space-y-2"
                    >
                        {cartItems.map((item, index) => (
                            <motion.div
                                key={item.id}
                                initial={{ x: 20, opacity: 0 }}
                                animate={{ x: 0, opacity: 1 }}
                                transition={{ duration: 0.5, delay: index * 0.1 }}
                                className="flex items-center justify-between border-b py-2"
                            >
                                <div className="flex items-center space-x-4">
                                    {getItemIcon(item.type)}
                                    <div>
                                        <div className="font-medium">{item.name}</div>
                                        <div className="text-sm text-gray-500">
                                            {item.type === 'module' ? (
                                                <Badge variant={'outline'} className="text-gray-600"> {capitalizeFirstLetter(item.frequency)} </Badge>
                                            ) : (<>Qty: {item.quantity} -</>)}
                                            {getFormattedAmount(item.price)}
                                        </div>
                                    </div>
                                </div>
                                <div className="flex items-center space-x-2">
                                    <Button
                                        variant="outline"
                                        size="sm"
                                        className="text-red-500 hover:bg-red-100"
                                        onClick={() => handleRemoveItem(item.id, item.type)}
                                        disabled={removingItemId == item.id}
                                    >
                                        {removingItemId === item.id ?
                                            <FaSpinner className="animate-spin h-4 w-4 mr-2" /> : <FaTrash className="h-4 w-4 mr-2" />
                                        }
                                        Remove
                                    </Button>
                                </div>
                            </motion.div>
                        ))}
                        <div className="flex justify-between mt-4 font-semibold text-lg">
                            <span>Subtotal:</span>
                            <span>{getFormattedAmount(subtotal)}</span>
                        </div>
                    </motion.div>
                )}
                {itemCount > 0 && (
                    <>
                        <DropdownMenuSeparator className="my-2" />
                        <div className="text-center">
                            <Link href="/checkout">
                                <Button
                                    variant="secondary"
                                    className="w-full bg-blue-500 text-white hover:bg-blue-600"
                                    onClick={() => setIsOpen(false)}
                                >
                                    <motion.div
                                        initial={{ scale: 1 }}
                                        whileHover={{ scale: 1.1 }}
                                        transition={{ type: "spring", stiffness: 300 }}
                                        className="flex items-center justify-center"
                                    >

                                        <MdOutlineShoppingCartCheckout className="h-4 w-4 mr-2" />
                                        Checkout
                                    </motion.div>
                                </Button>
                            </Link>
                        </div>
                    </>
                )}
            </DropdownMenuContent>
        </DropdownMenu>
    );
}
