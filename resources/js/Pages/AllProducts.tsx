import { Head, usePage } from '@inertiajs/react';
import { Separator } from '@/Components/ui/separator';
import { ModuleCard } from '@/Components/module-card';
import { PageProps } from '@/types';
import { motion } from "framer-motion";
import { useEffect, useRef, useState } from 'react';
import { IoIosArrowForward } from "react-icons/io";

export default function AllProducts() {
    const { moduleCategories } = usePage<PageProps>().props;
    const [activeCategory, setActiveCategory] = useState<string | null>(moduleCategories.length > 0 ? moduleCategories[0].id : null);
    const sectionRefs = useRef<Record<string, HTMLDivElement | null>>({});

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
            <div className="flex">
                <aside className="hidden md:block w-64 bg-gray-100 p-6 sticky top-0 h-screen">
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
                                    <a href={`#category-${category.id}`} className="text-blue-600 hover:underline">
                                        {category.name}
                                    </a>
                                    {activeCategory === category.id && <IoIosArrowForward className="absolute right-0 top-1/2 transform -translate-y-1/2 text-red-500" />}
                                </motion.li>
                            ))}
                        </motion.ul>
                    </nav>
                </aside>

                <main className="flex-1 p-6">
                    {moduleCategories.map((category) => (
                        <div
                            key={category.id}
                            id={`category-${category.id}`}
                            ref={el => sectionRefs.current[category.id] = el}
                            className="mb-12"
                        >
                            <h2 className="text-2xl font-bold mb-4">{category.name}</h2>
                            <p className="text-gray-600 mb-6">{category.description}</p>
                            <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                                {category.modules.map((module) => (
                                    <motion.div
                                        key={module.id}
                                        initial={{ opacity: 0, scale: 0.5 }}
                                        animate={{ opacity: 1, scale: 1 }}
                                        transition={{ duration: 0.5 }}
                                        className="w-full"
                                    >
                                        <ModuleCard
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
                </main>
            </div>
        </>
    );
}
