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
    const { cartItems, removeCartItem } = useCart();
    const [removingItemId, setRemovingItemId] = useState<string | null>(null);
    const [error, setError] = useState<string | null>(null);

    const getFormattedAmount = (amount: number) => {
        const formattedAmount = amount * exchangeRates[orgCurrency];
        return Intl.NumberFormat(orgCurrency, { style: "currency", currency: orgCurrency }).format(formattedAmount);
    };

    const subtotal = cartItems.reduce((acc, item) => acc + item.price * item.quantity, 0);

    const handleRemoveItem = async (id: string, itemType: string) => {
        try {
            setRemovingItemId(id);
            removeCartItem(id, itemType);
        } catch (err) {
            setError('Failed to remove item. Please try again.');
        } finally {
            setRemovingItemId(null);
        }
    };

    const capitalizeFirstLetter = (word: string) => {
        if (!word) return '';
        return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
    };

    return (
        <>
            <Head title="Checkout" />
            <div className="flex justify-center items-center min-h-screen bg-gray-50 p-4">
                <Card className="w-full max-w-3xl p-8 rounded-lg shadow-xl bg-white">
                    <CardHeader className="flex items-center mb-6 text-white bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow-lg p-4">
                        <HiOutlineShoppingCart className="text-3xl" aria-label="Shopping Cart Icon" />
                        <h1 className="text-2xl font-semibold ml-4">Checkout</h1>
                    </CardHeader>
                    <CardContent>
                        {error && <p className="text-red-500 mb-4">{error}</p>}
                        {cartItems.length > 0 ? (
                            <motion.ul>
                                {cartItems.map((item: CartItem) => (
                                    <motion.li
                                        key={item.id}
                                        className="flex justify-between items-center py-6 border-b border-gray-200"
                                        initial={{ opacity: 0, y: 20 }}
                                        animate={{ opacity: 1, y: 0 }}
                                        transition={{ duration: 0.5 }}
                                    >
                                        <div className="flex items-center">
                                            {item.type === 'module' ? (
                                                <MdOutlineExtension className="text-3xl text-blue-600 mr-4" aria-label="Module Icon" />
                                            ) : (
                                                <HiOutlineDatabase className="text-3xl text-green-600 mr-4" aria-label="Storage Icon" />
                                            )}
                                            <div>
                                                <p className="text-lg font-medium">{item.name}</p>
                                            </div>
                                        </div>
                                        <div className="flex items-center">
                                            <span className="text-lg font-medium mr-6">
                                                {item.type === 'module' ? (
                                                    <Badge variant={'outline'} className="text-gray-700"> {capitalizeFirstLetter(item.frequency)} </Badge>
                                                ) : (<>Quantity: {item.quantity}</>)}
                                            </span>
                                            <span className="text-lg font-medium mr-6">Price: {getFormattedAmount(item.price)}</span>
                                            <div className="flex items-center space-x-2">
                                                <Button
                                                    variant="outline"
                                                    size="sm"
                                                    className="text-red-500 hover:bg-red-100 transition-colors duration-200"
                                                    onClick={() => handleRemoveItem(item.id, item.type)}
                                                    disabled={removingItemId === item.id}
                                                    aria-label={`Remove ${item.name} from cart`}
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
                            <div className="flex items-center justify-center text-center py-12">
                                <div className="text-gray-500">
                                    <MdOutlineRemoveShoppingCart className="text-6xl mx-auto mb-6" aria-label="Empty Cart Icon" />
                                    <p className="text-lg">Your cart is empty! Why not explore some of our great products?</p>
                                    <Link
                                        href='/all-products'
                                        className="mt-4 inline-block bg-blue-600 text-white px-6 py-3 rounded-full shadow-md hover:bg-blue-700 transition-all duration-300"
                                    >
                                        Explore All Apps
                                    </Link>
                                </div>
                            </div>
                        )}
                    </CardContent>
                    {cartItems.length > 0 && (
                        <CardFooter className="flex justify-between items-center p-6 border-t border-gray-200">
                            <Link
                                href='/all-products'
                                className="bg-white text-blue-600 px-6 py-3 rounded-full shadow-lg hover:bg-gray-100 transition-all duration-300"
                            >
                                Explore All Apps
                            </Link>
                            <span className="text-lg font-semibold">Subtotal: {getFormattedAmount(subtotal)}</span>
                            <motion.button
                                className="bg-blue-600 text-white px-8 py-3 rounded-full shadow-lg hover:bg-blue-700 transition-all duration-300"
                                whileHover={{ scale: 1.05 }}
                                whileTap={{ scale: 0.95 }}
                                aria-label="Proceed to Payment"
                            >
                                Proceed to Payment
                            </motion.button>
                        </CardFooter>
                    )}
                </Card>
            </div>
        </>
    );
}
