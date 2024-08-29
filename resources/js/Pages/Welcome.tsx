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
import { Separator } from '@/Components/ui/separator';

const highlights: {
    icon: string;
    title: string;
    description: string;
}[] = [
        { icon: '⚙️', title: 'Customization', description: 'Tailor the tools to fit your exact needs.' },
        { icon: '📈', title: 'Growth Focused', description: 'All the tools you need to grow your business.' },
        { icon: '🔒', title: 'Secure', description: 'Your data is safe with enterprise-grade security.' },
    ];

const reasons: {
    title: string;
    description: string;
}[] = [
        { title: 'All-in-One', description: 'Manage everything in one place.' },
        { title: 'User Friendly', description: 'Intuitive interface for all skill levels.' },
        { title: '24/7 Support', description: 'We are here for you, anytime.' },
    ]

export default function Welcome({ moduleCategories }: PageProps) {
    const { auth, appName, exchangeRates } = usePage<PageProps>().props;
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
            case 'annual':
                multiplier = 10;
                break;
            default:
                multiplier = 1;
                break;
        }
        const priceInUSD = basePrice * multiplier;
        const price = priceInUSD * exchangeRates[selectedCurrency];
        return Intl.NumberFormat(selectedCurrency, { style: "currency", currency: selectedCurrency }).format(price);

    };

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

    return (
        <>
            <Head title="Welcome" />
            <div className="min-h-screen flex flex-col items-center bg-gray-50">
                {/* Header */}
                <motion.header
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 1 }}
                    transition={{ duration: 0.8 }}
                    className="text-center py-16 bg-gradient-to-r from-blue-500 to-purple-600 w-full text-white shadow-lg"
                >
                    <h1 className="text-5xl md:text-6xl font-extrabold mb-4">Welcome to {appName}</h1>
                    <p className="text-lg md:text-xl mb-6">
                        Discover our tools to help you streamline and grow your business effortlessly.
                    </p>
                    <Link
                        href={auth.user ? route('products') : route('get-started')}
                        className="bg-white text-blue-600 px-6 py-3 md:px-8 md:py-4 rounded-full shadow-lg hover:bg-gray-100 transition-all duration-300"
                    >
                        {auth.user ? 'Explore All Apps' : 'Get Started'}
                    </Link>
                </motion.header>

                {/* Currency and Pricing Tabs */}
                <main className="w-full max-w-7xl px-4 sm:px-6">
                    <motion.div
                        initial={{ y: 50, opacity: 0 }}
                        animate={{ y: 0, opacity: 1 }}
                        transition={{ duration: 0.6, delay: 0.4 }}
                        className="m-8 flex flex-col items-center"
                    >
                        {/* Currency Selector */}
                        <div className="mb-6">
                            <Select onValueChange={setSelectedCurrency} defaultValue={selectedCurrency}>
                                <SelectTrigger className="w-[180px]">
                                    <SelectValue placeholder="Select Currency" />
                                </SelectTrigger>
                                <SelectContent position="popper" sideOffset={5}>
                                    <SelectGroup>
                                        {Object.keys(exchangeRates).map((currency) => (
                                            <SelectItem key={currency} value={currency}>
                                                {currency}
                                            </SelectItem>
                                        ))}
                                    </SelectGroup>
                                </SelectContent>
                            </Select>
                        </div>

                        {/* Pricing Tabs */}
                        <div className="flex justify-center mb-12">
                            {['monthly', 'annual'].map((tab) => (
                                <Button
                                    key={tab}
                                    className={`px-4 py-2 md:px-6 md:py-3 mx-1 md:mx-2 text-lg font-semibold ${selectedTab === tab
                                        ? 'bg-blue-600 text-white'
                                        : 'bg-white text-gray-700 border'
                                        } hover:text-white rounded-full transition-all duration-300`}
                                    onClick={() => handleTabChange(tab)}
                                >
                                    {capitalizeFirstLetter(tab)}
                                </Button>
                            ))}
                        </div>

                        {/* Feature Highlights */}
                        <section className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-12 text-center">
                            {highlights.map((feature, index) => (
                                <motion.div
                                    key={index}
                                    initial={{ opacity: 0, y: 50 }}
                                    animate={{ opacity: 1, y: 0 }}
                                    transition={{ duration: 0.5, delay: index * 0.2 }}
                                    className="flex flex-col items-center bg-white shadow-lg rounded-lg p-6"
                                >
                                    <div className="text-4xl md:text-5xl mb-4">{feature.icon}</div>
                                    <h3 className="text-lg md:text-xl font-semibold text-gray-900 mb-2">{feature.title}</h3>
                                    <p className="text-gray-600">{feature.description}</p>
                                </motion.div>
                            ))}
                        </section>

                        {/* Module Categories */}
                        {moduleCategories.map((category) => (
                            <section key={category.name} className="py-12">
                                <h2 className="text-3xl md:text-4xl font-bold text-gray-800 text-center mb-8">{category.name}</h2>
                                <p className="text-center text-gray-600 mb-6">{category.description}</p>
                                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                                    {category.modules.map((module) => (
                                        <motion.div
                                            initial={{ opacity: 0, scale: 0.9 }}
                                            animate={{ opacity: 1, scale: 1 }}
                                            transition={{ duration: 0.5 }}
                                            key={module.id}
                                            className="bg-white border rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden"
                                        >
                                            <img
                                                src={module.banner}
                                                alt={module.name}
                                                className="w-full h-48 object-cover"
                                            />
                                            <div className="p-6">
                                                <h3 className="text-xl font-bold text-gray-900 mb-2">{module.name}</h3>
                                                <p className="text-gray-600 mb-4">{module.description}</p>
                                                <div className="flex items-center justify-between">
                                                    <span className="text-lg font-semibold text-gray-900">
                                                        {calculatePrice(module.price)} / {getPricingLabel(selectedTab)}
                                                    </span>
                                                    <Button className="px-4 py-2 rounded-lg transition-all duration-300">
                                                        <Link href={module.url}>Explore</Link>
                                                    </Button>
                                                </div>
                                            </div>
                                        </motion.div>
                                    ))}
                                </div>
                            </section>
                        ))}

                        <Separator />

                        {/* Why Choose Us */}
                        <section className="py-12 w-full">
                            <h2 className="text-2xl md:text-3xl font-semibold text-gray-800 text-center mb-8">Why Choose Us?</h2>
                            <div className="grid grid-cols-1 sm:grid-cols-3 gap-8 text-center">
                                {reasons.map((reason, index) => (
                                    <div key={index} className="flex flex-col items-center bg-white shadow-lg rounded-lg p-6">
                                        <h3 className="text-lg font-semibold text-gray-900 mb-2">{reason.title}</h3>
                                        <p className="text-gray-600">{reason.description}</p>
                                    </div>
                                ))}
                            </div>
                            <div className="text-center mt-8">
                                <Link
                                    href="/about"
                                    className="bg-blue-600 text-white px-6 py-3 rounded-full shadow-md hover:bg-blue-700 transition-all duration-300"
                                >
                                    Learn More About Us
                                </Link>
                            </div>
                        </section>

                        <Separator />

                    </motion.div>
                </main>

                <footer className="py-12 w-full bg-gray-100 text-center">
                    <p className="text-gray-700">&copy; {currentYear} {appName}. All Rights Reserved.</p>
                </footer>
            </div>
        </>
    );
}
