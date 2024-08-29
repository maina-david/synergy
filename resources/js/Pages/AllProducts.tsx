import { Head, usePage } from '@inertiajs/react';
import { Separator } from '@/Components/ui/separator';
import { ModuleCard } from '@/Components/module-card';
import { PageProps } from '@/types';
import { motion } from "framer-motion";
import { useEffect, useRef, useState } from 'react';
import { IoIosArrowForward } from "react-icons/io";
import { Button } from '@/Components/ui/button';
import { ScrollArea } from "@/Components/ui/scroll-area"

export default function AllProducts() {
    const { moduleCategories } = usePage<PageProps>().props;
    const [activeCategory, setActiveCategory] = useState<string | null>(moduleCategories.length > 0 ? moduleCategories[0].id : null);
    const sectionRefs = useRef<Record<string, HTMLDivElement | null>>({});
    const [selectedTab, setSelectedTab] = useState<string>('monthly');

    const handleTabChange = (tab: string) => {
        setSelectedTab(tab);
    };

    const capitalizeFirstLetter = (word: string) => {
        if (!word) return '';
        return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
    };

    useEffect(() => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    setActiveCategory(entry.target.id.replace('category-', ''));
                }
            });
        }, {
            rootMargin: '0px',
            threshold: 0.5
        });

        moduleCategories.forEach(category => {
            const section = sectionRefs.current[category.id];
            if (section) {
                observer.observe(section);
            }
        });

        return () => {
            moduleCategories.forEach(category => {
                const section = sectionRefs.current[category.id];
                if (section) {
                    observer.unobserve(section);
                }
            });
        };
    }, [moduleCategories]);

    const container = {
        hidden: { opacity: 0 },
        show: {
            opacity: 1,
            transition: {
                delayChildren: 0.5
            }
        }
    }

    const item = {
        hidden: { opacity: 0 },
        show: { opacity: 1 }
    }

    return (
        <>
            <Head title="Explore All Products" />
            <div className='sticky top-10 z-50'>
                {/* Pricing Tabs */}
                <div className="flex justify-center mt-2">
                    {['monthly', 'annual'].map((tab) => (
                        <Button
                            key={tab}
                            className={`px-4 py-2 md:px-6 md:py-3 mx-1 md:mx-2 font-semibold ${selectedTab === tab
                                ? 'bg-blue-600 text-white'
                                : 'bg-white text-gray-700 border'
                                } hover:text-white rounded-full transition-all duration-300`}
                            onClick={() => handleTabChange(tab)}
                        >
                            {capitalizeFirstLetter(tab)}
                        </Button>
                    ))}
                </div>
            </div>
            <div className="flex flex-col px-10 relative">
                <aside className="hidden lg:block p-8 fixed top-20 z-60 h-screen w-1/4">
                    <h3 className="text-xl font-semibold mb-4">Featured Apps</h3>
                    <nav>
                        <motion.ul
                            variants={container}
                            initial="hidden"
                            animate="show"
                            className="space-y-4"
                        >
                            {moduleCategories.map((category) => (
                                <motion.li
                                    variants={item}
                                    key={category.id}
                                    className={`relative ${activeCategory === category.id ? 'border-l-2 border-red-500' : ''}`}
                                >
                                    <a href={`#category-${category.id}`} className={`mr-4 text-gray-600 hover:text-primary ${activeCategory === category.id ? 'text-primary' : ''}`}>
                                        {category.name}
                                    </a>
                                    {activeCategory === category.id && <IoIosArrowForward className="absolute right-0 top-1/2 transform -translate-y-1/2 text-red-500" />}
                                </motion.li>
                            ))}
                        </motion.ul>
                    </nav>
                </aside>

                <main className="flex-1 p-6 h-screen ml-auto w-3/4">
                    <div>
                        {moduleCategories.map((category) => (
                            <div
                                key={category.id}
                                id={`category-${category.id}`}
                                ref={el => sectionRefs.current[category.id] = el}
                                className="mb-12"
                            >
                                <h2 className="text-2xl font-bold mb-4">{category.name}</h2>
                                <p className="text-gray-600 mb-6">{category.description}</p>
                                <div className="grid grid-cols-1 sm:grid-cols-1 md:grid-cols- lg:grid-cols-4 gap-6">
                                    {category.modules.map((module) => (
                                        <motion.div
                                            key={module.id}
                                            initial={{ opacity: 0, scale: 0.5 }}
                                            animate={{ opacity: 1, scale: 1 }}
                                            transition={{ duration: 0.5 }}
                                            className="w-full"
                                        >
                                            <ModuleCard
                                                frequency={selectedTab}
                                                module={module}
                                                aspectRatio="square"
                                                width={250}
                                                height={330}
                                            />
                                        </motion.div>
                                    ))}
                                </div>
                                <Separator className="my-8" />
                            </div>
                        ))}
                    </div>
                </main>
            </div>
        </>
    );
}
