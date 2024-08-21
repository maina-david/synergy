import { Head, usePage } from '@inertiajs/react';
import { Separator } from '@/Components/ui/separator';
import { ModuleCard } from '@/Components/module-card';
import { PageProps } from '@/types';
import { motion } from "framer-motion";

export default function AllProducts() {
    const { moduleCategories } = usePage<PageProps>().props;

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
                <aside className="w-64 bg-gray-100 p-6 sticky top-0 h-screen">
                    <h3 className="text-xl font-semibold mb-4">Categories</h3>
                    <nav>
                        <motion.ul
                            variants={container}
                            initial="hidden"
                            animate="show"
                            className="space-y-4"
                        >
                            {moduleCategories.map((category) => (
                                <motion.li variants={item} key={category.id}>
                                    <a href={`#category-${category.id}`} className="text-blue-600 hover:underline">
                                        {category.name}
                                    </a>
                                </motion.li>
                            ))}
                        </motion.ul>
                    </nav>
                </aside>

                <main className="flex-1 p-6">
                    {moduleCategories.map((category) => (
                        <section key={category.id} id={`category-${category.id}`} className="mb-12">
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
                        </section>
                    ))}
                </main>
            </div>
        </>
    );
}
