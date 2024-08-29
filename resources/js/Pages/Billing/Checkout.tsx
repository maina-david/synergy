import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
import { Card, CardContent, CardFooter, CardHeader } from '@/Components/ui/card';
import { useCart } from '@/Hooks/useCart';
import { CartItem, PageProps } from '@/types';
import { Head, Link, usePage } from '@inertiajs/react';
import { motion } from "framer-motion";
import { useState } from 'react';
import { FaSpinner, FaTrash } from 'react-icons/fa';
import { HiOutlineShoppingCart, HiOutlineDatabase } from 'react-icons/hi';
import { MdOutlineRemoveShoppingCart, MdOutlineExtension } from 'react-icons/md';


export default function CheckoutPage() {
    const { orgCurrency, exchangeRates } = usePage<PageProps>().props;
    const { cartItems, removeItem } = useCart();
    const [removingItemId, setRemovingItemId] = useState<string | null>(null);

    const getFormattedAmount = (amount: number) => {
        const formattedAmount = amount * exchangeRates[orgCurrency];
        return Intl.NumberFormat(orgCurrency, { style: "currency", currency: orgCurrency }).format(formattedAmount);
    };

    const subtotal = cartItems.reduce((acc, item) => acc + item.price * item.quantity, 0);

    const handleRemoveItem = async (id: string, itemType: string) => {
        setRemovingItemId(id);
        removeItem(id, itemType);
        setRemovingItemId(null);
    };


    const capitalizeFirstLetter = (word: string) => {
        if (!word) return '';
        return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
    };

    return (
        <>
            <Head title="Checkout" />
            <div className="flex justify-center items-center min-h-screen bg-gray-100">
                <Card className="w-full w-1/2 p-6 rounded-lg shadow-lg bg-white">
                    <CardHeader className="flex items-center mb-4 text-white bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-md">
                        <HiOutlineShoppingCart className="text-4xl text-white" />
                        <h1 className="text-2xl font-bold ml-4">Checkout</h1>
                    </CardHeader>
                    <CardContent>
                        {cartItems.length > 0 ? (
                            <motion.ul>
                                {cartItems.map((item: CartItem) => (
                                    <motion.li
                                        key={item.id}
                                        className="flex justify-between items-center py-4 border-b border-gray-200"
                                        initial={{ opacity: 0, y: 20 }}
                                        animate={{ opacity: 1, y: 0 }}
                                        transition={{ duration: 0.5 }}
                                    >
                                        <div className="flex items-center">
                                            {item.type === 'module' ? (
                                                <MdOutlineExtension className="text-2xl text-blue-600 mr-4" />
                                            ) : (
                                                <HiOutlineDatabase className="text-2xl text-green-600 mr-4" />
                                            )}
                                            <div>
                                                <p className="text-lg font-semibold">{item.name}</p>
                                            </div>
                                        </div>
                                        <div className="flex items-center">
                                            <span className="text-lg font-medium mr-4">
                                                {item.type === 'module' ? (
                                                    <Badge variant={'outline'} className="text-gray-600"> {capitalizeFirstLetter(item.frequency)} </Badge>
                                                ) : (<>Quantity: {item.quantity}</>)}
                                            </span>
                                            <span className="text-lg font-medium mr-4">Price: {getFormattedAmount(item.price)}</span>
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
                                        </div>
                                    </motion.li>
                                ))}
                            </motion.ul>
                        ) : (
                            <div className="flex items-center justify-center">
                                <div className="text-center text-gray-500">
                                    <MdOutlineRemoveShoppingCart className="text-6xl mx-auto mb-4" />
                                    <p>Your cart is empty!</p>
                                </div>
                            </div>
                        )}
                    </CardContent>
                    <CardFooter className="flex justify-between items-center p-4 border-t border-gray-200">
                        <Link
                            href='/all-products'
                            className="bg-white text-blue-600 px-6 py-3 md:px-8 md:py-4 rounded-full shadow-lg hover:bg-gray-100 transition-all duration-300"
                        >
                            Explore All Apps
                        </Link>
                        {cartItems.length > 0 && (
                            <>
                                <span className="text-lg font-medium">Subtotal: {getFormattedAmount(subtotal)}</span>
                                <motion.button
                                    className="bg-blue-600 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-700"
                                    whileHover={{ scale: 1.05 }}
                                    whileTap={{ scale: 0.95 }}
                                >
                                    Proceed to Payment
                                </motion.button>
                            </>
                        )}
                    </CardFooter>
                </Card>
            </div>
        </>
    );
}
