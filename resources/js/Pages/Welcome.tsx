import { PageProps } from '@/types';
import { Link, Head, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import { motion } from "framer-motion"

export default function Welcome() {
    const { auth, appName, moduleCategories } = usePage<PageProps>().props;
    const [currentYear, setCurrentYear] = useState<number>(new Date().getFullYear());

    useEffect(() => {
        setCurrentYear(new Date().getFullYear());
    }, []);

    return (
        <>
            <Head title="Welcome" />
            <div className="min-h-screen bg-gray-100 flex flex-col items-center">
                <header className="text-center py-12 bg-white shadow-md w-full">
                    <h1 className="text-5xl font-extrabold text-gray-900 mb-4">Welcome to {appName}</h1>
                    <p className="text-xl text-gray-600 mb-6">
                        Discover our range of powerful tools designed to help you manage and grow your business.
                    </p>
                    <Link
                        href={auth.user ? route('products') : route('get-started')}
                        className="bg-blue-500 text-white px-8 py-4 rounded-lg shadow-lg hover:bg-blue-600 transition duration-300"
                    >
                        {auth.user ? 'All Apps' : 'Get Started'}
                    </Link>
                </header>

                <main className="w-full max-w-7xl px-6">
                    {moduleCategories.map((category) => (
                        <section key={category.name} className="py-12">
                            <h2 className="text-3xl font-semibold text-gray-800 text-center mb-8">
                                {category.name}
                            </h2>
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
                                                    ${module.price.toFixed(2)} / {module.subscription_type}
                                                </span>
                                                <Link
                                                    href={module.url}
                                                    className="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300"
                                                >
                                                    Learn More
                                                </Link>
                                            </div>
                                        </div>
                                    </motion.div>
                                ))}
                            </div>
                        </section>
                    ))}

                    <section className="py-12 bg-gray-200">
                        <h2 className="text-3xl font-semibold text-gray-800 text-center mb-8">Why Choose Us?</h2>
                        <p className="text-lg text-gray-700 text-center mb-6">
                            {appName} offers a range of tools designed to simplify and optimize your business operations.
                            From CRM to finance management, we have everything you need to succeed.
                        </p>
                        <div className="text-center">
                            <Link
                                href="/about"
                                className="bg-blue-500 text-white px-6 py-3 rounded-lg shadow-md hover:bg-blue-600 transition duration-300"
                            >
                                Learn More About Us
                            </Link>
                        </div>
                    </section>
                </main>

                <footer className="bg-gray-800 text-white py-6 w-full text-center">
                    <p>&copy; {currentYear} {appName}. All rights reserved.</p>
                </footer>
            </div>
        </>
    );
}
