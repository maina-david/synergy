import { PageProps } from '@/types';
import { Link, Head, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { motion } from 'framer-motion';
import { Button } from '@/Components/ui/button';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/Components/ui/select"

export default function Welcome({ exchangeRates }: any) {
    const { auth, appName, moduleCategories } = usePage<PageProps>().props;
    const [currentYear, setCurrentYear] = useState<number>(new Date().getFullYear());
    const [selectedTab, setSelectedTab] = useState<string>('monthly');
    const [selectedCurrency, setSelectedCurrency] = useState<string>('USD');

    useEffect(() => {
        setCurrentYear(new Date().getFullYear());
    }, []);

    const handleTabChange = (tab: string) => {
        setSelectedTab(tab);
    };

    const calculatePrice = (basePrice: number) => {
        let multiplier = 1;
        switch (selectedTab) {
            case 'quarterly':
                multiplier = 3;
                break;
            case 'biannual':
                multiplier = 6;
                break;
            case 'annual':
                multiplier = 12;
                break;
            default:
                multiplier = 1;
                break;
        }
        const priceInUSD = basePrice * multiplier;
        return priceInUSD * exchangeRates[selectedCurrency];
    };

    const capitalizeFirstLetter = (word: string) => {
        if (!word) return '';
        return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
    };

    return (
        <>
            <Head title="Welcome" />
            <div className="min-h-screen flex flex-col items-center bg-gray-50">
                {/* Enhanced Header */}
                <header className="text-center py-16 bg-gradient-to-r from-blue-500 to-purple-600 w-full text-white shadow-lg">
                    <h1 className="text-6xl font-extrabold mb-4">Welcome to {appName}</h1>
                    <p className="text-xl mb-6">
                        Discover our tools to help you streamline and grow your business effortlessly.
                    </p>
                    <Link
                        href={auth.user ? route('products') : route('get-started')}
                        className="bg-white text-blue-600 px-8 py-4 rounded-lg shadow-lg hover:bg-gray-100 transition duration-300"
                    >
                        {auth.user ? 'Explore All Apps' : 'Get Started'}
                    </Link>
                </header>

                {/* Currency and Pricing Tabs */}
                <main className="w-full max-w-7xl px-6">
                    <div className="m-8 flex flex-col items-center">
                        {/* Currency Selector */}
                        <div className="mb-6">
                            <Select onValueChange={setSelectedCurrency} defaultValue={selectedCurrency}>
                                <SelectTrigger className="w-[180px]">
                                    <SelectValue placeholder="Select Currency" />
                                </SelectTrigger>
                                <SelectContent position="popper" sideOffset={5}>
                                    <SelectGroup>
                                        {Object.keys(exchangeRates).map((currency) => (
                                            <SelectItem
                                                key={currency}
                                                value={currency}
                                            >
                                                {currency}
                                            </SelectItem>
                                        ))}
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </div>

                        {/* Pricing Tabs */}
                        <div className="flex justify-center mb-12">
                            {['monthly', 'quarterly', 'biannual', 'annual'].map((tab) => (
                                <Button
                                    key={tab}
                                    className={`px-6 py-3 mx-2 text-lg font-semibold ${selectedTab === tab
                                        ? 'bg-blue-600 text-white'
                                        : 'bg-white text-gray-700 border'
                                        } rounded-full transition duration-300`}
                                    onClick={() => handleTabChange(tab)}
                                >
                                    {capitalizeFirstLetter(tab)}
                                </Button>
                            ))}
                        </div>

                        {/* Feature Highlights */}
                        <section className="grid grid-cols-1 sm:grid-cols-3 gap-8 mb-12 text-center">
                            {[
                                { icon: '⚙️', title: 'Customization', description: 'Tailor the tools to fit your exact needs.' },
                                { icon: '📈', title: 'Growth Focused', description: 'All the tools you need to grow your business.' },
                                { icon: '🔒', title: 'Secure', description: 'Your data is safe with enterprise-grade security.' },
                            ].map((feature, index) => (
                                <div key={index} className="flex flex-col items-center">
                                    <div className="text-5xl mb-4">{feature.icon}</div>
                                    <h3 className="text-xl font-semibold text-gray-900 mb-2">{feature.title}</h3>
                                    <p className="text-gray-600">{feature.description}</p>
                                </div>
                            ))}
                        </section>

                        {/* Module Categories */}
                        {moduleCategories.map((category) => (
                            <section key={category.name} className="py-12">
                                <h2 className="text-4xl font-bold text-gray-800 text-center mb-8">{category.name}</h2>
                                <p className="text-center text-gray-600 mb-6">{category.description}</p>
                                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                    {category.modules.map((module) => (
                                        <motion.div
                                            initial={{ opacity: 0, scale: 0.5 }}
                                            animate={{ opacity: 1, scale: 1 }}
                                            transition={{ duration: 0.5 }}
                                            key={module.id}
                                            className="bg-white border rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300"
                                        >
                                            <img
                                                src={module.banner}
                                                alt={module.name}
                                                className="w-full h-48 object-cover"
                                            />
                                            <div className="p-6">
                                                <h3 className="text-2xl font-bold text-gray-900 mb-2">{module.name}</h3>
                                                <p className="text-gray-600 mb-4">{module.description}</p>
                                                <div className="flex items-center justify-between">
                                                    <span className="text-xl font-semibold text-gray-900">
                                                        {selectedCurrency} {calculatePrice(module.price).toFixed(2)} / {capitalizeFirstLetter(selectedTab)}
                                                    </span>
                                                    <Button className="px-4 py-2 rounded-lg transition duration-300">
                                                        <Link href={module.url}>Learn More</Link>
                                                    </Button>
                                                </div>
                                            </div>
                                        </motion.div>
                                    ))}
                                </div>
                            </section>
                        ))}

                        {/* Why Choose Us */}
                        <section className="py-12 bg-gray-200">
                            <h2 className="text-3xl font-semibold text-gray-800 text-center mb-8">Why Choose Us?</h2>
                            <div className="grid grid-cols-1 sm:grid-cols-3 gap-8 text-center">
                                {[
                                    { title: 'All-in-One', description: 'Manage everything in one place.' },
                                    { title: 'User Friendly', description: 'Intuitive interface for all skill levels.' },
                                    { title: '24/7 Support', description: 'We are here for you, anytime.' },
                                ].map((reason, index) => (
                                    <div key={index} className="flex flex-col items-center">
                                        <h3 className="text-xl font-semibold text-gray-900 mb-2">{reason.title}</h3>
                                        <p className="text-gray-600">{reason.description}</p>
                                    </div>
                                ))}
                            </div>
                            <div className="text-center mt-8">
                                <Link
                                    href="/about"
                                    className="bg-blue-600 text-white px-6 py-3 rounded-lg shadow-md hover:bg-blue-700 transition duration-300"
                                >
                                    Learn More About Us
                                </Link>
                            </div>
                        </section>
                    </div>
                </main>

                <footer className="bg-gray-800 text-white py-6 w-full text-center">
                    <p>&copy; {currentYear} {appName}. All rights reserved.</p>
                </footer>
            </div>
        </>
    );
}
